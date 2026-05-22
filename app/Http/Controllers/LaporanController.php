<?php

namespace App\Http\Controllers;

use App\Models\InfoBps;
use App\Models\Kegiatan;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman dashboard user.
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
     * Menampilkan riwayat laporan hari ini.
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
     * Menyimpan data awal kegiatan laporan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required',
            'deskripsi' => 'required',
            'foto_mulai_base64' => 'required|string',
        ]);

        $path_mulai = $this->saveBase64Image($request->foto_mulai_base64, 'foto_mulai_base64');

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

        return redirect()->route('laporan.create')->with('success', 'Kegiatan dimulai! Data aman di server.');
    }

    /**
     * Menyelesaikan laporan kegiatan.
     */
    public function updateSelesai(Request $request, $id)
    {
        $request->validate([
            'foto_selesai_base64' => 'required|string',
        ]);

        $laporan = Laporan::where('user_id', Auth::id())->findOrFail($id);

        $jam_mulai = Carbon::parse($laporan->jam_mulai);
        $jam_selesai = Carbon::now();
        $durasi = $jam_mulai->diffInMinutes($jam_selesai);

        $path_selesai = $this->saveBase64Image($request->foto_selesai_base64, 'foto_selesai_base64');

        $laporan->update([
            'jam_selesai' => $jam_selesai->format('H:i:s'),
            'foto_selesai' => $path_selesai,
            'durasi_menit' => $durasi,
            'status' => 'selesai',
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan kegiatan selesai!');
    }

    /**
     * Membatalkan kegiatan berjalan (menghapus laporan berjalan beserta fotonya).
     */
    public function destroy($id)
    {
        $laporan = Laporan::where('user_id', Auth::id())
            ->where('status', 'berjalan')
            ->findOrFail($id);

        // Hapus foto mulai dari disk storage
        if ($laporan->foto_mulai && Storage::disk('public')->exists($laporan->foto_mulai)) {
            Storage::disk('public')->delete($laporan->foto_mulai);
        }

        $laporan->delete();

        return redirect()->route('laporan.create')->with('success', 'Kegiatan berhasil dibatalkan!');
    }

    /**
     * Mendecode teks Base64 dan menyimpannya sebagai file gambar dengan validasi keamanan ketat.
     */
    private function saveBase64Image($base64String, $fieldName, $subfolder = 'foto_laporan')
    {
        if (!$base64String) {
            return null;
        }

        // 1. Validasi format URI data base64
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => ['Format foto tidak valid.'],
            ]);
        }

        $extension = strtolower($matches[1]);
        if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'webp'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => ['Ekstensi foto tidak diizinkan.'],
            ]);
        }

        @[, $base64Data] = explode(',', $base64String);
        if (!$base64Data) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => ['Data foto rusak.'],
            ]);
        }

        $image = base64_decode($base64Data);
        if ($image === false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => ['Gagal memproses unggahan foto.'],
            ]);
        }

        // 2. Batas ukuran berkas maks 7 MB (7 * 1024 * 1024 bytes)
        $maxSize = 7 * 1024 * 1024;
        if (strlen($image) > $maxSize) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => ['Ukuran file foto terlalu besar. Maksimal adalah 7 MB.'],
            ]);
        }

        // 3. Validasi Mime-Type asli untuk mencegah eksploitasi file upload
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($image);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => ['File harus berupa gambar (JPEG, PNG, GIF, atau WebP).'],
            ]);
        }

        $extMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        $fileExtension = $extMap[$mimeType] ?? 'jpg';

        $fileName = time().'_'.uniqid().'.'.$fileExtension;
        $filePath = $subfolder.'/'.$fileName;

        Storage::disk('public')->put($filePath, $image);

        return $filePath;
    }
}

