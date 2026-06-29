<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

/**
 * Internal REST API — Endpoint: /api/loans
 * Mengembalikan data peminjaman milik user yang terautentikasi (Sanctum).
 */
class LoanApiController extends Controller
{
    public function index(Request $request)
    {
        $loans = $request->user()->loans()
            ->with(['items.book:id,judul,pengarang', 'fine'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'data' => $loans->map(fn ($loan) => $this->transform($loan)),
            'meta' => [
                'current_page' => $loans->currentPage(),
                'last_page' => $loans->lastPage(),
                'total' => $loans->total(),
            ],
        ]);
    }

    public function show(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === $request->user()->id, 403);
        return response()->json(['data' => $this->transform($loan->load('items.book', 'fine'))]);
    }

    protected function transform(Loan $loan): array
    {
        return [
            'id' => $loan->id,
            'status' => $loan->status,
            'tanggal_pinjam' => $loan->tanggal_pinjam?->toDateString(),
            'tanggal_tenggat' => $loan->tanggal_tenggat?->toDateString(),
            'tanggal_kembali' => $loan->tanggal_kembali?->toDateString(),
            'denda' => (float) $loan->denda,
            'buku' => $loan->items->map(fn ($item) => [
                'judul' => $item->book?->judul,
                'pengarang' => $item->book?->pengarang,
                'jumlah' => $item->jumlah,
            ]),
            'fine' => $loan->fine ? [
                'hari_terlambat' => $loan->fine->hari_terlambat,
                'total_denda' => (float) $loan->fine->total_denda,
                'status_bayar' => $loan->fine->status_bayar,
            ] : null,
        ];
    }
}
