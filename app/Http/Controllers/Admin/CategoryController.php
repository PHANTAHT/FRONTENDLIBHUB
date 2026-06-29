<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/categories');

            if ($response->successful()) {
                $categories = ApiHydrator::hydrateCollection(Category::class, $response->json()['categories'] ?? []);
            } else {
                $categories = collect();
            }
        } catch (\Exception $e) {
            $categories = collect();
        }

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->post(config('services.backend.url') . '/api/admin/categories', $request->all());

            if ($response->successful()) {
                return back()->with('status', 'Kategori ditambahkan.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal menambahkan kategori.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses tambah kategori: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->put(config('services.backend.url') . '/api/admin/categories/' . $id, $request->all());

            if ($response->successful()) {
                return back()->with('status', 'Kategori diperbarui.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal memperbarui kategori.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses perbaruan kategori: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->delete(config('services.backend.url') . '/api/admin/categories/' . $id);

            if ($response->successful()) {
                return back()->with('status', 'Kategori dihapus.');
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal menghapus kategori.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses hapus kategori: ' . $e->getMessage());
        }
    }
}
