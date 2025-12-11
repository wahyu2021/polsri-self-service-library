<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;

class BookController extends Controller
{
    protected BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        if ($request->has('q')) {
            $books = $this->bookService->searchBooks($request->get('q'));
            
            $formatted = $books->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'isbn' => $book->isbn . ' - ' . $book->author,
                    'search_term' => $book->title,
                ];
            });

            return response()->json(['success' => true, 'data' => $formatted]);
        }

        $books = $this->bookService->getAllBooks($request->all());

        if ($request->ajax()) {
            return view('admin.management-book._table', compact('books'));
        }

        return view('admin.management-book.index', compact('books'));
    }

    public function create()
    {
        return view('admin.management-book.create');
    }

    public function store(StoreBookRequest $request)
    {
        $this->bookService->createBook($request->validated());

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('admin.management-book.edit', compact('book'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->bookService->updateBook($book, $request->validated());

        return redirect()->route('admin.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus dari sistem.');
    }
}
