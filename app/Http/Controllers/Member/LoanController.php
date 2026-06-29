<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Loan;

class LoanController extends Controller
{
    public function index()
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/member/loans');

            if ($response->successful()) {
                $data = $response->json();
                $loans = ApiHydrator::hydrateCollection(Loan::class, $data['loans'] ?? []);
            } else {
                $loans = collect();
            }
        } catch (\Exception $e) {
            $loans = collect();
        }

        return view('member.loans', compact('loans'));
    }
}
