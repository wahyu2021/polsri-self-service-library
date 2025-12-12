import { Html5Qrcode } from "html5-qrcode";

const gpsDot = document.getElementById("gps-dot");
const gpsText = document.getElementById("gps-text");
const gpsBtnText = document.getElementById("gps-btn-text");
const statusDisplay = document.getElementById("status-display");
let currentLat = null;
let currentLng = null;
let html5QrcodeScanner = null;

// Config from Blade
const checkInUrl = window.AppConfig.checkInUrl;
const dashboardUrl = window.AppConfig.dashboardUrl;
const csrfToken = window.AppConfig.csrfToken;

document.addEventListener("DOMContentLoaded", () => {
    getCurrentLocation();
    startScanner();
});

window.getCurrentLocation = function () {
    gpsText.innerText = "Mencari satelit...";
    gpsBtnText.innerText = "Mencari satelit...";
    gpsDot.className = "w-2 h-2 rounded-full bg-amber-400 animate-pulse";

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLat = position.coords.latitude;
                currentLng = position.coords.longitude;

                // Update UI
                gpsText.innerText =
                    "Lokasi Terkunci (Akurasi: " +
                    Math.round(position.coords.accuracy) +
                    "m)";
                gpsBtnText.innerText = "Lokasi Terkunci";
                gpsDot.className =
                    "w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]";

                // Set Input Values
                document.getElementById("input_latitude").value = currentLat;
                document.getElementById("input_longitude").value = currentLng;
            },
            (error) => {
                console.error(error);
                let errMsg = "Gagal mengambil lokasi.";
                if (error.code === 1) errMsg = "Izin Lokasi Ditolak.";
                if (error.code === 2) errMsg = "Sinyal GPS Lemah.";

                gpsText.innerText = errMsg;
                gpsBtnText.innerText = "Coba Lagi (Wajib GPS)";
                gpsDot.className = "w-2 h-2 rounded-full bg-rose-500";
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            }
        );
    } else {
        alert("Browser ini tidak mendukung Geolocation.");
    }
};

function startScanner() {
    html5QrcodeScanner = new Html5Qrcode("reader");
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
    };

    html5QrcodeScanner
        .start({ facingMode: "environment" }, config, onScanSuccess)
        .then(() => {
            const fallback = document.getElementById("camera-fallback");
            if (fallback) {
                fallback.classList.add("none");
            }
        })
        .catch((err) => {
            console.error("Camera Error", err);
            const fallback = document.getElementById("camera-fallback");
            if (fallback) {
                fallback.innerText = "Gagal Memuat Kamera. Pastikan Izin Diberikan.";
                fallback.classList.remove("text-white/30");
                fallback.classList.add("text-red-500");
            }
        });
}

function onScanSuccess(decodedText) {
    if (html5QrcodeScanner) html5QrcodeScanner.pause();
    processCheckIn(decodedText);
}

async function processCheckIn(qrValue) {
    if (!currentLat) {
        showFeedback(
            false,
            "GPS Error",
            "Lokasi belum ditemukan. Pastikan GPS aktif."
        );
        if (html5QrcodeScanner) html5QrcodeScanner.resume();
        return;
    }

    document.getElementById("input_qr_code").value = qrValue;
    const formData = new FormData(document.getElementById("checkin-form"));

    statusDisplay.classList.remove("hidden");
    statusDisplay.className =
        "mb-4 p-3 rounded-xl text-center text-sm font-bold bg-slate-100 text-slate-500";
    statusDisplay.innerText = "Memverifikasi Lokasi...";

    try {
        const response = await fetch(checkInUrl, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken, Accept: "application/json" },
            body: formData,
        });
        const result = await response.json();

        if (result.success) {
            showFeedback(true, "Berhasil!", result.message);
            setTimeout(() => (window.location.href = dashboardUrl), 2000);
        } else {
            showFeedback(false, "Gagal Masuk", result.message);
            // Resume scanning after 3s if failed
            setTimeout(() => {
                if (html5QrcodeScanner) html5QrcodeScanner.resume();
                statusDisplay.classList.add("hidden");
            }, 3000);
        }
    } catch (error) {
        showFeedback(false, "Error Sistem", "Terjadi kesalahan jaringan.");
        setTimeout(() => {
            if (html5QrcodeScanner) html5QrcodeScanner.resume();
        }, 3000);
    }
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

    setTimeout(() => {
        overlay.classList.add("opacity-0");
        content.classList.remove("scale-100");
        content.classList.add("scale-50");
        setTimeout(() => {
            overlay.classList.add("hidden");
        }, 300);
    }, 2000);
}
