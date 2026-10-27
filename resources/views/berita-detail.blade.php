<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->Judul ?? 'Baca Berita' }} - KEARSIPAN BIG</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .konten-berita p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
            text-align: justify;
            color: #374151;
        }
    </style>
</head>

<body class="bg-slate-50 font-sans antialiased text-gray-900">

    <!-- 1. NAVBAR -->
    <nav class="bg-gradient-to-r from-blue-950 to-blue-800 shadow-lg border-b-4 border-blue-400 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <span class="font-bold text-white tracking-widest text-lg drop-shadow-md">
                        SDM KEARSIPAN <span class="text-blue-300">BIG</span>
                    </span>
                </div>
                <div>
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-lg transition-colors duration-200 backdrop-blur-sm border border-white/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. KONTEN UTAMA -->
    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <article class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

            <div class="p-8 md:p-12 pb-6">
                <div class="flex items-center text-sm font-semibold text-blue-600 mb-4 tracking-wide uppercase">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>
                        @if (isset($berita) && isset($berita->created_at))
                            Dipublikasikan pada
                            {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}
                        @else
                            Dipublikasikan hari ini
                        @endif
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
                    @if (isset($berita) && isset($berita->Judul))
                        {{ $berita->Judul }}
                    @else
                        Judul Berita Tidak Ditemukan
                    @endif
                </h1>

                <hr class="border-t-2 border-blue-50 w-24 rounded-full">
            </div>

            <!-- PERBAIKAN: Menggunakan folder gambar_berita -->
            @if (isset($berita) && isset($berita->Gambar))
                <div class="w-full bg-gray-100 relative group">
                    <img src="{{ asset('gambar_berita/' . $berita->Gambar) }}" alt="Gambar Berita"
                        class="w-full h-auto max-h-[500px] object-cover object-center border-y border-gray-100 shadow-inner">
                </div>
            @endif

            <div class="p-8 md:p-12 pt-8 konten-berita text-lg">
                <!-- PERBAIKAN: Memanggil Konten, bukan isi -->
                @if (isset($berita) && isset($berita->Konten))
                    <?php echo $berita->Konten; ?>
                @else
                    <p>Konten berita tidak tersedia.</p>
                @endif
            </div>

        </article>

    </main>

    <!-- 3. FOOTER -->
    <footer class="bg-slate-900 py-8 mt-8 border-t-4 border-blue-800">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <p class="text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} Badan Informasi Geospasial
            </p>
            <p class="text-slate-500 text-xs mt-1">
                Sistem Informasi SDM Kearsipan
            </p>
        </div>
    </footer>

</body>

</html>
