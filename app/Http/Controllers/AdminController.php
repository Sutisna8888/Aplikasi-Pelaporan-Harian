<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        $totalPengguna = User::where('role', 'pegawai')->count();

        $jumlahLaporanHarian = Laporan::whereDate('tanggal', $today)->count();

        $penggunaAktifHarian = Laporan::whereDate('tanggal', $today)
            ->distinct('user_id')
            ->count('user_id');

        $penggunaBelumMelapor = $totalPengguna - $penggunaAktifHarian;

        $riwayatLaporanRaw = Laporan::whereDate('tanggal', $today)
            ->with('user')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $groupedRiwayat = [];
        $maxLaporan = 0;

        foreach ($riwayatLaporanRaw as $laporan) {
            $userId = $laporan->user_id;
            $nama = $laporan->user->name ?? $laporan->user->username ?? 'User '.$userId;

            if (! isset($groupedRiwayat[$userId])) {
                $groupedRiwayat[$userId] = [
                    'nama' => $nama,
                    'waktu' => [],
                ];
            }

            $waktuString = substr($laporan->jam_mulai, 0, 5).' - '.
                          ($laporan->jam_selesai ? substr($laporan->jam_selesai, 0, 5).' WIB' : 'Sedang Berjalan');

            $groupedRiwayat[$userId]['waktu'][] = $waktuString;

            if (count($groupedRiwayat[$userId]['waktu']) > $maxLaporan) {
                $maxLaporan = count($groupedRiwayat[$userId]['waktu']);
            }
        }

        $maxLaporan = max(5, $maxLaporan);

        $now = now();
        $calMonthName = $now->format('F');
        $calYear = $now->format('Y');
        $calDaysInMonth = $now->daysInMonth;
        $calFirstDayOfWeek = $now->copy()->startOfMonth()->dayOfWeek;
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
