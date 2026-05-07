@extends('layouts.master')

@section('title', 'Profil BPS Kota Sukabumi')
@section('header_title', 'Profil BPS Kota Sukabumi')

@section('content')
<style>
    .hero-bg {
        background-color: #f4f7fb;
    }

    .image-container {
        position: relative;
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        padding: 18px; 
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

    .frame-blue {
        position: absolute;
        top: 0; right: 0;
        width: 50%; height: 45%;
        border-top: 7px solid #1e40af;
        border-right: 7px solid #1e40af;
        border-top-right-radius: 2.5rem;
        z-index: 25;
    }

    .frame-green {
        position: absolute;
        bottom: 0; left: 0;
        width: 45%; height: 50%;
        border-bottom: 7px solid #10b981;
        border-left: 7px solid #10b981;
        border-bottom-left-radius: 2.5rem;
        z-index: 25;
    }

    .frame-orange {
        position: absolute;
        bottom: 0; right: 0;
        width: 30%; height: 30%;
        border-bottom: 7px solid #f59e0b;
        border-right: 7px solid #f59e0b;
        border-bottom-right-radius: 2.5rem;
        z-index: 25;
    }

    .shape-dots {
        position: absolute;
        top: 5px; left: 5px;
        width: 100px; height: 100px;
        background-image: radial-gradient(#cbd5e1 2px, transparent 2px);
        background-size: 16px 16px;
        z-index: 5;
    }
    
    .polka-bg {
        background-image: radial-gradient(#cbd5e1 2px, transparent 2px);
        background-size: 20px 20px;
        background-position: center;
    }
</style>

<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    
    <div class="hero-bg flex flex-col md:flex-row relative min-h-[450px]">
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
        
        <div class="md:w-7/12 relative flex items-center justify-center p-10 md:p-14">
            <div class="image-container">
                <div class="shape-dots"></div>

                <div class="frame-blue"></div>
                <div class="frame-green"></div>
                <div class="frame-orange"></div>
                
                <img src="{{ asset('images/gedung-bps.png') }}" alt="Gedung BPS Kota Sukabumi" class="img-main">
            </div>
        </div>
    </div>

    <div class="p-10 md:p-14 border-t border-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
            
            <div class="relative">
                <div class="absolute -left-6 top-0 bottom-0 w-1 bg-blue-500/20 rounded-full hidden md:block"></div>
                <h3 class="text-2xl md:text-3xl font-bold text-[#1e3a8a] mb-6 tracking-tight flex items-center gap-3">
                    <i class="fas fa-history text-xl text-blue-500"></i> Sejarah Singkat
                </h3>
                <div class="text-[#475569] space-y-5 text-sm md:text-[1.05rem] leading-relaxed text-left md:text-justify italic">
                    <p>
                        <span class="text-3xl md:text-4xl font-bold text-[#1e3a8a] float-left mr-2 leading-none">B</span>adan Pusat Statistik bermula dari lembaga statistik yang dibentuk pada masa Hindia Belanda di Bogor tahun 1920 dan kemudian pindah ke Jakarta tahun 1924 dengan nama Centraal Kantoor Voor De Statistiek (CKS). Pada masa pendudukan Jepang tahun 1942–1945, nama lembaga berubah menjadi Shomubu Chosasitsu Gunseikanbu. Setelah Indonesia merdeka, lembaga tersebut dinasionalisasi menjadi KAPPURI lalu berkembang menjadi Biro Pusat Statistik. Pada tahun 1997, berdasarkan UU No. 16 Tahun 1997, nama Biro Pusat Statistik resmi berubah menjadi Badan Pusat Statistik (BPS).
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="text-3xl font-bold text-[#1e3a8a] mb-8 tracking-tight flex items-center gap-3">
                    <i class="fas fa-bullseye text-xl text-blue-500"></i> Visi & Misi
                </h3>
                
                <div class="bg-gradient-to-r from-[#1e40af] to-[#1e73be] p-6 rounded-2xl shadow-md mb-6 transform hover:scale-[1.01] transition-transform">
                    <span class="text-blue-200 text-xs font-bold uppercase tracking-widest">Visi Kami</span>
                    <p class="text-white text-xl font-bold mt-2 leading-snug">
                        "Penyedia Data Statistik Berkualitas Untuk Indonesia Maju"
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-check text-[#1e73be]"></i>
                        </div>
                        <p class="text-sm text-[#475569] font-medium">Menyediakan data statistik berkualitas dan wawasan (insight).</p>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-shield-alt text-[#1e73be]"></i>
                        </div>
                        <p class="text-sm text-[#475569] font-medium">Menguatkan kepemimpinan BPS dalam Sistem Statistik Nasional.</p>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-users-cog text-[#1e73be]"></i>
                        </div>
                        <p class="text-sm text-[#475569] font-medium">Menguatkan kapasitas kelembagaan statistik yang efektif.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#f8fafc] p-10 md:p-14 border-t border-gray-100 relative overflow-hidden">
        <div class="absolute inset-0 polka-bg opacity-30 z-0 pointer-events-none"></div>
        <div class="relative z-10">
            <!-- Header Section with Logo Integration -->
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-10 mb-10 md:mb-16">
                <div class="md:w-1/3 flex justify-center">
                    <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50 transform md:-rotate-2 hover:rotate-0 transition-all duration-500 group">
                        <img src="{{ asset('images/logo_bps.png') }}" alt="Logo BPS" class="w-32 md:w-48 h-auto transition-transform duration-500 group-hover:scale-105">
                    </div>
                </div>
                <div class="md:w-2/3 text-center md:text-left">
                    <h3 class="text-2xl md:text-3xl font-bold text-[#1e3a8a] mb-4 md:mb-5 tracking-tight flex items-center gap-3 justify-center md:justify-start">
                        Filosofi Warna Logo
                    </h3>
                    <p class="text-[#475569] text-base md:text-[1.1rem] leading-relaxed max-w-3xl">
                        Identitas visual Badan Pusat Statistik bukan sekadar estetika, melainkan simbol keberlanjutan data nasional. Tiga warna utama yaitu biru, hijau, dan oranye merepresentasikan tiga pilar sensus besar yang menjadi fondasi statistik Indonesia selama berdekade-dekade.
                    </p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50/60 p-6 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-[#3b82f6] shadow-md mb-4 flex items-center justify-center text-white font-bold text-xl">0</div>
                    <h4 class="font-bold text-[#1e3a8a] text-lg mb-2">Sensus Penduduk</h4>
                    <p class="text-sm text-[#475569] leading-relaxed">Dilaksanakan sepuluh tahun sekali pada setiap tahun yang berakhiran angka 0 (nol).</p>
                </div>
                <div class="bg-emerald-50/60 p-6 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-[#10b981] shadow-md mb-4 flex items-center justify-center text-white font-bold text-xl">3</div>
                    <h4 class="font-bold text-[#1e3a8a] text-lg mb-2">Sensus Pertanian</h4>
                    <p class="text-sm text-[#475569] leading-relaxed">Dilaksanakan sepuluh tahun sekali pada setiap tahun yang berakhiran angka 3 (tiga).</p>
                </div>
                <div class="bg-orange-50/60 p-6 rounded-2xl border border-orange-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-[#f59e0b] shadow-md mb-4 flex items-center justify-center text-white font-bold text-xl">6</div>
                    <h4 class="font-bold text-[#1e3a8a] text-lg mb-2">Sensus Ekonomi</h4>
                    <p class="text-sm text-[#475569] leading-relaxed">Dilaksanakan sepuluh tahun sekali pada setiap tahun yang berakhiran angka 6 (enam).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection