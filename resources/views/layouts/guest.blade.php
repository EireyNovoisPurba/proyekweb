<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Sistem Kearsipan BIG</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <!--
            Wadah Utama (Full Screen)
            Latar Belakang menggunakan foto pegawai bg-login.jpg
        -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('img/bg-login.jpg') }}');">

        <!-- Efek Kaca Gelap (Overlay Transparan) agar foto tidak mengalahkan kotak form -->
        <div class="absolute inset-0 bg-gray-900 bg-opacity-70 z-0"></div>

        <!-- Bagian Atas: Logo & Judul Aplikasi -->
        <div class="z-10 mb-8 text-center flex flex-col items-center">
            <a href="/">
                <!-- Menampilkan Logo BIG -->
                <img src="{{ asset('img/logo-big.png') }}" alt="Logo Badan Informasi Geospasial"
                    class="w-32 h-auto mx-auto drop-shadow-lg transform transition duration-500 hover:scale-105">

                <h2 class="text-white text-3xl font-extrabold mt-4 tracking-wide drop-shadow-md">
                    SISTEM INFORMASI KEARSIPAN
                </h2>
                <p class="text-gray-300 text-lg font-medium tracking-wider mt-1 drop-shadow">
                    BADAN INFORMASI GEOSPASIAL
                </p>
            </a>
        </div>

        <!--
             <!--
                Kotak Form Login (Efek Kaca / Glassmorphism)
            -->
        <div
            class="z-10 w-full sm:max-w-lg px-8 py-10 bg-white/50 backdrop-blur-md shadow-2xl overflow-hidden sm:rounded-2xl border border-white/50 border-t-4 border-t-blue-600">

            <div class="text-center mb-6">
                <h3 class="text-2xl font-extrabold text-gray-900">Silakan Masuk</h3>
                <p class="text-sm font-semibold text-gray-800 mt-1">Gunakan akun admin atau arsiparis Anda</p>
            </div>

            <!-- Ini adalah tempat form email & password dari login.blade.php disuntikkan -->
            {{ $slot }}

        </div>

        <!-- Footer Text -->
        <div class="z-10 mt-8 text-gray-400 text-sm font-medium tracking-wide">
            &copy; {{ date('Y') }} Biro Umum dan Keuangan - Arsip Nasional
        </div>
    </div>
</body>

</html>
