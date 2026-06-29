<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $token = session('backend_token');
        
        $response = Http::withToken($token)
            ->get(config('services.backend.url') . '/api/member/books', $request->query());

        if ($response->successful()) {
            $data = $response->json();
            $books = ApiHydrator::hydratePaginated(Book::class, $data['books'] ?? []);
            $categories = ApiHydrator::hydrateCollection(Category::class, $data['categories'] ?? []);
        } else {
            $books = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            $categories = collect();
        }

        return view('member.books', compact('books', 'categories'));
    }

    public function show($id)
    {
        $token = session('backend_token');
        
        $response = Http::withToken($token)
            ->get(config('services.backend.url') . '/api/member/books/' . $id);

        if ($response->successful()) {
            $book = ApiHydrator::hydrateSingle(Book::class, $response->json());
        } else {
            abort(404, 'Buku tidak ditemukan.');
        }

        return view('member.book-detail', compact('book'));
    }
}
