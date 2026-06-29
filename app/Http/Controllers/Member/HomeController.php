<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Book;
use App\Models\Loan;

class HomeController extends Controller
{
    public function index()
    {
        $token = session('backend_token');
        $response = Http::withToken($token)
            ->get(config('services.backend.url') . '/api/member/home');

        if ($response->successful()) {
            $data = $response->json();
            $activeLoans = ApiHydrator::hydrateCollection(Loan::class, $data['activeLoans'] ?? []);
            $nextDue = ApiHydrator::hydrateSingle(Loan::class, $data['nextDue'] ?? null);
            $history = ApiHydrator::hydrateCollection(Loan::class, $data['history'] ?? []);
            $popularBooks = ApiHydrator::hydrateCollection(Book::class, $data['popularBooks'] ?? []);
            $outstandingFines = (float) ($data['outstandingFines'] ?? 0);
        } else {
            $activeLoans = collect();
            $nextDue = null;
            $history = collect();
            $popularBooks = collect();
            $outstandingFines = 0.0;
        }

        return view('member.home', compact(
            'activeLoans', 'nextDue', 'outstandingFines', 'history', 'popularBooks'
        ));
    }
}
