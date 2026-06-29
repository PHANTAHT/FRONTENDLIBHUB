<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Services\GoogleBooksService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------- Akun Admin ----------------
        User::updateOrCreate(
            ['email' => 'admin@pustaka.test'],
            [
                'nama_lengkap' => 'Admin Pustaka',
                'no_telp' => '081200000000',
                'alamat' => 'Perpustakaan Pusat',
                'role' => 'admin',
                'status_keanggotaan' => 'aktif',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // ---------------- Akun Anggota contoh ----------------
        foreach ([
            ['Sean Vandana Sanjaya', 'sean@pustaka.test'],
            ['Robert Leonardo Sanjaya', 'robert@pustaka.test'],
            ['Nelson Susanto', 'nelson@pustaka.test'],
        ] as [$nama, $email]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'nama_lengkap' => $nama,
                    'alamat' => 'Surabaya',
                    'role' => 'anggota',
                    'status_keanggotaan' => 'aktif',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // ---------------- Kategori + Buku dari Google Books API ----------------
        $service = app(GoogleBooksService::class);

        $map = [
            'Fiksi'     => 'subject:fiction',
            'Sains'     => 'subject:science',
            'Teknologi' => 'subject:computers',
            'Sejarah'   => 'subject:history',
            'Bisnis'    => 'subject:business',
            'Psikologi' => 'subject:psychology',
            'Filsafat'  => 'subject:philosophy',
            'Anak'      => 'subject:juvenile',
        ];

        $perKategori = 120;  // jumlah buku per kategori (ubah sesuai selera)
        $pageSize    = 40;   // maksimal per request Google
        $seen = [];
        $totalBuku = 0;

        foreach ($map as $namaKategori => $query) {
            $kategori = Category::updateOrCreate(
                ['nama_kategori' => $namaKategori],
                ['deskripsi' => "Koleksi buku bertema {$namaKategori}"]
            );

            $this->command->info("Mengambil buku '{$namaKategori}'...");

            for ($start = 0; $start < $perKategori; $start += $pageSize) {
                $hasil = $service->search($query, $pageSize, $start);
                if (empty($hasil)) break;

                foreach ($hasil as $data) {
                    if (empty($data['judul'])) continue;

                    $key = $data['isbn'] ?: strtolower($data['judul'] . '|' . ($data['pengarang'] ?? ''));
                    if (isset($seen[$key])) continue;
                    $seen[$key] = true;

                    Book::create([
                        'judul'          => $data['judul'],
                        'pengarang'      => $data['pengarang'] ?? 'Tidak diketahui',
                        'penerbit'       => $data['penerbit'] ?? null,
                        'tahun_terbit'   => $data['tahun_terbit'] ?? null,
                        'isbn'           => $data['isbn'] ?? null,
                        'sinopsis'       => $data['sinopsis'] ?? null,
                        'jumlah_halaman' => $data['jumlah_halaman'] ?? null,
                        'foto_sampul'    => $data['foto_sampul'] ?? null,
                        'stok'           => rand(2, 6),
                        'category_id'    => $kategori->id,
                    ]);
                    $totalBuku++;
                }

                usleep(300000); // jeda 0,3 detik biar tidak kena throttle
            }
        }

        // ---------------- Fallback kalau API tidak bisa diakses ----------------
        if ($totalBuku === 0) {
            $this->command->warn('Google Books tidak bisa diakses. Memakai data contoh.');
            $fiksi = Category::firstOrCreate(['nama_kategori' => 'Fiksi']);
            foreach ([
                ['Laskar Pelangi', 'Andrea Hirata', '9789793062792'],
                ['Bumi Manusia', 'Pramoedya Ananta Toer', '9789799731234'],
                ['Atomic Habits', 'James Clear', '9780735211292'],
            ] as [$judul, $pengarang, $isbn]) {
                Book::create([
                    'judul' => $judul, 'pengarang' => $pengarang, 'isbn' => $isbn,
                    'stok' => rand(2, 6), 'category_id' => $fiksi->id,
                    'foto_sampul' => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg",
                ]);
            }
            $totalBuku = 3;
        }

        $this->command->info("Selesai! Total {$totalBuku} buku.");
    }
}