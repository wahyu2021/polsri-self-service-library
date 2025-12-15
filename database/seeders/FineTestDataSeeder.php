<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use App\Enums\LoanStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FineTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa user, book, dan buat data loan dengan denda
        $users = User::where('role', 'mahasiswa')->limit(5)->get();
        $books = Book::limit(10)->get();

        if ($users->isEmpty() || $books->isEmpty()) {
            $this->command->error('Tidak ada user mahasiswa atau book di database. Jalankan seeder lain dulu.');
            return;
        }

        $scenarios = [
            // Denda belum lunas
            [
                'fine_amount' => 10000,
                'is_fine_paid' => 'false',
                'days_late' => 3,
                'description' => 'Terlambat 3 hari - Belum Lunas'
            ],
            [
                'fine_amount' => 25000,
                'is_fine_paid' => 'false',
                'days_late' => 5,
                'description' => 'Terlambat 5 hari - Belum Lunas'
            ],
            [
                'fine_amount' => 50000,
                'is_fine_paid' => 'false',
                'days_late' => 10,
                'description' => 'Terlambat 10 hari - Belum Lunas'
            ],
            // Denda sudah lunas
            [
                'fine_amount' => 15000,
                'is_fine_paid' => 'true',
                'days_late' => 3,
                'description' => 'Terlambat 3 hari - Sudah Lunas'
            ],
            [
                'fine_amount' => 35000,
                'is_fine_paid' => 'true',
                'days_late' => 7,
                'description' => 'Terlambat 7 hari - Sudah Lunas'
            ],
            // Tanpa denda
            [
                'fine_amount' => 0,
                'is_fine_paid' => 'true',
                'days_late' => 0,
                'description' => 'Tepat waktu - Tanpa Denda'
            ],
        ];

        $now = Carbon::now();
        $createdCount = 0;

        foreach ($users as $user) {
            foreach ($books as $book) {
                $scenario = $scenarios[array_rand($scenarios)];

                $borrowDate = $now->copy()->subDays(30);
                $dueDate = $borrowDate->copy()->addDays(7);
                $returnDate = $dueDate->copy()->addDays($scenario['days_late']);

                DB::table('loans')->insert([
                    'transaction_code' => 'TEST-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'status' => LoanStatus::RETURNED->value,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'return_date' => $returnDate,
                    'fine_amount' => $scenario['fine_amount'],
                    'is_fine_paid' => DB::raw($scenario['is_fine_paid'].'::boolean'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $createdCount++;
            }
        }

        $this->command->info("✅ Berhasil membuat {$createdCount} data loan dengan denda untuk testing!");
        $this->command->line('');
        $this->command->info('📊 Distribusi data:');
        $this->command->line('  - Denda belum lunas: ' . Loan::where('status', LoanStatus::RETURNED)->where('fine_amount', '>', 0)->whereRaw('"is_fine_paid" = false')->count());
        $this->command->line('  - Denda sudah lunas: ' . Loan::where('status', LoanStatus::RETURNED)->where('fine_amount', '>', 0)->whereRaw('"is_fine_paid" = true')->count());
        $this->command->line('  - Tanpa denda: ' . Loan::where('status', LoanStatus::RETURNED)->where('fine_amount', 0)->count());
    }
}

