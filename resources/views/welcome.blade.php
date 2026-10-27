<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi SDM Kearsipan - Badan Informasi Geospasial</title>

    <!-- Memanggil Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#1e3a8a',
                        'secondary-blue': '#3b82f6',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- ================= BAGIAN 1: NAVBAR ================= -->
    <nav class="bg-white shadow-md fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="mr-4 pr-4 border-r-2 border-gray-200 flex items-center h-12">
                        <img src="{{ asset('img/logo-big.png') }}" alt="Logo BIG"
                            class="h-12 w-auto object-contain drop-shadow-sm hover:scale-105 transition-transform duration-300">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-primary-blue leading-tight">SDM KEARSIPAN</h1>
                        <p class="text-sm text-gray-600">Badan Informasi Geospasial</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-gray-700 hover:text-secondary-blue font-medium transition duration-150">Beranda</a>
                    <a href="#informasi-publik"
                        class="text-gray-700 hover:text-secondary-blue font-medium transition duration-150">Informasi
                        Publik</a>
                    <a href="{{ route('login') }}"
                        class="bg-primary-blue text-white px-5 py-2 rounded-md hover:bg-blue-800 font-medium transition duration-150 shadow-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ================= BAGIAN 2: HEADLINE CAROUSEL ================= -->
    <div class="relative mt-20 bg-primary-blue overflow-hidden shadow-xl" style="height: 500px;">
        <div id="carousel-inner" class="relative w-full h-full">

            @forelse ($beritaTerbaru as $index => $item)
                <div
                    class="carousel-slide absolute inset-0 flex flex-col md:flex-row transition-opacity duration-700 ease-in-out {{ $index == 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                    <div
                        class="w-full md:w-1/3 bg-primary-blue text-white p-8 md:p-12 flex flex-col justify-center border-r border-blue-800 z-20">
                        <h2 class="text-sm font-bold text-blue-300 mb-2 tracking-widest uppercase">Headline</h2>
                        <p class="text-xs text-gray-300 mb-4"><i
                                class="far fa-calendar-alt mr-2"></i>{{ $item->created_at->format('d F Y') }}</p>
                        <h3 class="text-2xl md:text-3xl font-bold mb-4 leading-tight">{{ $item->Judul }}</h3>
                        <p class="text-sm text-gray-200 mb-8 line-clamp-3">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->Konten), 120) }}
                        </p>
                        <a href="#berita-section"
                            class="inline-block bg-white text-primary-blue font-bold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition duration-300 w-max">
                            Baca Selengkapnya <i class="fas fa-arrow-down ml-2"></i>
                        </a>
                    </div>
                    <div class="w-full md:w-2/3 h-64 md:h-full relative">
                        <img src="{{ asset('gambar_berita/' . $item->Gambar) }}" class="w-full h-full object-cover"
                            alt="{{ $item->Judul }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-l from-primary-blue to-transparent opacity-80 md:w-24">
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-slide absolute inset-0 flex flex-col md:flex-row opacity-100 z-10">
                    <div class="w-full md:w-1/3 bg-primary-blue text-white p-8 md:p-12 flex flex-col justify-center">
                        <h2 class="text-sm font-bold text-blue-300 mb-2 tracking-widest uppercase">Informasi</h2>
                        <h3 class="text-3xl font-bold mb-4">Selamat Datang di SiARSIPARIS</h3>
                        <p class="text-sm text-gray-200 mb-8">Sistem Informasi Pengelolaan SDM Kearsipan Badan Informasi
                            Geospasial.</p>
                        <a href="#informasi-publik"
                            class="inline-block bg-white text-primary-blue font-bold px-6 py-3 rounded-full w-max">Lihat
                            Data SDM</a>
                    </div>
                    <div class="w-full md:w-2/3 h-full relative">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80"
                            class="w-full h-full object-cover" alt="Default">
                    </div>
                </div>
            @endforelse
        </div>

        @if (count($beritaTerbaru) > 1)
            <button onclick="prevSlide()"
                class="absolute left-4 md:left-[35%] top-1/2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-70 text-white rounded-full w-12 h-12 flex items-center justify-center z-30 transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="nextSlide()"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-70 text-white rounded-full w-12 h-12 flex items-center justify-center z-30 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-3 z-30">
                @foreach ($beritaTerbaru as $index => $item)
                    <span class="dot w-3 h-3 rounded-full bg-white opacity-50 cursor-pointer transition"
                        onclick="goToSlide({{ $index }})"></span>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ================= BAGIAN 3: DAFTAR BERITA (Grid) ================= -->
    <main id="berita-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold text-gray-900 border-b-4 border-primary-blue inline-block pb-2">Berita &
                Pengumuman</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($beritaTerbaru as $item)
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <img src="{{ asset('gambar_berita/' . $item->Gambar) }}"
                        class="w-full h-48 object-cover transform transition-transform duration-500 hover:scale-110"
                        alt="{{ $item->Judul }}" loading="lazy">
                    <div class="p-6">
                        <p class="text-xs text-gray-500 mb-2"><i
                                class="far fa-calendar-alt mr-1"></i>{{ $item->created_at->format('d M Y') }}</p>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 hover:text-primary-blue cursor-pointer">
                            {{ $item->Judul }}</h4>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ \Illuminate\Support\Str::limit($item->Konten, 100) }}</p>
                        <a href="{{ route('berita.baca', $item->id) }}"
                            class="text-secondary-blue font-semibold text-sm hover:underline">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <i class="fas fa-newspaper text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500">Belum ada berita atau informasi yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- ================= BAGIAN 3.5: STATISTIK ARSIP (Banner Biru) ================= -->
    <section class="bg-[#0288D1] text-white py-10 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center divide-x divide-blue-400/50">
                <div class="mb-3">
                    <h2 class="font-bold text-4xl md:text-5xl drop-shadow-md">{{ $arsipVital }}</h2>
                    <p class="text-sm md:text-base font-medium mt-2 text-blue-100">Total Arsip Vital</p>
                </div>
                <div class="mb-3">
                    <h2 class="font-bold text-4xl md:text-5xl drop-shadow-md">{{ $arsipTerjaga }}</h2>
                    <p class="text-sm md:text-base font-medium mt-2 text-blue-100">Total Arsip Terjaga</p>
                </div>
                <div class="mb-3">
                    <h2 class="font-bold text-4xl md:text-5xl drop-shadow-md">{{ $arsipInaktif }}</h2>
                    <p class="text-sm md:text-base font-medium mt-2 text-blue-100">Total Arsip Inaktif</p>
                </div>
                <div class="mb-3 border-t md:border-t-0 pt-4 md:pt-0">
                    <h2 class="font-bold text-4xl md:text-5xl drop-shadow-md">{{ $totalPengunjung }}</h2>
                    <p class="text-sm md:text-base font-medium mt-2 text-blue-100">Total Pengunjung</p>
                </div>
                <div class="mb-3 border-t md:border-t-0 pt-4 md:pt-0">
                    <h2 class="font-bold text-4xl md:text-5xl drop-shadow-md">{{ $pengunjungHariIni }}</h2>
                    <p class="text-sm md:text-base font-medium mt-2 text-blue-100">Pengunjung Hari Ini</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= BAGIAN 3.75: POJOK EDUKASI DINAMIS (Fun Fact) ================= -->
    @if ($funFact)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-16">
            <div
                class="shadow-lg rounded-2xl overflow-hidden bg-gradient-to-br from-white to-blue-50 border-l-8 border-[#0288D1]">
                <div class="flex flex-col md:flex-row items-stretch">

                    <!-- Kolom Gambar Vertikal -->
                    <div class="w-full md:w-1/3 p-0">
                        <img src="{{ asset('storage/' . $funFact->gambar) }}" alt="{{ $funFact->judul }}"
                            class="w-full h-full object-cover max-h-[500px]">
                    </div>

                    <!-- Kolom Teks Konten -->
                    <div class="w-full md:w-2/3 p-6 md:p-10">
                        <span
                            class="inline-block bg-orange-500 text-white rounded-full px-3 py-1 text-sm font-semibold mb-3 shadow-sm">{{ $funFact->tag_badge }}
                            ✨</span>
                        <h2 class="font-bold text-2xl md:text-3xl mb-4 text-[#0288D1]">{{ $funFact->judul }}</h2>

                        <p class="text-gray-500 text-lg mb-6">{{ $funFact->deskripsi_singkat }}</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @if ($funFact->poin_1_judul)
                                <div
                                    class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
                                    <h5 class="font-bold text-[#0288D1] mb-2">{{ $funFact->poin_1_judul }}</h5>
                                    <p class="text-sm text-gray-600 m-0">{{ $funFact->poin_1_isi }}</p>
                                </div>
                            @endif

                            @if ($funFact->poin_2_judul)
                                <div
                                    class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
                                    <h5 class="font-bold text-red-500 mb-2">{{ $funFact->poin_2_judul }}</h5>
                                    <p class="text-sm text-gray-600 m-0">{{ $funFact->poin_2_isi }}</p>
                                </div>
                            @endif

                            @if ($funFact->poin_3_judul)
                                <div
                                    class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
                                    <h5 class="font-bold text-green-500 mb-2">{{ $funFact->poin_3_judul }}</h5>
                                    <p class="text-sm text-gray-600 m-0">{{ $funFact->poin_3_isi }}</p>
                                </div>
                            @endif
                        </div>

                        @if ($funFact->kesimpulan)
                            <div class="mt-6 p-4 bg-blue-50 border-l-4 border-[#0288D1] rounded-r-lg text-gray-700">
                                {{ $funFact->kesimpulan }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </section>
    @endif

    <!-- ================= BAGIAN 4: DATA SDM (Informasi Publik) ================= -->
    <section id="informasi-publik" class="bg-white py-16 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h3 class="text-3xl font-bold text-gray-900 border-b-4 border-primary-blue inline-block pb-2">Informasi
                    Publik: Data SDM</h3>
                <p class="mt-4 text-gray-600">Daftar Arsiparis dan Pengelola Arsip di Lingkungan Badan Informasi
                    Geospasial.</p>
            </div>

            <!-- Form Pencarian Publik -->
            <div class="mb-6 flex justify-end">
                <form action="{{ url('/#informasi-publik') }}" method="GET" class="flex w-full md:w-1/3">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama atau NIP..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary-blue">
                    <button type="submit"
                        class="bg-primary-blue text-white px-4 py-2 rounded-r-md hover:bg-blue-800 transition"><i
                            class="fas fa-search"></i></button>
                    @if (request('search'))
                        <a href="{{ url('/#informasi-publik') }}"
                            class="bg-red-500 text-white px-4 py-2 ml-2 rounded-md hover:bg-red-600 transition">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Tabel Data Berdesain Tailwind -->
            <div class="overflow-x-auto shadow-md sm:rounded-lg mb-6">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-white uppercase bg-primary-blue">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-3">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                PANGKAT</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                GOLONGAN</th>
                            <th scope="col" class="px-6 py-3">Jabatan</th>
                            <th scope="col" class="px-6 py-3">Unit Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataArsiparis as $sdm)
                            <tr class="bg-white border-b hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $dataArsiparis->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $sdm->nama }}</td>
                                <td class="px-6 py-4">{{ $sdm->nip }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $sdm->pangkat ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $sdm->golongan ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $sdm->jabatan }}</td>
                                <td class="px-6 py-4">{{ $sdm->unit_kerja ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data SDM
                                    Arsiparis yang sesuai dengan pencarian Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi Tailwind Secara Eksplisit -->
            <div class="mt-4">
                {{ $dataArsiparis->withQueryString()->fragment('informasi-publik')->links('pagination::tailwind') }}
            </div>
        </div>
    </section>

    <!-- ================= BAGIAN 5: FOOTER ================= -->
    <footer class="bg-primary-blue text-white py-8 mt-10">
        <div class="text-center text-sm text-blue-300">
            &copy; 2026 Badan Informasi Geospasial. Hak Cipta Dilindungi.<br>
        </div>
    </footer>

    <!-- Script Animasi Slider -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.dot');

        function updateSlider(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.remove('opacity-0', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'z-0');
                }
            });

            if (dots.length > 0) {
                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.remove('opacity-50');
                        dot.classList.add('opacity-100');
                    } else {
                        dot.classList.remove('opacity-100');
                        dot.classList.add('opacity-50');
                    }
                });
            }
        }

        function nextSlide() {
            if (slides.length <= 1) return;
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlider(currentSlide);
        }

        function prevSlide() {
            if (slides.length <= 1) return;
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateSlider(currentSlide);
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider(currentSlide);
        }

        if (slides.length > 1) {
            updateSlider(0);
            setInterval(nextSlide, 5000);
        }
    </script>
</body>

</html>
