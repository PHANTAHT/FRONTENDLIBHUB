@extends('layouts.admin')
@section('title', 'Anggota — Admin')
@section('page-title', 'Data Anggota')
@section('page-sub', 'Kelola akun anggota & admin')
@section('page-actions')
  <a href="{{ route('admin.users.create') }}" class="flex items-center gap-1.5 rounded-full bg-burgundy px-4 py-2 text-sm font-semibold text-cream hover:bg-burgundy-700">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Tambah Anggota
  </a>
@endsection

@section('content')
<form method="GET" class="mb-5 flex items-center gap-2 rounded-xl border border-sand bg-white px-3 shadow-card focus-within:border-burgundy sm:max-w-md">
  <svg class="h-5 w-5 text-maroon/40" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
  <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email…" class="w-full bg-transparent py-2.5 text-sm outline-none placeholder:text-ink/30">
  <button class="text-sm font-medium text-burgundy">Cari</button>
</form>

<div class="rounded-2xl border border-sand/50 bg-white shadow-card">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream-50 text-left text-xs uppercase tracking-wide text-ink/45">
        <tr>
          <th class="px-5 py-3 font-semibold">Nama</th>
          <th class="px-5 py-3 font-semibold">Email</th>
          <th class="px-5 py-3 font-semibold">Telepon</th>
          <th class="px-5 py-3 font-semibold">Role</th>
          <th class="px-5 py-3 font-semibold">Status</th>
          <th class="px-5 py-3 text-right font-semibold">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sand/30">
        @forelse($users as $user)
          <tr class="hover:bg-cream-50/60">
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sand text-xs font-semibold text-maroon">{{ $user->initials() }}</span>
                <span class="font-medium text-ink">{{ $user->nama_lengkap }}</span>
              </div>
            </td>
            <td class="px-5 py-3 text-ink/60">{{ $user->email }}</td>
            <td class="px-5 py-3 text-ink/60">{{ $user->no_telp ?: '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role==='admin' ? 'bg-burgundy/10 text-burgundy' : 'bg-cream-100 text-maroon' }}">{{ ucfirst($user->role) }}</span>
            </td>
            <td class="px-5 py-3">
              <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->status_keanggotaan==='aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($user->status_keanggotaan) }}</span>
            </td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="rounded-full border border-maroon/15 px-3 py-1.5 text-xs font-semibold text-maroon hover:bg-cream-100">Edit</a>
                @if($user->id !== auth()->id())
                  <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus anggota ini?')">
                    @csrf @method('DELETE')
                    <button class="rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-5 py-12 text-center text-ink/45">Tidak ada anggota.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
    <div class="border-t border-sand/40 px-5 py-3">{{ $users->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
