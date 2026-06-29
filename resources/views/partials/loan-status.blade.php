@php
  $map = [
    'dipinjam'  => ['On loan', 'bg-amber-100 text-amber-700'],
    'kembali'   => ['Dikembalikan', 'bg-green-100 text-green-700'],
    'terlambat' => ['Terlambat', 'bg-red-100 text-red-700'],
  ];
  [$label, $cls] = $map[$status] ?? [$status, 'bg-gray-100 text-gray-600'];
@endphp
<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $cls }}">{{ $label }}</span>
