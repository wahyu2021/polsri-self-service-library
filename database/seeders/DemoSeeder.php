<?php

namespace Database\Seeders;

use App\Enums\LoanStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Logbook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Starting Demo Data Seeding...');

        // 1. Create Students
        $students = [];
        $names = [
            'Ahmad Rizki', 'Siti Aminah', 'Budi Santoso', 'Dewi Lestari', 'Eko Prasetyo',
            'Fajar Nugraha', 'Gita Pertiwi', 'Hendra Wijaya', 'Indah Sari', 'Joko Susilo',
            'Kartika Putri', 'Lukman Hakim', 'Maya Anggraini', 'Nanda Pratama', 'Oki Setiawan'
        ];

        foreach ($names as $index => $name) {
            $nim = '06203070' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $students[] = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@student.polsri.ac.id',
                'nim' => $nim,
                'password' => Hash::make('password'),
                'role' => UserRole::MAHASISWA,
                'email_verified_at' => now(),
            ]);
        }
        $this->command->info('✅ Created 15 Students');

        // 2. Create Categories
        $categories = [
            'Teknologi Informasi', 'Ekonomi & Bisnis', 'Teknik Mesin', 
            'Teknik Elektro', 'Bahasa & Sastra', 'Sains Dasar', 'Umum'
        ];
        
        $categoryIds = [];
        foreach ($categories as $catName) {
            $cat = \App\Models\Category::create(['name' => $catName, 'slug' => Str::slug($catName)]);
            $categoryIds[] = $cat->id;
        }
        $this->command->info('✅ Created 7 Categories');

        // 3. Create Books
        $bookTitles = [
            'Pemrograman Web Modern dengan Laravel', 'Algoritma dan Struktur Data', 'Dasar-Dasar Jaringan Komputer',
            'Sistem Operasi: Konsep dan Implementasi', 'Basis Data Relasional', 'Kecerdasan Buatan untuk Pemula',
            'Desain UI/UX Mobile Apps', 'Manajemen Proyek Perangkat Lunak', 'Keamanan Siber di Era Digital',
            'Internet of Things (IoT) Basic', 'Matematika Diskrit', 'Statistika Probabilitas',
            'Bahasa Inggris untuk IT', 'Etika Profesi Komputer', 'Cloud Computing AWS'
        ];

        $books = [];
        foreach ($bookTitles as $index => $title) {
            $books[] = Book::create([
                'title' => $title,
                'author' => 'Pakar IT ' . chr(65 + $index),
                // 'publisher' => 'Polsri Press', // Removed as column doesn't exist
                'isbn' => '978-602-' . rand(1000, 9999) . '-' . rand(10, 99),
                'publication_year' => rand(2018, 2024),
                'category_id' => $categoryIds[rand(0, count($categoryIds) - 1)],
                'stock' => rand(3, 10),
                'synopsis' => 'Buku panduan lengkap tentang ' . $title . ' untuk mahasiswa vokasi.',
                'cover_image' => null, // Placeholder handled by frontend
            ]);
        }
        $this->command->info('✅ Created 15 Books');

        // 4. Create Loans (Mix of Active, Returned, Overdue)
        $loanCount = 0;
        foreach ($students as $student) {
            // Each student has 1-3 loans
            $numLoans = rand(1, 3);
            
            for ($i = 0; $i < $numLoans; $i++) {
                $book = $books[rand(0, count($books) - 1)];
                $statusRoll = rand(1, 10);
                
                $loanData = [
                    'user_id' => $student->id,
                    'book_id' => $book->id,
                    'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                    'borrow_date' => Carbon::now()->subDays(rand(1, 30)),
                ];

                // 20% Active, 20% Overdue, 60% Returned
                if ($statusRoll <= 2) {
                    // Active (Borrowed)
                    $loanData['due_date'] = Carbon::parse($loanData['borrow_date'])->addDays(7);
                    $loanData['status'] = LoanStatus::BORROWED;
                } elseif ($statusRoll <= 4) {
                    // Overdue (Borrowed but Late)
                    $loanData['borrow_date'] = Carbon::now()->subDays(10);
                    $loanData['due_date'] = Carbon::now()->subDays(3);
                    $loanData['status'] = LoanStatus::BORROWED;
                } else {
                    // Returned
                    $loanData['due_date'] = Carbon::parse($loanData['borrow_date'])->addDays(7);
                    $returnDate = Carbon::parse($loanData['borrow_date'])->addDays(rand(3, 10));
                    $loanData['return_date'] = $returnDate;
                    $loanData['status'] = LoanStatus::RETURNED;

                    // Calculate Fine if returned late
                    if ($returnDate->gt($loanData['due_date'])) {
                        $daysLate = $returnDate->diffInDays($loanData['due_date']);
                        $loanData['fine_amount'] = $daysLate * 1000;
                        $loanData['is_fine_paid'] = (rand(1, 10) > 3); // 70% paid
                    } else {
                        $loanData['fine_amount'] = 0;
                        $loanData['is_fine_paid'] = true;
                    }
                }

                Loan::create($loanData);
                $loanCount++;
            }
        }
        $this->command->info("✅ Created $loanCount Transactions");

        // 4. Create Logbook (Visits)
        $visitCount = 0;
        for ($i = 0; $i < 50; $i++) {
            Logbook::create([
                'user_id' => $students[rand(0, count($students) - 1)]->id,
                'check_in_time' => Carbon::now()->subDays(rand(0, 14))->subHours(rand(1, 8)),
                'latitude' => -2.9850,
                'longitude' => 104.7320,
            ]);
            $visitCount++;
        }
        $this->command->info("✅ Created $visitCount Logbook Entries");
    }
}
