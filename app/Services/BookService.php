<?php

namespace App\Services;

use App\Interfaces\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class BookService
{
    protected BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getExternalBooksInspiration()
    {
        return \Illuminate\Support\Facades\Cache::remember('external_books_inspiration', 720, function () {
            try {
                // Mengambil buku bertema teknologi/komputer terbaru
                $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                    'q' => 'subject:computers',
                    'orderBy' => 'newest',
                    'maxResults' => 4,
                    'langRestrict' => 'id' // Preferensi bahasa (opsional)
                ]);

                if ($response->successful()) {
                    return $response->json()['items'] ?? [];
                }
            } catch (\Exception $e) {
                // Fail silently agar tidak merusak dashboard jika internet mati
                return [];
            }
            
            return [];
        });
    }

    public function getAllBooks(array $filters)
    {
        return $this->bookRepository->getAllPaginated($filters);
    }

    public function getAvailableBooks()
    {
        return $this->bookRepository->getAvailableBooks();
    }

    public function findBookByIsbn(string $isbn)
    {
        return $this->bookRepository->findByIsbn($isbn);
    }

    public function searchBooks(string $query)
    {
        return $this->bookRepository->searchBooks($query);
    }

    public function createBook(array $data)
    {
        if (isset($data['cover_image']) && $data['cover_image']) {
            $data['cover_image'] = $data['cover_image']->store('covers', 'public');
        }

        return $this->bookRepository->create($data);
    }

    public function updateBook(Book $book, array $data)
    {
        if (isset($data['cover_image']) && $data['cover_image']) {
            // Hapus cover lama
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $data['cover_image']->store('covers', 'public');
        }

        return $this->bookRepository->update($book, $data);
    }

    public function deleteBook(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        return $this->bookRepository->delete($book);
    }
}
