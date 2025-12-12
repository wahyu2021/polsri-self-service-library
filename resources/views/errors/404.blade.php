@extends('components.layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center text-center px-4">
    <div class="w-24 h-24 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mb-6 shadow-xl shadow-orange-500/10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    
    <h1 class="text-4xl font-bold text-slate-900 mb-2 tracking-tight">Halaman Tidak Ditemukan</h1>
    <p class="text-slate-500 max-w-md mx-auto mb-8">Maaf, halaman yang Anda cari mungkin telah dihapus, dipindahkan, atau tidak tersedia.</p>
    
    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-polsri-primary hover:bg-orange-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 hover:-translate-y-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Beranda
    </a>
</div>
@endsection
