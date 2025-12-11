<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->unique();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable(); // Added
            $table->year('publication_year')->nullable(); // Added
            
            // Relation to Categories
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            
            $table->integer('stock')->default(0);
            $table->text('synopsis')->nullable(); // Added
            $table->string('cover_image')->nullable(); // Renamed from 'cover'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
