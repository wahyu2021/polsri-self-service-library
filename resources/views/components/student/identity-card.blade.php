@props(['user', 'activeLoans'])

<div class="bg-white px-6 pt-8 pb-8 rounded-b-[2.5rem] lg:rounded-[2.5rem] shadow-xl shadow-slate-200/60 lg:sticky lg:top-24 z-10">
    
    <div class="flex justify-between items-center mb-6 lg:mb-8">
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Selamat Datang</p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ explode(' ', $user->name)[0] }}</h1>
        </div>
        <a href="{{ route('student.profile.index') }}" class="relative group block lg:hidden">
            <div class="w-12 h-12 rounded-full bg-slate-100 border-2 border-white shadow-lg overflow-hidden">
                <img src="{{ $user->avatar ? \Illuminate\Support\Facades\Storage::url($user->avatar) : asset('images/default-profile.jpg') }}"
                     alt="Profile" 
                     class="w-full h-full object-cover">
            </div>
        </a>
    </div>

    <div class="relative w-full aspect-[1.6/1] bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-6 shadow-2xl shadow-orange-500/40 overflow-hidden group hover:scale-[1.02] transition-transform duration-500">
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-full h-full opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white to-transparent"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-between text-white">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center border border-white/10">
                        <span class="font-bold text-lg">P</span>
                    </div>
                    <span class="font-bold tracking-widest text-xs opacity-90">POLSRI LIB</span>
                </div>
                <span class="text-xs font-mono font-medium opacity-80 bg-black/20 px-2 py-1 rounded-lg">{{ $user->nim }}</span>
            </div>

            <div class="flex items-end justify-between">
                <div>
                    <p class="text-[10px] text-orange-100 uppercase tracking-wider mb-1">Status</p>
                    <p class="text-2xl font-bold leading-none">Mahasiswa</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-orange-100 uppercase tracking-wider mb-1">Dipinjam</p>
                    <p class="text-3xl font-black leading-none">{{ $activeLoans->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $overdueCount = $activeLoans->filter(fn($l) => \Carbon\Carbon::now()->gt($l->due_date))->count();
    @endphp
    @if($overdueCount > 0)
        <div class="mt-6 flex items-center gap-3 p-4 bg-rose-50 border border-rose-100 rounded-2xl animate-pulse">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h3 class="font-bold text-rose-700 text-xs">Terlambat!</h3>
                <p class="text-[10px] text-rose-500 font-medium">Kembalikan {{ $overdueCount }} buku segera.</p>
            </div>
        </div>
    @endif
</div>