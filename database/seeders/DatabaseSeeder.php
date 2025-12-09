<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'nim' => '062340833133',
            'role' => 'mahasiswa'
        ]);
    }
}
