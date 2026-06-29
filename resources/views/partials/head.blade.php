<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Pustaka — Perpustakaan Digital')</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          burgundy: { DEFAULT: '#800B38', 600: '#800B38', 700: '#6A0A2E', 800: '#541A19' },
          maroon:  '#541A19',
          cream:   { DEFAULT: '#F2E2D3', 50: '#FBF7F0', 100: '#F6ECDF' },
          sand:    { DEFAULT: '#DDC3AA', 300: '#DDC3AA', 200: '#E8D6C0' },
          gold:    '#B0824A',
          ink:     '#2A1316',
        },
        fontFamily: {
          display: ['Fraunces', 'serif'],
          sans: ['Inter', 'ui-sans-serif', 'system-ui'],
        },
        boxShadow: {
          card: '0 1px 2px rgba(84,26,25,.06), 0 8px 24px -12px rgba(84,26,25,.18)',
        },
      },
    },
  };
</script>
<style>
  body { -webkit-font-smoothing: antialiased; }
  .font-display { font-family: 'Fraunces', serif; }
  ::selection { background: #800B38; color: #F2E2D3; }
  [x-cloak] { display: none !important; }
</style>
