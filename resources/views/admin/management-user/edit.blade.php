<x-layouts.admin title="Edit User">

    <div class="max-w-2xl mx-auto">
        <x-ui.header 
            title="Edit Data User" 
            subtitle="Perbarui informasi akun pengguna."
            :breadcrumbs="[
                ['label' => 'User', 'url' => route('admin.users.index')],
                ['label' => 'Edit User']
            ]"
        >
            <x-ui.link-button :href="route('admin.users.index')" color="gray" icon="arrow-left">
                Kembali
            </x-ui.link-button>
        </x-ui.header>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8" x-data="{ isLoading: false }" @submit="isLoading = true">
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
                    <button type="submit" :disabled="isLoading"
                        class="px-8 py-3 bg-polsri-primary hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none flex items-center gap-2">
                        <span x-show="!isLoading">Simpan Perubahan</span>
                        <span x-show="isLoading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-layouts.admin>
