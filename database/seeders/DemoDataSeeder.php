<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Setting;
use App\Enums\LoanStatus;
use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings (Koordinat Polsri)
        // Ganti value ini dengan koordinat tempat Anda demo nanti malam jika beda!
        Setting::updateOrCreate(['key' => 'library_lat'], ['value' => '-2.9850']); 
        Setting::updateOrCreate(['key' => 'library_lng'], ['value' => '104.7320']);
        Setting::updateOrCreate(['key' => 'fine_per_day'], ['value' => '1000']);

        // 2. User Mahasiswa Demo
        $student = User::firstOrCreate(
            ['email' => 'mahasiswa@polsri.ac.id'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role' => UserRole::MAHASISWA,
                'nim' => '061940411999',
                'avatar' => null // Akan pakai default
            ]
        );

        // 3. Admin Demo
        User::firstOrCreate(
            ['email' => 'admin@polsri.ac.id'],
            [
                'name' => 'Petugas Perpus',
                'password' => bcrypt('password'),
                'role' => UserRole::ADMIN,
                'nim' => null,
            ]
        );

        // 4. Books
        $books = [
            ['title' => 'Laravel 11 for Beginners', 'author' => 'Taylor Otwell', 'isbn' => '978-602-001'],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '978-602-002'],
            ['title' => 'Algoritma & Pemrograman', 'author' => 'Rinaldi Munir', 'isbn' => '978-602-003'],
            ['title' => 'Sistem Basis Data', 'author' => 'Fathansyah', 'isbn' => '978-602-004'],
            ['title' => 'React JS Modern Web', 'author' => 'Facebook Team', 'isbn' => '978-602-005'],
        ];

        foreach ($books as $b) {
            Book::firstOrCreate(['isbn' => $b['isbn']], [
                'title' => $b['title'],
                'author' => $b['author'],
                'stock' => rand(2, 10),
                'cover' => null // Akan pakai placeholder text
            ]);
        }

        $allBooks = Book::all();

        // 5. Loans (Skenario Demo)
        
        // A. Loan Aktif (Baru pinjam kemarin)
        Loan::create([
            'transaction_code' => 'TRX-' . Str::random(5),
            'user_id' => $student->id,
            'book_id' => $allBooks[0]->id,
            'status' => LoanStatus::BORROWED,
            'borrow_date' => Carbon::yesterday(),
            'due_date' => Carbon::now()->addDays(6),
        ]);

        // B. Loan Due Soon (Besok Jatuh Tempo) -> Memicu Notifikasi
        Loan::create([
            'transaction_code' => 'TRX-' . Str::random(5),
            'user_id' => $student->id,
            'book_id' => $allBooks[1]->id,
            'status' => LoanStatus::BORROWED,
            'borrow_date' => Carbon::now()->subDays(6),
            'due_date' => Carbon::tomorrow(), // H-1
        ]);

        // C. Loan Overdue (Telat 2 Hari) -> Memicu Notifikasi Merah
        Loan::create([
            'transaction_code' => 'TRX-' . Str::random(5),
            'user_id' => $student->id,
            'book_id' => $allBooks[2]->id,
            'status' => LoanStatus::BORROWED,
            'borrow_date' => Carbon::now()->subDays(9),
            'due_date' => Carbon::now()->subDays(2), // Overdue
        ]);

        // D. History (Sudah dikembalikan tepat waktu)
        Loan::create([
            'transaction_code' => 'TRX-' . Str::random(5),
            'user_id' => $student->id,
            'book_id' => $allBooks[3]->id,
            'status' => LoanStatus::RETURNED,
            'borrow_date' => Carbon::now()->subMonth(),
            'due_date' => Carbon::now()->subMonth()->addDays(7),
            'return_date' => Carbon::now()->subMonth()->addDays(5),
            'fine_amount' => 0
        ]);

        // E. History (Ada Denda Belum Lunas) -> Memicu Notifikasi Denda
        Loan::create([
            'transaction_code' => 'TRX-' . Str::random(5),
            'user_id' => $student->id,
            'book_id' => $allBooks[4]->id,
            'status' => LoanStatus::RETURNED,
            'borrow_date' => Carbon::now()->subMonth(),
            'due_date' => Carbon::now()->subMonth()->addDays(7),
            'return_date' => Carbon::now()->subMonth()->addDays(10), // Telat 3 hari
            'fine_amount' => 3000,
            'is_fine_paid' => false // Belum bayar
        ]);
    }
}