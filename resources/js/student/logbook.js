import { Html5Qrcode } from "html5-qrcode";

const gpsDot = document.getElementById("gps-dot");
const gpsText = document.getElementById("gps-text");
const gpsBtnText = document.getElementById("gps-btn-text");
const statusDisplay = document.getElementById("status-display");
let currentLat = null;
let currentLng = null;
let html5QrcodeScanner = null;
let isProcessing = false;
let watchId = null;

// Sound Effect
const beepSound = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU"); // Short beep placeholder

const checkInUrl = window.AppConfig.checkInUrl;
const dashboardUrl = window.AppConfig.dashboardUrl;
const csrfToken = window.AppConfig.csrfToken;

document.addEventListener("DOMContentLoaded", () => {
    startGpsTracking();
    startScanner();
});

// 1. Real-time GPS Tracking (Best Practice)
function startGpsTracking() {
    gpsText.innerText = "Mencari satelit...";
    gpsBtnText.innerText = "Mencari satelit...";
    gpsDot.className = "w-2 h-2 rounded-full bg-amber-400 animate-pulse";

    if (navigator.geolocation) {
        watchId = navigator.geolocation.watchPosition(
            (position) => {
                currentLat = position.coords.latitude;
                currentLng = position.coords.longitude;
                const accuracy = Math.round(position.coords.accuracy);

                // Visual Feedback based on accuracy
                let accuracyText = `Akurasi: ${accuracy}m`;
                let dotClass = "w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]";
                
                if (accuracy > 50) {
                    dotClass = "w-2 h-2 rounded-full bg-yellow-400"; // Low accuracy warning
                }

                gpsText.innerText = `Lokasi Terkunci (${accuracyText})`;
                gpsBtnText.innerText = "Lokasi Terkunci";
                gpsDot.className = dotClass;

                // Auto update hidden inputs
                const latInput = document.getElementById("input_latitude");
                const lngInput = document.getElementById("input_longitude");
                if(latInput) latInput.value = currentLat;
                if(lngInput) lngInput.value = currentLng;
            },
            (error) => {
                console.error("GPS Error:", error);
                let errMsg = "Gagal mengambil lokasi.";
                if (error.code === 1) errMsg = "Izin Lokasi Ditolak.";
                if (error.code === 2) errMsg = "Sinyal GPS Lemah.";
                if (error.code === 3) errMsg = "Waktu Habis (Timeout).";

                gpsText.innerText = errMsg;
                gpsBtnText.innerText = "Coba Lagi (Wajib GPS)";
                gpsDot.className = "w-2 h-2 rounded-full bg-rose-500";
            },
            {
                enableHighAccuracy: true, // Force GPS hardware usage
                timeout: 20000,
                maximumAge: 5000, // Accept cache no older than 5s
            }
        );
    } else {
        alert("Browser ini tidak mendukung Geolocation.");
    }
}

// 2. Robust Scanner Initialization
function startScanner() {
    if (html5QrcodeScanner) {
        return;
    }

    html5QrcodeScanner = new Html5Qrcode("reader");
    
    // Config optimized for mobile scanning
    const config = {
        fps: 10,
        qrbox: function(viewfinderWidth, viewfinderHeight) {
            // Rectangular scanning area (better for long QR codes or various distances)
            const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
            return {
                width: Math.floor(minEdge * 0.7),
                height: Math.floor(minEdge * 0.7)
            };
        },
        // experimentalFeatures: {
        //     useBarCodeDetectorIfSupported: true
        // }
    };

    html5QrcodeScanner
        .start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess
        )
        .then(() => {
            console.log("Kamera berhasil dimuat.");
            const fallback = document.getElementById("camera-fallback");
            if (fallback) fallback.classList.add("hidden");
        })
        .catch((err) => {
            console.error("Camera Init Error:", err);
            const fallback = document.getElementById("camera-fallback");
            if (fallback) {
                fallback.innerText = "Gagal Memuat Kamera. Pastikan izin diberikan.";
                fallback.classList.remove("text-white/30");
                fallback.classList.add("text-rose-500", "font-bold");
            }
        });
}

function onScanSuccess(decodedText) {
    if (isProcessing) return;

    // 3. Instant Feedback: Pause Camera & Beep
    isProcessing = true;
    try {
        html5QrcodeScanner.pause(true); // Freeze the frame
        // beepSound.play().catch(e => console.log("Audio play blocked", e));
    } catch (e) {
        console.warn("Failed to pause scanner", e);
    }
    
    console.log("QR Terdeteksi: ", decodedText);
    processCheckIn(decodedText);
}

async function processCheckIn(qrValue) {
    // Validate Location Presence
    if (!currentLat || !currentLng) {
        showFeedback(false, "GPS Error", "Menunggu sinyal GPS... Silakan tunggu indikator hijau.");
        resumeScanner(); // Let user try again
        return;
    }

    document.getElementById("input_qr_code").value = qrValue;
    const formData = new FormData(document.getElementById("checkin-form"));

    statusDisplay.classList.remove("hidden");
    statusDisplay.className = "mb-4 p-3 rounded-xl text-center text-sm font-bold bg-slate-100 text-slate-500 animate-pulse";
    statusDisplay.innerText = "Memverifikasi...";

    try {
        const response = await fetch(checkInUrl, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken, Accept: "application/json" },
            body: formData,
        });
        const result = await response.json();

        if (result.success) {
            showFeedback(true, "Berhasil!", result.message);
            
            // Redirect after delay
            setTimeout(() => {
                window.location.href = dashboardUrl;
            }, 2000);
        } else {
            showFeedback(false, "Gagal Masuk", result.message);
            resumeScanner(); // Allow retry
        }
    } catch (error) {
        console.error(error);
        showFeedback(false, "Error Sistem", "Terjadi kesalahan jaringan atau server.");
        resumeScanner(); // Allow retry
    }
}

function resumeScanner() {
    setTimeout(() => {
        isProcessing = false;
        statusDisplay.classList.add("hidden");
        try {
            html5QrcodeScanner.resume();
        } catch (e) {
            console.warn("Failed to resume scanner", e);
        }
    }, 2500); // Delay slightly longer to let user read the error
}

function showFeedback(isSuccess, title, message) {
    const overlay = document.getElementById("feedback-overlay");
    const content = document.getElementById("feedback-content");
    const icon = document.getElementById("feedback-icon");
    const titleEl = document.getElementById("feedback-title");
    const msgEl = document.getElementById("feedback-message");

    overlay.classList.remove("hidden");
    // Force reflow
    void overlay.offsetWidth;
    overlay.classList.remove("opacity-0");
    
    content.classList.remove("scale-50");
    content.classList.add("scale-100");

    if (isSuccess) {
        icon.innerHTML = "✅";
        titleEl.className = "text-3xl font-bold text-emerald-400 mb-2";
    } else {
        icon.innerHTML = "❌";
        titleEl.className = "text-3xl font-bold text-rose-500 mb-2";
    }

    titleEl.innerText = title;
    msgEl.innerText = message;

    // Auto hide feedback is handled by resumeScanner or redirect
    if (!isSuccess) {
        setTimeout(() => {
            overlay.classList.add("opacity-0");
            content.classList.remove("scale-100");
            content.classList.add("scale-50");
            setTimeout(() => {
                overlay.classList.add("hidden");
            }, 300);
        }, 2000);
    }
}

// Expose location retry to window for the button
window.getCurrentLocation = function() {
    // Restart logic if button clicked manually
    if (watchId) navigator.geolocation.clearWatch(watchId);
    startGpsTracking();
};
