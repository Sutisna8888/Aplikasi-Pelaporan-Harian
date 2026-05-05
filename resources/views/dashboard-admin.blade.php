@extends('layouts.master')

@section('title', 'Dashboard Admin - ALPHA')
@section('header_title', 'Dashboard Admin')

@section('content')
<style>
    /* Custom CSS for Admin Dashboard */
    .admin-dashboard {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    .top-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }
    
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
    }
    
    .card-orange {
        border-left: 10px solid #f97316;
    }

    .card-blue {
        border-left: 10px solid #3b82f6;
    }
    
    .card-number {
        font-size: 4rem;
        font-weight: bold;
        margin-bottom: 5px;
        line-height: 1;
    }
    
    .card-orange .card-number {
        color: #f97316;
    }
    
    .card-blue .card-number {
        color: #3b82f6;
    }
    
    .card-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .card-orange .card-icon {
        color: #f97316;
        opacity: 0.8;
    }
    
    .card-blue .card-icon {
        color: #3b82f6;
        opacity: 0.8;
    }
    
    .card-title {
        font-size: 1.2rem;
        color: #555;
    }
    
    .card-orange .card-title {
        color: #f97316;
    }
    
    .card-blue .card-title {
        color: #3b82f6;
    }
    
    /* Calendar styles */
    .calendar-widget {
        background: #fff;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .cal-header {
        display: flex;
        justify-content: space-between;
        font-weight: 800;
        font-size: 1.4rem;
        margin-bottom: 20px;
        color: #333;
    }
    .cal-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-size: 0.75rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #555;
    }
    .cal-days span.red { color: #dc2626; }
    .cal-dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        gap: 5px 0;
    }
    .cal-dates div {
        padding: 6px 0;
        font-size: 1rem;
        color: #444;
    }
    .cal-dates div.red { color: #dc2626; }
    .cal-dates div.active {
        background: #4ade80;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        width: 30px;
        height: 30px;
        line-height: 18px;
        margin: 0 auto;
    }
    
    /* Bottom row */
    .bottom-row {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .table-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .table-title {
        font-size: 1.4rem;
        font-weight: bold;
        color: #333;
        margin: 0;
    }
    
    .badges {
        display: flex;
        gap: 15px;
    }
    
    .badge {
        background: #f3f4f6;
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 0.95rem;
        color: #666;
    }
    
    .badge strong {
        color: #111;
        font-size: 1.2rem;
        margin-left: 8px;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    
    .admin-table th, .admin-table td {
        border: 1px solid #e5e7eb;
        padding: 14px;
        text-align: center;
        color: #6b7280;
        font-size: 0.95rem;
    }
    
    .admin-table th {
        background-color: #fff;
        font-weight: 600;
        color: #4b5563;
    }
    
    .admin-table td:first-child {
        text-align: left;
        font-weight: 500;
        color: #4b5563;
    }

    @media (max-width: 992px) {
        .top-row { grid-template-columns: 1fr; }
        .table-header-container { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="admin-dashboard">
    <!-- TOP ROW -->
    <div class="top-row">
        <!-- Static Calendar -->
        <div class="calendar-widget">
            <div class="cal-header">
                <span>{{ $calMonthName }}</span>
                <span>{{ $calYear }}</span>
            </div>
            <div class="cal-days">
                <span class="red">SUN</span>
                <span>MON</span>
                <span>TUE</span>
                <span>WED</span>
                <span>THU</span>
                <span>FRI</span>
                <span>SAT</span>
            </div>
            <div class="cal-dates">
                @for ($i = 0; $i < $calFirstDayOfWeek; $i++)
                    <div></div>
                @endfor

                @for ($day = 1; $day <= $calDaysInMonth; $day++)
                    @php
                        // Hitung hari dalam minggu (0 = Minggu, 6 = Sabtu)
                        $currentDayOfWeek = ($i + $day - 1) % 7;
                        $isSunday = $currentDayOfWeek == 0;
                        $isActive = $day == $calToday;
                        
                        $classes = [];
                        if ($isSunday) $classes[] = 'red';
                        if ($isActive) $classes[] = 'active';
                        $classString = !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '';
                    @endphp
                    <div {!! $classString !!}>{{ $day }}</div>
                @endfor
                
                @php
                    // Isi sisa grid agar genap (opsional, tapi baik untuk layout flex/grid statis)
                    $totalCells = $calFirstDayOfWeek + $calDaysInMonth;
                    $remainingCells = 42 - $totalCells; // max 6 baris x 7 hari
                    if ($remainingCells >= 7 && $totalCells <= 35) {
                        $remainingCells -= 7;
                    }
                @endphp
                @for ($i = 0; $i < $remainingCells; $i++)
                    <div></div>
                @endfor
            </div>
        </div>
        
        <!-- Active Users Card -->
        <div class="card card-orange">
            <div class="card-number">{{ $penggunaAktifHarian }}</div>
            <div class="card-icon"><i class="fas fa-file-alt"></i></div>
            <div class="card-title">Pengguna Aktif Setiap hari nya</div>
        </div>
        
        <!-- Total Users Card -->
        <div class="card card-blue">
            <div class="card-number">{{ $totalPengguna }}</div>
            <div class="card-icon"><i class="fas fa-users"></i></div>
            <div class="card-title">Total pengguna</div>
        </div>
    </div>
    
    <!-- BOTTOM ROW -->
    <div class="bottom-row">
        <div class="table-header-container">
            <h3 class="table-title">Riwayat Laporan Hari ini</h3>
            <div class="badges">
                <div class="badge">Jumlah Laporan Harian <strong>{{ $jumlahLaporanHarian }}</strong></div>
                <div class="badge">Pengguna Belum Melapor <strong>{{ $penggunaBelumMelapor }}</strong></div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        @for($i = 1; $i <= $maxLaporan; $i++)
                            <th>Waktu {{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedRiwayat as $userRiwayat)
                        <tr>
                            <td>{{ $userRiwayat['nama'] }}</td>
                            @for($i = 0; $i < $maxLaporan; $i++)
                                <td>{{ $userRiwayat['waktu'][$i] ?? '' }}</td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $maxLaporan + 1 }}" style="text-align: center; padding: 30px; font-style: italic;">
                                Belum ada laporan hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection