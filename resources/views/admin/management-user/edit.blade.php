<x-layouts.admin title="Edit User">

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:border-slate-300 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Data User</h1>
                <p class="text-slate-500 text-sm mt-1">Perbarui informasi akun pengguna.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <x-ui.input label="Nama Lengkap" name="name" type="text"
                        value="{{ old('name', $user->name) }}" required />

                    <div class="grid grid-cols-2 gap-6">
                        @php
                            $roleOptions = [
                                [
                                    'value' => \App\Enums\UserRole::MAHASISWA->value,
                                    'label' => 'Mahasiswa',
                                    'color' => 'blue',
                                ],
                                [
                                    'value' => \App\Enums\UserRole::ADMIN->value,
                                    'label' => 'Admin / Petugas',
                                    'color' => 'purple',
                                ],
                            ];

                            $currentRoleValue =
                                $user->role instanceof \App\Enums\UserRole ? $user->role->value : $user->role;
                        @endphp

                        <x-ui.custom-select label="Role" name="role" :value="$currentRoleValue" :options="$roleOptions" />

                        <x-ui.input label="NIM (Opsional)" name="nim" type="text"
                            value="{{ old('nim', $user->nim) }}" class="font-mono" />
                    </div>

                    <x-ui.input label="Email Address" name="email" type="email"
                        value="{{ old('email', $user->email) }}" required />

                    <div class="pt-4 border-t border-slate-50 mt-4">
                        <p class="text-sm font-semibold text-slate-900 mb-4">
                            Reset Password Mahasiswa
                            <span class="text-slate-400 font-normal">(Isi hanya jika ingin membuatkan password
                                baru)</span>
                        </p>

                        <div class="grid grid-cols-2 gap-6">
                            <x-ui.input label="Password Baru" name="password" type="password"
                                placeholder="Buatkan password baru..." />

                            <x-ui.input label="Ulangi Password Baru" name="password_confirmation" type="password"
                                placeholder="Ketik ulang password..." />
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-slate-50 flex justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-polsri-primary hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-layouts.admin>
