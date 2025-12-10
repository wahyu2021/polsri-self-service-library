@props(['notifications'])

<div id="notification-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="notification-content">
        
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <span class="text-xl">🔔</span> Notifikasi
            </h3>
            <button onclick="closeNotification()" class="text-white/70 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Content Body -->
        <div class="p-6">
            
            <!-- 1. Denda (Priority High) -->
            @if($notifications['unpaid_fines']->count() > 0)
                <div class="mb-4 bg-rose-50 border border-rose-100 rounded-xl p-4 flex gap-4 items-start" data-type="fine">
                    <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 shrink-0 text-xl">
                        💸
                    </div>
                    <div>
                        <h4 class="font-bold text-rose-700 text-sm">Denda Belum Dibayar</h4>
                        <p class="text-xs text-rose-600 mt-1 leading-relaxed">
                            Anda memiliki {{ $notifications['unpaid_fines']->count() }} transaksi denda yang belum lunas. Harap segera hubungi admin.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 2. Overdue (Priority High) -->
            @if($notifications['overdue']->count() > 0)
                <div class="mb-4 bg-rose-50 border border-rose-100 rounded-xl p-4 flex gap-4 items-start" data-type="overdue">
                    <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 shrink-0 text-xl">
                        ⚠️
                    </div>
                    <div>
                        <h4 class="font-bold text-rose-700 text-sm">Terlambat Pengembalian</h4>
                        <p class="text-xs text-rose-600 mt-1 leading-relaxed">
                            {{ $notifications['overdue']->count() }} buku telah melewati batas waktu. Kembalikan segera untuk menghindari denda bertambah.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 3. Due Soon (Priority Medium) -->
            @if($notifications['due_soon']->count() > 0)
                <div class="mb-4 bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-4 items-start" data-type="due_soon">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 shrink-0 text-xl">
                        ⏳
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-700 text-sm">Jatuh Tempo Besok</h4>
                        <p class="text-xs text-amber-600 mt-1 leading-relaxed">
                            {{ $notifications['due_soon']->count() }} buku harus dikembalikan besok atau hari ini.
                        </p>
                    </div>
                </div>
            @endif

            @if($notifications['unpaid_fines']->isEmpty() && $notifications['overdue']->isEmpty() && $notifications['due_soon']->isEmpty())
                <p class="text-center text-slate-400 text-sm">Tidak ada notifikasi baru.</p>
            @endif

        </div>

        <!-- Footer -->
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 text-center">
            <button onclick="closeNotification()" class="w-full bg-slate-900 text-white font-bold py-3 rounded-xl hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const hasFines = {{ $notifications['unpaid_fines']->count() > 0 ? 'true' : 'false' }};
        const hasOverdue = {{ $notifications['overdue']->count() > 0 ? 'true' : 'false' }};
        const hasDueSoon = {{ $notifications['due_soon']->count() > 0 ? 'true' : 'false' }};
        
        const hasAnyNotification = hasFines || hasOverdue || hasDueSoon;

        if (hasAnyNotification) {
            checkAndShowNotification(hasFines);
        }
    });

    function checkAndShowNotification(isCriticalFine) {
        const lastSeen = localStorage.getItem('last_notification_date');
        const today = new Date().toISOString().split('T')[0];

        // Logic: 
        // 1. If critical fine, ALWAYS show (or maybe just once a day? The prompt said "1 hari sekali").
        // 2. Let's stick to "Once a Day" for all types to be non-intrusive, 
        //    UNLESS the user explicitly clears local storage.
        
        if (lastSeen !== today) {
            showNotification();
        }
    }

    function showNotification() {
        const modal = document.getElementById('notification-modal');
        const content = document.getElementById('notification-content');
        
        modal.classList.remove('hidden');
        // Force reflow
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }

    window.closeNotification = function() {
        const modal = document.getElementById('notification-modal');
        const content = document.getElementById('notification-content');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        // Save today's date to prevent showing again today
        const today = new Date().toISOString().split('T')[0];
        localStorage.setItem('last_notification_date', today);

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
