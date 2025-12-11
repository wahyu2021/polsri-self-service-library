<?php

namespace App\Repositories;

use App\Interfaces\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository implements BookRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Book::with('category'); // Eager load category

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAvailableBooks(): Collection
    {
        return Book::where('stock', '>', 0)->orderBy('title')->get();
    }

    public function findById(int $id): ?Book
    {
        return Book::find($id);
    }

    public function findByIsbn(string $isbn): ?Book
    {
        return Book::where('isbn', $isbn)->first();
    }

    public function searchBooks(string $query, int $limit = 5): Collection
    {
        return Book::where('title', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    public function create(array $data): Book
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book): bool
    {
        return $book->delete();
    }
}
