<?php

namespace App\Http\Controllers\Admin;

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

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/books', $request->query());

            if ($response->successful()) {
                $books = ApiHydrator::hydratePaginated(Book::class, $response->json()['books'] ?? []);
            } else {
                $books = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            }
        } catch (\Exception $e) {
            $books = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
        }

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $token = session('backend_token');
        
        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/categories');
            
            $categories = $response->successful()
                ? ApiHydrator::hydrateCollection(Category::class, $response->json()['categories'] ?? [])
                : collect();
        } catch (\Exception $e) {
            $categories = collect();
        }

        return view('admin.books.form', [
            'book' => new Book(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $token = session('backend_token');

        try {
            $http = Http::withToken($token);
            
            if ($request->hasFile('cover_file')) {
                $file = $request->file('cover_file');
                $http = $http->attach(
                    'cover_file', 
                    fopen($file->getRealPath(), 'r'), 
                    $file->getClientOriginalName()
                );
            }

            $response = $http->post(
                config('services.backend.url') . '/api/admin/books', 
                $request->except(['cover_file'])
            );

            if ($response->successful()) {
                return redirect()->route('admin.books.index')->with('status', 'Buku ditambahkan.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal menambahkan buku.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses tambah buku: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $token = session('backend_token');

        try {
            $bookRes = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/member/books/' . $id);
                
            $catRes = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/categories');

            if ($bookRes->successful()) {
                $book = ApiHydrator::hydrateSingle(Book::class, $bookRes->json());
                $categories = ApiHydrator::hydrateCollection(Category::class, $catRes->json()['categories'] ?? []);
                
                return view('admin.books.form', compact('book', 'categories'));
            }
        } catch (\Exception $e) {
            // Fail
        }

        return redirect()->route('admin.books.index')->with('error', 'Buku tidak ditemukan.');
    }

    public function update(Request $request, $id)
    {
        $token = session('backend_token');

        try {
            $http = Http::withToken($token);
            
            if ($request->hasFile('cover_file')) {
                $file = $request->file('cover_file');
                $http = $http->attach(
                    'cover_file', 
                    fopen($file->getRealPath(), 'r'), 
                    $file->getClientOriginalName()
                );
            }

            $fields = $request->except(['cover_file']);
            $fields['_method'] = 'PUT'; // Emulate PUT through POST for file upload compatibility

            $response = $http->post(
                config('services.backend.url') . '/api/admin/books/' . $id, 
                $fields
            );

            if ($response->successful()) {
                return redirect()->route('admin.books.index')->with('status', 'Buku diperbarui.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal memperbarui buku.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses perbaruan buku: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->delete(config('services.backend.url') . '/api/admin/books/' . $id);

            if ($response->successful()) {
                return back()->with('status', 'Buku dihapus.');
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal menghapus buku.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    public function lookupIsbn(Request $request)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/books/lookup', $request->query());

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['found' => false], 500);
        }
    }
}
