@extends('layouts.master')

@section('title', 'Profil BPS Kota Sukabumi')
@section('header_title', 'Profil BPS Kota Sukabumi')

@section('content')
<style>
    .hero-bg {
        background-color: #f4f7fb;
    }

    /* =========================================
       CORNER BRACKET FRAME (Rapi & Presisi)
       ========================================= */
    .image-container {
        position: relative;
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        padding: 18px; /* Menjaga jarak aman antara foto dan garis warna */
        z-index: 10;
    }

    .img-main {
        width: 100%;
        height: 380px;
        object-fit: cover;
        object-position: center 20%;
        border-radius: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        position: relative;
        z-index: 20;
    }

    /* 1. Garis Biru (Sudut Kanan Atas) */
    .frame-blue {
        position: absolute;
        top: 0; right: 0;
        width: 50%; height: 45%;
        border-top: 7px solid #1e40af;
        border-right: 7px solid #1e40af;
        border-top-right-radius: 2.5rem;
        z-index: 25;
    }

    /* 2. Garis Hijau (Sudut Kiri Bawah) */
    .frame-green {
        position: absolute;
        bottom: 0; left: 0;
        width: 45%; height: 50%;
        border-bottom: 7px solid #10b981;
        border-left: 7px solid #10b981;
        border-bottom-left-radius: 2.5rem;
        z-index: 25;
    }

    /* 3. Garis Oranye (Sudut Kanan Bawah) */
    .frame-orange {
        position: absolute;
        bottom: 0; right: 0;
        width: 30%; height: 30%;
        border-bottom: 7px solid #f59e0b;
        border-right: 7px solid #f59e0b;
        border-bottom-right-radius: 2.5rem;
        z-index: 25;
    }

    /* 4. Titik-titik (Sudut Kiri Atas) */
    .shape-dots {
        position: absolute;
        top: 5px; left: 5px;
        width: 100px; height: 100px;
        background-image: radial-gradient(#cbd5e1 2px, transparent 2px);
        background-size: 16px 16px;
        z-index: 5;
    }
    
    /* Visi Misi Card BG */
    .visi-misi-card {
        background: linear-gradient(135deg, #f0f4fa 0%, #e2e8f0 100%);
    }
    
    /* Polka dots background for Arti Logo section */
    .polka-bg {
        background-image: radial-gradient(#e5e7eb 2px, transparent 2px);
        background-size: 20px 20px;
        background-position: center;
    }
</style>

<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <!-- Hero Section -->
    <div class="hero-bg flex flex-col md:flex-row relative min-h-[450px]">
        <!-- Text Content -->
        <div class="md:w-5/12 p-10 md:p-14 flex flex-col justify-center z-20">
            <h2 class="text-[#64748b] text-xl mb-2 font-medium">Selamat Datang di</h2>
            <h1 class="text-4xl md:text-[2.75rem] font-bold text-[#1e3a8a] mb-6 leading-tight tracking-tight">BPS KOTA<br>SUKABUMI</h1>
            <p class="text-[#475569] mb-8 text-base leading-relaxed">
                Badan Pusat Statistik Kota Sukabumi menyediakan data statistik yang akurat, terpercaya, dan terkini untuk mendukung pembangunan daerah.
            </p>
            <div>
                <a href="https://sukabumikota.bps.go.id/" target="_blank" class="inline-block bg-[#1e40af] text-white px-8 py-3 rounded-md hover:bg-blue-900 transition-colors duration-200 font-medium shadow-md">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
        
        <!-- Image Content (Corner Bracket Frame) -->
        <div class="md:w-7/12 relative flex items-center justify-center p-10 md:p-14">
            <div class="image-container">
                <!-- Dekorasi Titik -->
                <div class="shape-dots"></div>

                <!-- 3 Garis Warna Sudut -->
                <div class="frame-blue"></div>
                <div class="frame-green"></div>
                <div class="frame-orange"></div>
                
                <!-- Gambar Utama -->
                <img src="{{ asset('images/gedung-bps.png') }}" alt="Gedung BPS Kota Sukabumi" class="img-main">
            </div>
        </div>

    </div>

    <!-- Sejarah & Visi Misi Section -->
    <div class="p-10 md:p-14">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
            <!-- Sejarah -->
            <div>
                <h3 class="text-3xl font-bold text-[#1e3a8a] mb-6 tracking-tight">Sejarah Singkat</h3>
                <div class="text-[#475569] space-y-5 text-[1.05rem] leading-relaxed">
                    <p>BPS Kota Sukabumi merupakan perwakilan dari Badan Pusat Statistik yang berperan dalam penyediaan data statistik di tingkat daerah.</p>
                    <p>Sejak awal berdirinya, BPS telah menjadi sumber utama data untuk mendukung perencanaan, evaluasi, dan pengambilan kebijakan pemerintah di Kota Sukabumi.</p>
                </div>
            </div>
            
            <!-- Visi Misi -->
            <div class="visi-misi-card p-10 rounded-3xl shadow-sm">
                <h3 class="text-3xl font-bold text-[#1e3a8a] mb-8 tracking-tight">Visi & Misi</h3>
                
                <div class="flex items-start gap-4 mb-8">
                    <span class="bg-[#1e73be] text-white px-5 py-2 rounded-lg font-bold text-sm shadow-sm mt-1">Visi</span>
                    <p class="font-bold text-[#1e293b] text-xl leading-snug tracking-tight">"Penyedia Data Statistik Berkualitas Untuk Indonesia Maju"</p>
                </div>

                <div class="space-y-4 text-[#475569] text-base">
                    <div class="flex gap-4">
                        <span class="text-[#1e73be] font-bold text-lg">1.</span>
                        <p class="pt-0.5">Menyediakan data statistik berkualitas dan wawasan (insight) untuk perumusan kebijakan dan pengambilan keputusan.</p>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-[#1e73be] font-bold text-lg">2.</span>
                        <p class="pt-0.5">Menguatkan kepemimpinan BPS dalam penyelenggaraan Sistem Statistik Nasional (SSN).</p>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-[#1e73be] font-bold text-lg">3.</span>
                        <p class="pt-0.5">Menguatkan kapasitas kelembagaan statistik yang efektif dan efisien.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Arti Logo Section -->
    <div class="bg-[#f8fafc] p-10 md:p-14 border-t border-gray-100 relative overflow-hidden">
        <div class="absolute inset-0 polka-bg opacity-30 z-0 pointer-events-none"></div>
        <div class="relative z-10">
            <h3 class="text-3xl font-bold text-[#1e3a8a] mb-6 tracking-tight">Arti Logo</h3>
            <p class="text-[#475569] mb-10 max-w-4xl text-[1.05rem]">
                Logo pada Badan Pusat Statistik memiliki warna biru, hijau dan orange dan disetiap warna memiliki arti khusus, yaitu:
            </p>
            
            <div class="space-y-8">
                <!-- Biru -->
                <div class="flex items-start gap-5">
                    <div class="w-5 h-5 rounded-full bg-[#3b82f6] mt-1.5 shrink-0 shadow-sm"></div>
                    <div>
                        <h4 class="font-bold text-[#1e3a8a] text-lg mb-2">Biru</h4>
                        <p class="text-[#475569] text-base leading-relaxed">Melambangkan kegiatan sensus penduduk yang dilakukan sepuluh tahun sekali pada setiap tahun yang berakhiran angka 0 (nol).</p>
                    </div>
                </div>
                
                <!-- Hijau -->
                <div class="flex items-start gap-5">
                    <div class="w-5 h-5 rounded-full bg-[#10b981] mt-1.5 shrink-0 shadow-sm"></div>
                    <div>
                        <h4 class="font-bold text-[#1e3a8a] text-lg mb-2">Hijau</h4>
                        <p class="text-[#475569] text-base leading-relaxed">Melambangkan kegiatan sensus pertanian yang dilakukan sepuluh tahun sekali pada setiap tahun yang berakhiran angka 3 (tiga).</p>
                    </div>
                </div>
                
                <!-- Oranye -->
                <div class="flex items-start gap-5">
                    <div class="w-5 h-5 rounded-full bg-[#f59e0b] mt-1.5 shrink-0 shadow-sm"></div>
                    <div>
                        <h4 class="font-bold text-[#1e3a8a] text-lg mb-2">Oranye</h4>
                        <p class="text-[#475569] text-base leading-relaxed">Melambangkan kegiatan sensus ekonomi yang dilakukan sepuluh tahun sekali pada setiap tahun yang berakhiran angka 6 (enam).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
