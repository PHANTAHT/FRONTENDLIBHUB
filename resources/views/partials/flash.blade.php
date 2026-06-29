@if (session('status'))
  <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
    {{ session('status') }}
  </div>
@endif
@if (session('error'))
  <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
    {{ session('error') }}
  </div>
@endif
@if (session('info'))
  <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
    {{ session('info') }}
  </div>
@endif
