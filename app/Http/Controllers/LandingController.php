<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Book;

class LandingController extends Controller
{
    public function index()
    {
        $response = Http::get(config('services.backend.url') . '/api/landing');

        if ($response->successful()) {
            $data = $response->json();
            $popularBooks = ApiHydrator::hydrateCollection(Book::class, $data['popularBooks'] ?? []);
            $stats = $data['stats'] ?? [
                'buku' => 0,
                'judul' => 0,
                'kategori' => 0,
            ];
        } else {
            $popularBooks = collect();
            $stats = [
                'buku' => 0,
                'judul' => 0,
                'kategori' => 0,
            ];
        }

        return view('landing', compact('popularBooks', 'stats'));
    }
}
