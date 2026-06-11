<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - LMS Perpustakaan</title>

    {{-- Google Fonts: Public Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#00236f',
                        'primary-container': '#1e3a8a',
                        'on-primary': '#ffffff',
                        'secondary-container': '#fd8a42',
                        'on-secondary-container': '#682c00',
                        'surface': '#f6fafe',
                        'outline-variant': '#c5c5d3',
                        'error': '#ba1a1a',
                        'error-container': '#ffdad6',
                        'on-error-container': '#93000a',
                    },
                    fontFamily: {
                        'sans': ['Public Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .songket-pattern {
            background-image: radial-gradient(circle at 1px 1px, #fd8a42 1px, transparent 0);
            background-size: 24px 24px;
            opacity: 0.08;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary via-slate-900 to-indigo-950 flex min-h-screen items-center justify-center p-4 relative overflow-hidden">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute inset-0 songket-pattern pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-secondary-container rounded-full blur-[120px] opacity-20 pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-primary rounded-full blur-[120px] opacity-30 pointer-events-none"></div>

    <div class="w-full max-w-md bg-white/90 backdrop-blur-md border border-white/20 rounded-2xl shadow-2xl p-8 z-10 transition-all duration-300">
        {{-- Header Logo & App Title --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/30 mb-4">
                <span class="material-symbols-outlined text-white text-3xl font-variation-settings: 'FILL' 1">local_library</span>
            </div>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">LMS Perpustakaan</h1>
            <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold mt-1">Pustakawan Portal</p>
        </div>

        {{-- Validation Error Alerts --}}
        @if ($errors->any())
        <div class="mb-6 p-4 bg-error-container border border-error/20 rounded-xl text-on-error-container text-sm flex gap-3 items-start animate-pulse">
            <span class="material-symbols-outlined text-error flex-shrink-0">error</span>
            <div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Form Login --}}
        <form method="POST" action="{{ route('auth.login') }}" class="space-y-5">
            @csrf

            {{-- Input Email/NIP --}}
            <div>
                <label for="login" class="block text-sm font-semibold text-slate-700 mb-1.5">Email atau NIP</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">person</span>
                    <input type="text" 
                           name="login" 
                           id="login" 
                           value="{{ old('login') }}" 
                           required 
                           autofocus
                           placeholder="pustakawan@perpustakaan.go.id" 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-slate-400">
                </div>
            </div>

            {{-- Input Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           placeholder="••••••••" 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-slate-400">
                </div>
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between text-xs text-slate-500 font-medium">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary focus:ring-primary">
                    <span>Ingat Saya</span>
                </label>
                <a href="#" class="hover:text-primary hover:underline transition-all">Lupa Kata Sandi?</a>
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full py-3 px-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-container active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-2">
                <span>Masuk</span>
                <span class="material-symbols-outlined text-lg">login</span>
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 text-center text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} Dinas Perpustakaan Provinsi Sumatera Selatan.</p>
            <p class="mt-1">Layanan Manajemen Sistem Perpustakaan v1.0.0</p>
        </div>
    </div>
</body>
</html>
