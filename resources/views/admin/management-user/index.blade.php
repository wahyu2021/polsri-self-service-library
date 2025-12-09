<x-layouts.admin title="Manajemen Pengguna">

    <!-- Header & Actions -->
    <x-ui.header title="Data Pengguna" subtitle="Kelola akun Admin dan Mahasiswa.">
        
        <!-- Search Form -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-3">
            
            <!-- Custom Alpine.js Dropdown for Role -->
            <div x-data="{ 
                    open: false, 
                    role: '{{ request('role') }}',
                    label: '{{ request('role') == 'admin' ? 'Admin' : (request('role') == 'student' ? 'Mahasiswa' : 'Semua Role') }}'
                }" 
                class="relative">
                
                <input type="hidden" name="role" :value="role">

                <button type="button" @click="open = !open" @click.outside="open = false"
                    class="flex items-center justify-between gap-2 pl-4 pr-3 py-2.5 bg-white rounded-xl border border-slate-200 text-sm font-medium text-slate-700 hover:border-slate-300 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition shadow-sm min-w-[150px]">
                    <span x-text="label"></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 mt-2 w-full min-w-[160px] bg-white rounded-xl shadow-xl border border-slate-100 ring-1 ring-black/5 overflow-hidden origin-top-left focus:outline-none"
                    style="display: none;">
                    
                    <div class="p-1">
                        <button type="button" @click="role = ''; label = 'Semua Role'; open = false; $el.closest('form').submit()"
                            class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                            :class="role === '' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                            <span>Semua Role</span>
                            <svg x-show="role === ''" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>

                        <button type="button" @click="role = 'admin'; label = 'Admin'; open = false; $el.closest('form').submit()"
                            class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                            :class="role === 'admin' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                <span>Admin</span>
                            </div>
                            <svg x-show="role === 'admin'" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>

                        <button type="button" @click="role = 'student'; label = 'Mahasiswa'; open = false; $el.closest('form').submit()"
                            class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                            :class="role === 'student' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span>Mahasiswa</span>
                            </div>
                            <svg x-show="role === 'student'" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari user..." 
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary w-full sm:w-64 transition-all shadow-sm group-hover:border-slate-300">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5 pointer-events-none group-hover:text-slate-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </form>

        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </a>
    </x-ui.header>

    @if(session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-ui.alert type="error" :message="session('error')" />
    @endif

    <!-- Users Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">NIM</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="font-medium text-slate-900">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role == 'admin')
                                <x-ui.badge color="purple">Admin</x-ui.badge>
                            @else
                                <x-ui.badge color="blue">Student</x-ui.badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-slate-600 font-mono">{{ $user->nim ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">Tidak ada user ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $users->withQueryString()->links() }}
        </div>
    </x-ui.card>

</x-layouts.admin>