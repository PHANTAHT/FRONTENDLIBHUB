<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

/**
 * Internal REST API — Endpoint: /api/books
 * Siap dikonsumsi aplikasi mobile di masa depan.
 */
class BookApiController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query()
            ->with('category:id,nama_kategori')
            ->when($request->filled('q'), fn ($q) =>
                $q->where('judul', 'like', "%{$request->q}%")
                  ->orWhere('pengarang', 'like', "%{$request->q}%"))
            ->when($request->filled('category'), fn ($q) =>
                $q->where('category_id', $request->integer('category')))
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'data' => $books->map(fn ($b) => $this->transform($b)),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'total' => $books->total(),
            ],
        ]);
    }

    public function show(Book $book)
    {
        return response()->json(['data' => $this->transform($book->load('category'))]);
    }

    protected function transform(Book $book): array
    {
        return [
            'id' => $book->id,
            'judul' => $book->judul,
            'pengarang' => $book->pengarang,
            'penerbit' => $book->penerbit,
            'tahun_terbit' => $book->tahun_terbit,
            'isbn' => $book->isbn,
            'stok' => $book->stok,
            'stok_tersedia' => $book->availableStock(),
            'kategori' => $book->category?->nama_kategori,
            'sinopsis' => $book->sinopsis,
            'jumlah_halaman' => $book->jumlah_halaman,
            'cover' => $book->coverUrl(),
        ];
    }
}
