<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean
            $table->string('label')->nullable(); // Nama yang muncul di UI
            $table->timestamps();
        });

        // Insert Default Settings
        DB::table('settings')->insert([
            [
                'key' => 'library_lat',
                'value' => '-2.986383', // Contoh Koordinat Polsri
                'type' => 'text',
                'label' => 'Latitude Lokasi Perpustakaan',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'library_lng',
                'value' => '104.730248', // Contoh Koordinat Polsri
                'type' => 'text',
                'label' => 'Longitude Lokasi Perpustakaan',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'validation_radius',
                'value' => '50', // meter
                'type' => 'number',
                'label' => 'Radius Validasi Absensi (Meter)',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'fine_per_day',
                'value' => '1000', // Rupiah
                'type' => 'number',
                'label' => 'Denda Keterlambatan (Rp/Hari)',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};