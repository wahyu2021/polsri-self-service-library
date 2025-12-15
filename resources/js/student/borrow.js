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

// Handle Borrow Form Submit
const borrowForm = document.getElementById('borrow-form');
const btnSubmit = document.getElementById('btn-borrow-submit');

if (borrowForm && btnSubmit) {
    borrowForm.addEventListener('submit', function() {
        // Disable button to prevent double submit
        btnSubmit.disabled = true;
        btnSubmit.classList.add('cursor-not-allowed', 'opacity-90'); // Slight dim, keeping visibility

        // Add animate.css Pulse effect to the whole button to show activity
        btnSubmit.classList.add('animate__animated', 'animate__pulse', 'animate__infinite');
        
        // Change text & icon
        btnSubmit.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="animate__animated animate__fadeIn">Memproses...</span>
        `;
    });
}
