import { Html5Qrcode } from "html5-qrcode";

const isbnInput = document.getElementById('isbn-input');
const manualInputContainer = document.getElementById('manual-input-container');
const loading = document.getElementById('loading-indicator');
const errorMsg = document.getElementById('error-message');
const stepScan = document.getElementById('step-scan');
const stepPreview = document.getElementById('step-preview');
const scannerContainer = document.getElementById('scanner-container');
const scanIllustration = document.getElementById('scan-illustration');

// Config from Blade
const lookupUrl = window.AppConfig.lookupUrl;
const csrfToken = window.AppConfig.csrfToken;

let html5QrcodeScanner = null;

// Handle "Enter" key on input
if (isbnInput) {
    isbnInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            checkBook();
        }
    });
}

window.startScanner = function() {
    scanIllustration.classList.add('hidden');
    manualInputContainer.classList.add('hidden'); // Hide manual input
    scannerContainer.classList.remove('hidden');

    html5QrcodeScanner = new Html5Qrcode("reader");
    
    // Prefer back camera, specific formats for barcodes
    const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 150 },
        aspectRatio: 1.0
    };
    
    html5QrcodeScanner.start(
        { facingMode: "environment" }, 
        config, 
        (decodedText) => {
            // Success
            stopScanner();
            isbnInput.value = decodedText;
            checkBook();
        },
        (errorMessage) => {
            // Parsing error, ignore
        }
    ).catch(err => {
        console.error("Error starting scanner", err);
        alert("Gagal membuka kamera. Pastikan izin diberikan.");
        stopScanner();
    });
}

window.stopScanner = function() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }).catch(err => console.error(err));
    }
    scannerContainer.classList.add('hidden');
    scanIllustration.classList.remove('hidden');
    manualInputContainer.classList.remove('hidden'); // Show manual input back
}

window.checkBook = async function() {
    const isbn = isbnInput.value.trim();
    if(!isbn) return;

    loading.classList.remove('hidden');
    errorMsg.classList.add('hidden');
    isbnInput.disabled = true;

    try {
        const response = await fetch(lookupUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ isbn: isbn })
        });

        if (!response.ok) throw new Error('Buku tidak ditemukan.');

        const data = await response.json();
        showPreview(data, isbn);

    } catch (error) {
        errorMsg.innerText = error.message;
        errorMsg.classList.remove('hidden');
        isbnInput.disabled = false;
        isbnInput.focus();
    } finally {
        loading.classList.add('hidden');
    }
}

function showPreview(data, isbn) {
    stepScan.classList.add('hidden');
    stepPreview.classList.remove('hidden');

    document.getElementById('preview-title').innerText = data.title;
    document.getElementById('preview-author').innerText = data.author;
    document.getElementById('confirm-isbn').value = isbn;

    const coverImg = document.getElementById('preview-cover');
    if (data.cover) {
        coverImg.src = data.cover;
        coverImg.classList.remove('opacity-0');
    } else {
        coverImg.src = ''; 
    }
}

window.resetScan = function() {
    stepPreview.classList.add('hidden');
    stepScan.classList.remove('hidden');
    isbnInput.value = '';
    isbnInput.disabled = false;
    isbnInput.focus();
}
