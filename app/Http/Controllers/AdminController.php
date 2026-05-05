<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        // 1. Total Pengguna (Hanya Pegawai)
        $totalPengguna = \App\Models\User::where('role', 'pegawai')->count();
        
        // 2. Jumlah Laporan Harian
        $jumlahLaporanHarian = Laporan::whereDate('tanggal', $today)->count();
        
        // 3. Pengguna Aktif Harian
        $penggunaAktifHarian = Laporan::whereDate('tanggal', $today)
                                ->distinct('user_id')
                                ->count('user_id');
                                
        // 4. Pengguna Belum Melapor
        $penggunaBelumMelapor = $totalPengguna - $penggunaAktifHarian;
        
        // 5. Data Tabel (User dan Riwayat Laporannya Hari ini)
        $riwayatLaporanRaw = Laporan::whereDate('tanggal', $today)
            ->with('user')
            ->orderBy('jam_mulai', 'asc')
            ->get();
            
        // Mengelompokkan laporan berdasarkan user_id
        $groupedRiwayat = [];
        $maxLaporan = 0; // Untuk menentukan jumlah maksimal kolom "Waktu"
        
        foreach ($riwayatLaporanRaw as $laporan) {
            $userId = $laporan->user_id;
            $nama = $laporan->user->name ?? $laporan->user->username ?? 'User ' . $userId;
            
            if (!isset($groupedRiwayat[$userId])) {
                $groupedRiwayat[$userId] = [
                    'nama' => $nama,
                    'waktu' => []
                ];
            }
            
            // Format waktu
            $waktuString = substr($laporan->jam_mulai, 0, 5) . ' - ' . 
                          ($laporan->jam_selesai ? substr($laporan->jam_selesai, 0, 5) . ' WIB' : 'Sedang Berjalan');
                          
            $groupedRiwayat[$userId]['waktu'][] = $waktuString;
            
            if (count($groupedRiwayat[$userId]['waktu']) > $maxLaporan) {
                $maxLaporan = count($groupedRiwayat[$userId]['waktu']);
            }
        }
        
        // Pastikan minimal ada 5 kolom seperti di desain jika datanya kurang dari 5
        $maxLaporan = max(5, $maxLaporan);
        
        // 6. Data Kalender Statis Dinamis (Otomatis menyesuaikan bulan berjalan)
        $now = now();
        $calMonthName = $now->format('F');
        $calYear = $now->format('Y');
        $calDaysInMonth = $now->daysInMonth;
        $calFirstDayOfWeek = $now->copy()->startOfMonth()->dayOfWeek; // 0 (Sunday) - 6 (Saturday)
        $calToday = $now->day;

        return view('dashboard-admin', compact(
            'totalPengguna', 
            'jumlahLaporanHarian', 
            'penggunaAktifHarian', 
            'penggunaBelumMelapor',
            'groupedRiwayat',
            'maxLaporan',
            'calMonthName',
            'calYear',
            'calDaysInMonth',
            'calFirstDayOfWeek',
            'calToday'
        ));
    }
}
