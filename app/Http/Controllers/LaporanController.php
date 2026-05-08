<?php

namespace App\Http\Controllers;

use App\Models\InfoBps;
use App\Models\Kegiatan;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman Dashboard User dengan statistik dinamis.
     */
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $year = date('Y');

        $laporanHariIni = Laporan::where('user_id', $userId)->where('status', 'selesai')->whereDate('tanggal', Carbon::today())->count();
        $totalMenitHariIni = Laporan::where('user_id', $userId)->whereDate('tanggal', Carbon::today())->sum('durasi_menit');
        $penggunaanWaktu = sprintf('%02d:%02d', floor($totalMenitHariIni / 60), $totalMenitHariIni % 60);
        $laporanMingguIni = Laporan::where('user_id', $userId)->where('status', 'selesai')->whereBetween('tanggal', [Carbon::now()->startOfWeek()->format('Y-m-d'), Carbon::now()->endOfWeek()->format('Y-m-d')])->count();
        $laporanBulanIni = Laporan::where('user_id', $userId)->where('status', 'selesai')->whereMonth('tanggal', Carbon::now()->month)->whereYear('tanggal', Carbon::now()->year)->count();
        $laporanTahunIni = Laporan::where('user_id', $userId)->where('status', 'selesai')->whereYear('tanggal', Carbon::now()->year)->count();

        $laporans = Laporan::where('user_id', $userId)->where('status', 'selesai')->with('kegiatan')->orderBy('created_at', 'desc')->limit(5)->get();

        $monthlyData = Laporan::where('user_id', $userId)
            ->whereYear('tanggal', $year)
            ->where('status', 'selesai')
            ->selectRaw('MONTH(tanggal) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = $monthlyData[$m] ?? 0;
        }

        $infos = InfoBps::orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        return view('dashboard-user', compact(
            'laporans',
            'laporanHariIni',
            'penggunaanWaktu',
            'laporanMingguIni',
            'laporanBulanIni',
            'laporanTahunIni',
            'chartData',
            'infos'
        ));
    }

    /**
     * Halaman Riwayat: Menampilkan semua laporan hari ini.
     */
    public function history()
    {
        $userId = Auth::id();

        $laporans = Laporan::where('user_id', $userId)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'selesai')
            ->with('kegiatan')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        return view('laporan.riwayat', compact('laporans'));
    }

    /**
     * Menampilkan form input laporan.
     */
    public function create()
    {
        $userId = Auth::id();
        $kegiatans = Kegiatan::where('is_active', true)->get();

        $laporanAktif = Laporan::where('user_id', $userId)
            ->where('status', 'berjalan')
            ->first();

        return view('laporan.buat-laporan', compact('kegiatans', 'laporanAktif'));
    }

    /**
     * TAHAP 1: Menyimpan awal kegiatan (Klik Mulai).
     */
    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required',
            'deskripsi' => 'required',
            'foto_mulai_base64' => 'required|string',
        ]);

        $path_mulai = $this->saveBase64Image($request->foto_mulai_base64);

        Laporan::create([
            'user_id' => Auth::id(),
            'kegiatan_id' => $request->kegiatan_id,
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'jam_mulai' => Carbon::now()->format('H:i:s'),
            'deskripsi' => $request->deskripsi,
            'lokasi_teks' => $request->lokasi_teks ?? 'Kantor BPS Kota Sukabumi',
            'foto_mulai' => $path_mulai,
            'status' => 'berjalan',
        ]);

        return redirect()->route('dashboard')->with('success', 'Kegiatan dimulai! Data aman di server.');
    }

    /**
     * TAHAP 2: Mengupdate laporan saat selesai (Klik Selesai).
     */
    public function updateSelesai(Request $request, $id)
    {
        $request->validate([
            'foto_selesai_base64' => 'required|string',
        ]);

        $laporan = Laporan::findOrFail($id);

        $jam_mulai = Carbon::parse($laporan->jam_mulai);
        $jam_selesai = Carbon::now();
        $durasi = $jam_mulai->diffInMinutes($jam_selesai);

        $path_selesai = $this->saveBase64Image($request->foto_selesai_base64);

        $laporan->update([
            'jam_selesai' => $jam_selesai->format('H:i:s'),
            'foto_selesai' => $path_selesai,
            'durasi_menit' => $durasi,
            'status' => 'selesai',
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan kegiatan selesai!');
    }

    /**
     * Helper Method: Mendecode teks Base64 dan menyimpannya sebagai file.
     */
    private function saveBase64Image($base64String, $subfolder = 'foto_laporan')
    {
        if (! $base64String) {
            return null;
        }

        @[$type, $base64String] = explode(';', $base64String);
        @[, $base64String] = explode(',', $base64String);

        if (! $base64String) {
            return null;
        }

        $image = base64_decode($base64String);
        $fileName = time().'_'.uniqid().'.jpg';
        $path = public_path().'/storage/'.$subfolder.'/'.$fileName;

        File::makeDirectory(public_path().'/storage/'.$subfolder.'/', 0755, true, true);
        file_put_contents($path, $image);

        return $subfolder.'/'.$fileName;
    }
}
