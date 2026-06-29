@extends('layouts.admin')
@section('title', ($user->exists ? 'Edit' : 'Tambah') . ' Anggota — Admin')
@section('page-title', $user->exists ? 'Edit Anggota' : 'Tambah Anggota')
@section('page-actions')
  <a href="{{ route('admin.users.index') }}" class="rounded-full bg-cream-100 px-4 py-2 text-sm font-medium text-maroon hover:bg-sand/40">← Kembali</a>
@endsection

@section('content')
<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
      class="mx-auto max-w-2xl">
  @csrf
  @if($user->exists) @method('PUT') @endif

  <div class="space-y-4 rounded-2xl border border-sand/50 bg-white p-6 shadow-card">
    <div>
      <label class="mb-1.5 block text-sm font-medium text-maroon">Nama Lengkap <span class="text-red-500">*</span></label>
      <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required
             class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
      @error('nama_lengkap')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">No. Telepon</label>
        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}"
               class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
      </div>
    </div>

    <div>
      <label class="mb-1.5 block text-sm font-medium text-maroon">Alamat</label>
      <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}"
             class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Role <span class="text-red-500">*</span></label>
        <select name="role" class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
          <option value="anggota" @selected(old('role', $user->role)==='anggota')>Anggota</option>
          <option value="admin" @selected(old('role', $user->role)==='admin')>Admin</option>
        </select>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Status Keanggotaan <span class="text-red-500">*</span></label>
        <select name="status_keanggotaan" class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
          <option value="aktif" @selected(old('status_keanggotaan', $user->status_keanggotaan ?? 'aktif')==='aktif')>Aktif</option>
          <option value="nonaktif" @selected(old('status_keanggotaan', $user->status_keanggotaan)==='nonaktif')>Nonaktif</option>
        </select>
      </div>
    </div>

    @if(!$user->exists)
    <div>
      <label class="mb-1.5 block text-sm font-medium text-maroon">
        Password <span class="text-red-500">*</span>
      </label>
      <input type="password" name="password" required
             class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
      @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    @endif

    <div class="pt-2">
      <button class="rounded-xl bg-burgundy px-6 py-2.5 text-sm font-semibold text-cream hover:bg-burgundy-700">
        {{ $user->exists ? 'Simpan Perubahan' : 'Simpan Anggota' }}
      </button>
    </div>
  </div>
</form>
@endsection
