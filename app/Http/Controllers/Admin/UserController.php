<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/users', $request->query());

            if ($response->successful()) {
                $users = ApiHydrator::hydratePaginated(User::class, $response->json()['users'] ?? []);
            } else {
                $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            }
        } catch (\Exception $e) {
            $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->post(config('services.backend.url') . '/api/admin/users', $request->all());

            if ($response->successful()) {
                return redirect()->route('admin.users.index')->with('status', 'Anggota ditambahkan.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal menambahkan anggota.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses tambah anggota: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/users/' . $id);

            if ($response->successful()) {
                $user = ApiHydrator::hydrateSingle(User::class, $response->json());
                return view('admin.users.form', compact('user'));
            }
        } catch (\Exception $e) {
            // Fail
        }

        return redirect()->route('admin.users.index')->with('error', 'Anggota tidak ditemukan.');
    }

    public function update(Request $request, $id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->put(config('services.backend.url') . '/api/admin/users/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('admin.users.index')->with('status', 'Data anggota diperbarui.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal memperbarui data anggota.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses perbaruan data anggota: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->delete(config('services.backend.url') . '/api/admin/users/' . $id);

            if ($response->successful()) {
                return back()->with('status', 'Anggota dihapus.');
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal menghapus anggota.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses hapus anggota: ' . $e->getMessage());
        }
    }
}
