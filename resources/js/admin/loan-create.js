import { Html5Qrcode } from "html5-qrcode";

document.addEventListener('DOMContentLoaded', () => {
    // 1. Config & DOM Elements
    const config = window.AdminConfig || { searchUserUrl: '', searchBookUrl: '' };
    
    const inputNim = document.getElementById('input_nim');
    const inputIsbn = document.getElementById('input_isbn');
    const scannerContainer = document.getElementById('scanner_container');
    const submitBtn = document.getElementById('btn_submit');
    
    // Suggestion Containers
    const studentSuggestions = document.getElementById('student_suggestions');
    const bookSuggestions = document.getElementById('book_suggestions');

    // Error Containers
    const errorStudent = document.getElementById('error_student');
    const errorBook = document.getElementById('error_book');

    // Buttons Scanner
    const btnScanStudent = document.getElementById('btn_scan_student');
    const btnScanBook = document.getElementById('btn_scan_book');

    let html5QrCode = null;
    let scanTarget = null; // 'student' atau 'book'
    let debounceTimer = null;

    // Expose Functions
    window.startScanner = startScanner;
    window.stopScanner = stopScanner;
    window.searchStudent = searchStudent;
    window.searchBook = searchBook;
    window.resetStudent = resetStudent;
    window.resetBook = resetBook;

    // --- EVENT LISTENERS ---

    // Handle Enter Key
    if (inputNim) {
        inputNim.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); searchStudent(); }
        });
        inputNim.addEventListener('input', (e) => handleInput(e, 'student'));
        // Hide suggestions on blur (delayed to allow click)
        inputNim.addEventListener('blur', () => setTimeout(() => hideSuggestions('student'), 200));
        inputNim.addEventListener('focus', (e) => handleInput(e, 'student')); // Show on focus if has value
    }
    if (inputIsbn) {
        inputIsbn.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); searchBook(); }
        });
        inputIsbn.addEventListener('input', (e) => handleInput(e, 'book'));
        inputIsbn.addEventListener('blur', () => setTimeout(() => hideSuggestions('book'), 200));
        inputIsbn.addEventListener('focus', (e) => handleInput(e, 'book'));
    }

    // --- AUTOCOMPLETE / SUGGESTIONS ---

    function handleInput(e, type) {
        const value = e.target.value.trim();
        const suggestionBox = type === 'student' ? studentSuggestions : bookSuggestions;
        
        hideError(type);

        if (value.length < 2) {
            hideSuggestions(type);
            return;
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchSuggestions(type, value);
        }, 300);
    }

    async function fetchSuggestions(type, query) {
        const url = type === 'student' 
            ? `${config.searchUserUrl}?q=${encodeURIComponent(query)}`
            : `${config.searchBookUrl}?q=${encodeURIComponent(query)}`;

        try {
            const res = await fetch(url);
            const data = await res.json();
            
            if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                renderSuggestions(type, data.data);
            } else {
                hideSuggestions(type);
            }
        } catch (err) {
            console.error(err);
        }
    }

    function renderSuggestions(type, items) {
        const container = type === 'student' ? studentSuggestions : bookSuggestions;
        container.innerHTML = '';
        container.classList.remove('hidden');

        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-none transition-colors';
            
            if (type === 'student') {
                div.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                            ${item.name.charAt(0)}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">${item.name}</p>
                            <p class="text-xs text-slate-500 font-mono">${item.nim}</p>
                        </div>
                    </div>
                `;
                div.onclick = () => selectSuggestion('student', item);
            } else {
                div.innerHTML = `
                     <div class="flex items-start gap-3">
                         <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 line-clamp-1">${item.title}</p>
                            <p class="text-xs text-slate-500 font-mono">${item.isbn} <span class="mx-1">•</span> ${item.author}</p>
                        </div>
                        ${item.stock > 0 
                            ? `<span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Stok: ${item.stock}</span>`
                            : `<span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">Habis</span>`
                        }
                    </div>
                `;
                div.onclick = () => selectSuggestion('book', item);
            }
            
            container.appendChild(div);
        });
    }

    function selectSuggestion(type, item) {
        if (type === 'student') {
            inputNim.value = item.nim;
            // Langsung trigger pencarian final untuk validasi & set UI
            searchStudent(); 
        } else {
            inputIsbn.value = item.isbn;
            searchBook();
        }
        hideSuggestions(type);
    }

    function hideSuggestions(type) {
        const container = type === 'student' ? studentSuggestions : bookSuggestions;
        if(container) {
            setTimeout(() => container.classList.add('hidden'), 100); 
        }
    }


    // --- SCANNER LOGIC ---

    function startScanner(target) {
        // Toggle feature
        if (scanTarget === target && !scannerContainer.classList.contains('hidden')) {
            stopScanner();
            return;
        }

        scanTarget = target;
        scannerContainer.classList.remove('hidden');
        
        setButtonState(target, true);
        hideError('student');
        hideError('book');

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

        html5QrCode.start(
            { facingMode: "environment" }, 
            qrConfig,
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Camera Error:", err);
            showError(target, "Gagal mengakses kamera. Pastikan izin diberikan.");
            stopScanner();
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        stopScanner();
        if (scanTarget === 'student') {
            if(inputNim) {
                inputNim.value = decodedText;
                searchStudent();
            }
        } else if (scanTarget === 'book') {
            if(inputIsbn) {
                inputIsbn.value = decodedText;
                searchBook();
            }
        }
    }

    function onScanFailure(error) {
        // Ignore noise
    }

    function stopScanner() {
        setButtonState(null, false);
        scanTarget = null;
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                scannerContainer.classList.add('hidden');
            }).catch(err => {
                scannerContainer.classList.add('hidden');
            });
        } else {
            scannerContainer.classList.add('hidden');
        }
    }

    function setButtonState(target, isActive) {
        [btnScanStudent, btnScanBook].forEach(btn => {
            if(btn) {
                const iconOff = btn.querySelector('.icon-off');
                const iconOn = btn.querySelector('.icon-on');
                if(iconOff) iconOff.classList.remove('hidden');
                if(iconOn) iconOn.classList.add('hidden');
                btn.classList.remove('bg-rose-100', 'text-rose-600', 'border-rose-200');
                btn.classList.add('bg-white', 'text-slate-500', 'border-slate-200'); // Reset to default white
            }
        });

        if (isActive && target) {
            let activeBtn = target === 'student' ? btnScanStudent : btnScanBook;
            if(activeBtn) {
                const iconOff = activeBtn.querySelector('.icon-off');
                const iconOn = activeBtn.querySelector('.icon-on');
                if(iconOff) iconOff.classList.add('hidden');
                if(iconOn) iconOn.classList.remove('hidden');
                activeBtn.classList.remove('bg-white', 'text-slate-500', 'border-slate-200');
                activeBtn.classList.add('bg-rose-100', 'text-rose-600', 'border-rose-200');
            }
        }
    }

    // --- SEARCH & UI STATE ---
    
    async function searchStudent() {
        const nim = inputNim.value.trim();
        hideError('student');
        if(!nim) return showError('student', 'Masukkan NIM terlebih dahulu!');
        
        setLoading(inputNim, true);
        try {
            const res = await fetch(`${config.searchUserUrl}?nim=${nim}`);
            const data = await res.json();
            
            if(data.success && data.data && !Array.isArray(data.data)) { // Ensure single object result
                document.getElementById('res_student_name').innerText = data.data.name;
                document.getElementById('res_student_nim').innerText = data.data.nim;
                document.getElementById('final_user_id').value = data.data.id;
                
                document.getElementById('student_result').classList.remove('hidden', 'opacity-0', 'translate-y-2');
                inputNim.disabled = true;
                inputNim.classList.add('bg-slate-100', 'text-slate-400');
                
                if(inputIsbn && !inputIsbn.disabled) inputIsbn.focus();
            } else {
                showError('student', data.message || 'Mahasiswa tidak ditemukan.');
                inputNim.value = ''; 
                inputNim.focus();
            }
        } catch (err) { 
            console.error(err); 
            showError('student', 'Terjadi kesalahan server.'); 
        } finally { 
            setLoading(inputNim, false); 
            checkFormValidity(); 
        }
    }

    async function searchBook() {
        const isbn = inputIsbn.value.trim();
        hideError('book');
        if(!isbn) return showError('book', 'Scan ISBN terlebih dahulu!');
        
        setLoading(inputIsbn, true);
        try {
            const res = await fetch(`${config.searchBookUrl}?isbn=${isbn}`);
            const data = await res.json();
            
            if(data.success && data.data && !Array.isArray(data.data)) {
                document.getElementById('res_book_title').innerText = data.data.title;
                document.getElementById('res_book_isbn').innerText = data.data.isbn;
                document.getElementById('res_book_author').innerText = data.data.author;
                document.getElementById('final_book_id').value = data.data.id;
                
                document.getElementById('book_result').classList.remove('hidden', 'opacity-0', 'translate-y-2');
                inputIsbn.disabled = true;
                inputIsbn.classList.add('bg-slate-100', 'text-slate-400');
            } else {
                showError('book', data.message || 'Buku tidak ditemukan.');
                inputIsbn.value = ''; 
                inputIsbn.focus();
            }
        } catch (err) { 
            console.error(err); 
            showError('book', 'Terjadi kesalahan server.'); 
        } finally { 
            setLoading(inputIsbn, false); 
            checkFormValidity(); 
        }
    }

    function checkFormValidity() {
        const userId = document.getElementById('final_user_id').value;
        const bookId = document.getElementById('final_book_id').value;
        
        if(userId && bookId) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'shadow-none');
            submitBtn.classList.add('bg-polsri-primary', 'hover:bg-orange-600', 'text-white', 'shadow-lg', 'shadow-orange-500/20');
            submitBtn.querySelector('span').innerText = 'Proses Peminjaman';
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'shadow-none');
            submitBtn.classList.remove('bg-polsri-primary', 'hover:bg-orange-600', 'text-white', 'shadow-lg', 'shadow-orange-500/20');
            submitBtn.querySelector('span').innerText = 'Lengkapi Data';
        }
    }

    function resetStudent() {
        document.getElementById('final_user_id').value = '';
        document.getElementById('student_result').classList.add('hidden');
        inputNim.disabled = false; 
        inputNim.classList.remove('bg-slate-100', 'text-slate-400');
        inputNim.value = ''; 
        inputNim.focus(); 
        checkFormValidity();
        hideError('student');
    }

    function resetBook() {
        document.getElementById('final_book_id').value = '';
        document.getElementById('book_result').classList.add('hidden');
        inputIsbn.disabled = false; 
        inputIsbn.classList.remove('bg-slate-100', 'text-slate-400');
        inputIsbn.value = ''; 
        inputIsbn.focus(); 
        checkFormValidity();
        hideError('book');
    }

    function setLoading(inputElement, isLoading) {
        if(!inputElement) return;
        const parent = inputElement.parentElement;
        const btn = parent.querySelector('button:last-child'); 
        if(btn) {
            if(isLoading) { 
                btn.dataset.originalText = btn.innerText;
                btn.innerText = '...'; 
                btn.disabled = true; 
            } else { 
                btn.innerText = btn.dataset.originalText || 'Cari'; 
                btn.disabled = false; 
            }
        }
    }

    function showError(type, message) {
        const el = type === 'student' ? errorStudent : errorBook;
        if(el) {
            el.innerText = message;
            el.classList.remove('hidden');
        } else {
            alert(message);
        }
    }

    function hideError(type) {
        const el = type === 'student' ? errorStudent : errorBook;
        if(el) el.classList.add('hidden');
    }
});
