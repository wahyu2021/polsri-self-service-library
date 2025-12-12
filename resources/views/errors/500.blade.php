@extends('components.layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center text-center px-4">
    <div class="w-24 h-24 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mb-6 shadow-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    </div>
    
    <h1 class="text-4xl font-bold text-slate-900 mb-2 tracking-tight">Terjadi Kesalahan Server</h1>
    <p class="text-slate-500 max-w-md mx-auto mb-8">Maaf, terjadi kesalahan internal di sisi kami. Tim teknis telah dinotifikasi.</p>
    
    <div class="flex gap-4">
        <button onclick="location.reload()" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Coba Lagi
        </button>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-polsri-primary hover:bg-orange-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20">
            Ke Beranda
        </a>
    </div>
</div>
@endsection
