<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS') - LMS Perpustakaan</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Manajemen Perpustakaan Daerah')">

    {{-- Google Fonts: Public Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary':                '#00236f',
                        'primary-container':      '#1e3a8a',
                        'primary-fixed':          '#dce1ff',
                        'primary-fixed-dim':      '#b6c4ff',
                        'on-primary':             '#ffffff',
                        'on-primary-container':   '#90a8ff',
                        'on-primary-fixed':       '#00164e',
                        'inverse-primary':        '#b6c4ff',
                        'secondary':              '#9b4500',
                        'secondary-container':    '#fd8a42',
                        'secondary-fixed':        '#ffdbca',
                        'secondary-fixed-dim':    '#ffb68e',
                        'on-secondary':           '#ffffff',
                        'on-secondary-container': '#682c00',
                        'on-secondary-fixed':     '#331200',
                        'tertiary':               '#1b2b3f',
                        'tertiary-container':     '#314156',
                        'tertiary-fixed':         '#d3e4fe',
                        'tertiary-fixed-dim':     '#b7c8e1',
                        'on-tertiary':            '#ffffff',
                        'on-tertiary-container':  '#9dadc6',
                        'on-tertiary-fixed':      '#0b1c30',
                        'on-tertiary-fixed-variant': '#38485d',
                        'surface':                '#f6fafe',
                        'surface-dim':            '#d6dade',
                        'surface-bright':         '#f6fafe',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low':  '#f0f4f8',
                        'surface-container':      '#eaeef2',
                        'surface-container-high': '#e4e9ed',
                        'surface-container-highest': '#dfe3e7',
                        'surface-variant':        '#dfe3e7',
                        'on-surface':             '#171c1f',
                        'on-surface-variant':     '#444651',
                        'background':             '#f6fafe',
                        'on-background':          '#171c1f',
                        'outline':                '#757682',
                        'outline-variant':        '#c5c5d3',
                        'error':                  '#ba1a1a',
                        'error-container':        '#ffdad6',
                        'on-error':               '#ffffff',
                        'on-error-container':     '#93000a',
                        'inverse-surface':        '#2c3134',
                        'inverse-on-surface':     '#edf1f5',
                        'surface-tint':           '#4059aa',
                    },
                    fontFamily: {
                        'sans': ['Public Sans', 'sans-serif'],
                    },
                    fontSize: {
                        'caption':    ['12px', { lineHeight: '16px', fontWeight: '400' }],
                        'label-md':   ['14px', { lineHeight: '20px', letterSpacing: '0.01em', fontWeight: '600' }],
                        'body-md':    ['16px', { lineHeight: '24px', fontWeight: '400' }],
                        'body-lg':    ['18px', { lineHeight: '28px', fontWeight: '400' }],
                        'title-lg':   ['20px', { lineHeight: '28px', fontWeight: '600' }],
                        'headline-md': ['24px', { lineHeight: '32px', fontWeight: '600' }],
                        'headline-lg': ['32px', { lineHeight: '40px', fontWeight: '700' }],
                        'display-lg': ['48px', { lineHeight: '56px', letterSpacing: '-0.02em', fontWeight: '700' }],
                    },
                    spacing: {
                        'gutter': '24px',
                        'container-max': '1280px',
                    }
                }
            }
        }
    </script>

    <style>
        /* ── Base ─────────────────────────────────────────────── */
        body { font-family: 'Public Sans', sans-serif; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            /* Penting: gunakan block agar tidak mengganggu flex alignment */
            display: block;
            line-height: 1;
            flex-shrink: 0;
        }
        .icon-filled { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        /* ── Scrollbar ────────────────────────────────────────── */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c5c5d3; border-radius: 10px; }

        /* ── Sidebar Navigation ───────────────────────────────── */
        /*
         * KEY FIX: gunakan display:flex + align-items:center secara pure CSS
         * tanpa @apply agar tidak ada konflik dengan Tailwind CDN JIT.
         * Setiap nav item dijamin 1 baris dengan icon & teks sejajar.
         */
        .sidebar-link {
            display: flex;
            align-items: center;
            flex-direction: row;          /* paksa horizontal */
            flex-wrap: nowrap;            /* tidak boleh wrap ke baris baru */
            gap: 10px;
            padding: 9px 14px;
            margin: 1px 8px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            line-height: 1.4;
            color: #444651;              /* on-surface-variant */
            text-decoration: none;
            transition: background 150ms ease, color 150ms ease;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }
        .sidebar-link:hover {
            background-color: #eaeef2;   /* surface-container */
            color: #00236f;              /* primary */
        }

        /* Active state: left border indicator (seperti Linear, Notion, Vercel) */
        .sidebar-link.active {
            background-color: #dce1ff;   /* primary-fixed */
            color: #00236f;              /* primary */
            font-weight: 600;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background-color: #00236f;  /* primary */
        }

        /* Ikon: ukuran tetap, tidak menyusut */
        .sidebar-link .nav-icon {
            display: block;
            width: 20px;
            height: 20px;
            font-size: 20px;
            flex-shrink: 0;
            line-height: 20px;
        }

        /* Teks label: bisa truncate jika overflow */
        .sidebar-link .nav-label {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
            min-width: 0;
        }

        /* ── Decorative ───────────────────────────────────────── */
        .songket-pattern {
            background-image: radial-gradient(circle at 1px 1px, #fd8a42 1px, transparent 0);
            background-size: 24px 24px;
            opacity: 0.06;
        }

        /* ── Animations ───────────────────────────────────────── */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.3s ease-out; }

        /* ── Buttons ──────────────────────────────────────────────────────────────
         *  Semua tombol menggunakan warna SOLID yang jelas berbeda dari background
         *  agar user bisa langsung mengenalinya sebagai elemen interaktif.
         *  Bentuk: persegi dengan sudut rounded (rounded-lg).
         * ─────────────────────────────────────────────────────────────────────── */

        /* PRIMARY: Biru gelap — aksi utama (Simpan, Proses, Submit) */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px;
            background-color: #00236f;       /* primary */
            color: #ffffff;
            font-size: 13.5px; font-weight: 600; letter-spacing: 0.01em;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 150ms ease, transform 100ms ease, box-shadow 150ms ease;
            box-shadow: 0 1px 3px rgba(0,35,111,0.25);
            text-decoration: none;
        }
        .btn-primary:hover  { background-color: #1e3a8a; box-shadow: 0 2px 8px rgba(0,35,111,0.35); }
        .btn-primary:active { transform: scale(0.97); }

        /* SECONDARY: Amber/oranye — aksi sekunder (Verifikasi, Kembalikan) */
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px;
            background-color: #b45309;       /* amber-700 — solid, kontras */
            color: #ffffff;
            font-size: 13.5px; font-weight: 600; letter-spacing: 0.01em;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 150ms ease, transform 100ms ease, box-shadow 150ms ease;
            box-shadow: 0 1px 3px rgba(180,83,9,0.25);
            text-decoration: none;
        }
        .btn-secondary:hover  { background-color: #92400e; box-shadow: 0 2px 8px rgba(180,83,9,0.35); }
        .btn-secondary:active { transform: scale(0.97); }

        /* DANGER: Merah — aksi destruktif (Hapus) */
        .btn-danger {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px;
            background-color: #ba1a1a;       /* error */
            color: #ffffff;
            font-size: 13.5px; font-weight: 600; letter-spacing: 0.01em;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 150ms ease, transform 100ms ease, box-shadow 150ms ease;
            box-shadow: 0 1px 3px rgba(186,26,26,0.25);
            text-decoration: none;
        }
        .btn-danger:hover  { background-color: #93000a; box-shadow: 0 2px 8px rgba(186,26,26,0.35); }
        .btn-danger:active { transform: scale(0.97); }

        /* SUCCESS: Hijau — aksi positif (Proses Pengembalian, Bayar Denda) */
        .btn-success {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px;
            background-color: #15803d;       /* green-700 */
            color: #ffffff;
            font-size: 13.5px; font-weight: 600; letter-spacing: 0.01em;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 150ms ease, transform 100ms ease, box-shadow 150ms ease;
            box-shadow: 0 1px 3px rgba(21,128,61,0.25);
            text-decoration: none;
        }
        .btn-success:hover  { background-color: #166534; box-shadow: 0 2px 8px rgba(21,128,61,0.35); }
        .btn-success:active { transform: scale(0.97); }

        /* GHOST: Outline tipis — navigasi balik, batal */
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px;
            background-color: transparent;
            color: #00236f;                  /* primary */
            font-size: 13.5px; font-weight: 600; letter-spacing: 0.01em;
            border-radius: 8px;
            border: 1.5px solid #c5c5d3;    /* outline-variant */
            cursor: pointer;
            transition: background 150ms ease, border-color 150ms ease, transform 100ms ease;
            text-decoration: none;
        }
        .btn-ghost:hover  { background-color: #eaeef2; border-color: #00236f; }
        .btn-ghost:active { transform: scale(0.97); }

        /* ICON-ONLY: Tombol ikon bulat kecil untuk aksi inline tabel */
        .btn-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid #c5c5d3;
            background-color: transparent;
            cursor: pointer;
            transition: all 150ms ease;
        }
        .btn-icon:hover { background-color: #eaeef2; border-color: #757682; }
        .btn-icon.danger:hover  { background-color: #ffdad6; border-color: #ba1a1a; color: #ba1a1a; }
        .btn-icon.primary:hover { background-color: #dce1ff; border-color: #00236f; color: #00236f; }
        .btn-icon.amber:hover   { background-color: #fef3c7; border-color: #b45309; color: #b45309; }

        /* ── Forms ────────────────────────────────────────────── */
        .input-field { @apply w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/50; }
        .form-label  { @apply block text-label-md text-on-surface mb-1.5; }

        /* ── Card ─────────────────────────────────────────────── */
        .card { @apply bg-surface-container-lowest border border-outline-variant rounded-xl p-6; }

        /* ── Table ────────────────────────────────────────────── */
        .table-header { @apply text-label-md text-on-surface-variant uppercase tracking-wider text-left py-3 px-4; }
        .table-cell   { @apply py-3 px-4 text-body-md text-on-surface; }
    </style>

    @stack('styles')
</head>
<body class="bg-background text-on-surface flex h-screen overflow-hidden">

{{-- ═══════════════════ SIDEBAR ════════════════════════════════════════════ --}}
<aside id="sidebar" class="w-60 flex-shrink-0 h-screen bg-white border-r border-outline-variant flex flex-col z-40 transition-transform duration-300">

    {{-- Brand / Logo --}}
    <div class="h-16 px-4 flex items-center gap-3 border-b border-outline-variant flex-shrink-0">
        <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-white icon-filled" style="font-size:20px;">local_library</span>
        </div>
        <div class="min-w-0">
            <p class="text-[14px] font-bold text-primary leading-tight truncate">LMS Perpustakaan</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">Pusda Sumsel</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-3">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined nav-icon {{ request()->routeIs('dashboard') ? 'icon-filled' : '' }}">dashboard</span>
            <span class="nav-label">Dashboard</span>
        </a>

        {{-- Section: Perpustakaan --}}
        <div class="px-5 pt-5 pb-1.5">
            <p class="text-[10px] text-outline uppercase tracking-widest font-bold">Perpustakaan</p>
        </div>

        <a href="{{ route('anggota.index') }}"
           class="sidebar-link {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined nav-icon {{ request()->routeIs('anggota.*') ? 'icon-filled' : '' }}">group</span>
            <span class="nav-label">Manajemen Anggota</span>
        </a>

        <a href="{{ route('buku.index') }}"
           class="sidebar-link {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined nav-icon {{ request()->routeIs('buku.*') ? 'icon-filled' : '' }}">menu_book</span>
            <span class="nav-label">Katalog Buku</span>
        </a>

        <a href="{{ route('transaksi.index') }}"
           class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined nav-icon {{ request()->routeIs('transaksi.*') ? 'icon-filled' : '' }}">swap_horiz</span>
            <span class="nav-label">Terminal Transaksi</span>
        </a>

        <a href="{{ route('riwayat.index') }}"
           class="sidebar-link {{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined nav-icon {{ request()->routeIs('riwayat.*') ? 'icon-filled' : '' }}">history</span>
            <span class="nav-label">Riwayat Transaksi</span>
        </a>

    </nav>

    {{-- User Profile --}}
    <div class="p-3 border-t border-outline-variant flex-shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-all">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                <span class="text-[11px] font-bold text-white">{{ auth()->user()->initials ?? 'US' }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold text-on-surface truncate leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-on-surface-variant capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar"
                    class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error-container rounded-lg transition-all">
                    <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ═══════════════════ MAIN CONTENT ═══════════════════════════════════════ --}}
<div class="flex-1 flex flex-col h-screen overflow-hidden">

    {{-- Top App Bar --}}
    <header class="h-16 bg-white border-b border-outline-variant flex items-center justify-between px-6 flex-shrink-0 z-30">
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="md:hidden p-2 hover:bg-surface-container rounded-lg transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">menu</span>
            </button>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size:18px;display:block;">search</span>
                <input type="text"
                    placeholder="Cari NIK, ISBN, atau nama..."
                    class="pl-9 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-[14px] focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all w-72"
                    id="globalSearch">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[12px] text-on-surface-variant">{{ now()->setTimezone('Asia/Jakarta')->isoFormat('dddd, D MMMM Y') }}</span>
            <div class="w-px h-4 bg-outline-variant mx-1"></div>
            <span class="text-[12px] font-bold text-primary">{{ now()->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</span>
        </div>
    </header>

    {{-- Alert Messages --}}
    @if(session('success') || session('error'))
    <div class="px-6 pt-4 fade-in flex-shrink-0" id="alertContainer">
        @if(session('success'))
        <div class="flex items-start gap-3 p-4 bg-surface-container-low border border-outline-variant rounded-xl text-on-surface">
            <span class="material-symbols-outlined text-green-600 icon-filled flex-shrink-0">check_circle</span>
            <p class="text-body-md">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="ml-auto text-outline hover:text-on-surface">
                <span class="material-symbols-outlined" style="font-size:18px;display:block;">close</span>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-start gap-3 p-4 bg-error-container border border-error/20 rounded-xl text-on-error-container">
            <span class="material-symbols-outlined icon-filled flex-shrink-0 text-error">error</span>
            <p class="text-body-md">{{ session('error') }}</p>
            <button onclick="this.parentElement.remove()" class="ml-auto text-outline hover:text-on-error-container">
                <span class="material-symbols-outlined" style="font-size:18px;display:block;">close</span>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Page Content — NO outer padding, each page controls its own layout --}}
    <main class="flex-1 overflow-y-auto custom-scrollbar bg-background">
        @yield('content')
    </main>
</div>

<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.getElementById('alertContainer')?.remove();
    }, 5000);

    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });
</script>

@stack('scripts')
</body>
</html>
