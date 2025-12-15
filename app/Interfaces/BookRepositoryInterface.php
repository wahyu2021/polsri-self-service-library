<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getAvailableBooks(): Collection;
    public function findById(int $id): ?Book;
    public function findByIsbn(string $isbn): ?Book;
    public function searchBooks(string $query, int $limit = 5): Collection;
    public function getPopularBooks(int $limit = 5): Collection;
    public function create(array $data): Book;
    public function update(Book $book, array $data): Book;
    public function delete(Book $book): bool;
}
