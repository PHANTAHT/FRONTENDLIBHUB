<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Integrasi dengan Google Books API (External API).
 * Mengambil metadata buku (judul, pengarang, sinopsis, cover, jumlah halaman)
 * berdasarkan ISBN agar input data oleh Admin lebih cepat.
 *
 * Tidak perlu API key untuk volume request standar.
 */
class GoogleBooksService
{
    protected string $endpoint = 'https://www.googleapis.com/books/v1/volumes';

    public function findByIsbn(string $isbn): ?array
    {
        $isbn = preg_replace('/[^0-9Xx]/', '', $isbn);
        return $this->firstResult('isbn:' . $isbn);
    }

   public function search(string $query, int $maxResults = 12, int $startIndex = 0): array
    {
        $params = array_filter([
            'q' => $query,
            'maxResults' => min($maxResults, 40),
            'startIndex' => $startIndex,
            'printType' => 'books',
            'key' => config('services.google_books.key'),
        ], fn ($v) => $v !== null && $v !== '');

        $response = Http::timeout(15)->get($this->endpoint, $params);

        if (! $response->ok()) return [];

        return collect($response->json('items', []))
            ->map(fn ($item) => $this->normalize($item))
            ->filter()
            ->values()
            ->all();
    }

    protected function firstResult(string $query): ?array
    {
        $response = Http::timeout(10)->get($this->endpoint, [
            'q' => $query,
            'maxResults' => 1,
        ]);

        if (! $response->ok()) return null;

        $item = $response->json('items.0');
        return $item ? $this->normalize($item) : null;
    }

    protected function normalize(array $item): ?array
    {
        $info = $item['volumeInfo'] ?? [];
        if (empty($info['title'])) return null;

        $isbn = null;
        foreach ($info['industryIdentifiers'] ?? [] as $id) {
            if (in_array($id['type'] ?? '', ['ISBN_13', 'ISBN_10'])) {
                $isbn = $id['identifier'];
                break;
            }
        }

        $cover = $info['imageLinks']['thumbnail']
            ?? $info['imageLinks']['smallThumbnail']
            ?? null;
        if ($cover) $cover = str_replace('http://', 'https://', $cover);

        return [
            'judul'          => $info['title'] ?? null,
            'pengarang'      => isset($info['authors']) ? implode(', ', $info['authors']) : null,
            'penerbit'       => $info['publisher'] ?? null,
            'tahun_terbit'   => isset($info['publishedDate']) ? (int) substr($info['publishedDate'], 0, 4) : null,
            'isbn'           => $isbn,
            'sinopsis'       => $info['description'] ?? null,
            'jumlah_halaman' => $info['pageCount'] ?? null,
            'foto_sampul'    => $cover,
        ];
    }
}
