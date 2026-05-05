<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;

class AdminLaporanController extends Controller
{
    /**
     * Menampilkan halaman Kelola Laporan Admin
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan'); // Format YYYY-MM
        $tanggal = $request->input('tanggal'); // Format YYYY-MM-DD
        
        $searchedUser = null;
        $laporans = [];

        if ($search) {
            // Cari pengguna berdasarkan username atau nip
            $searchedUser = User::where('role', 'pegawai')
                ->where(function($q) use ($search) {
                    $q->where('username', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%");
                })->first();

            if ($searchedUser) {
                // Ambil laporan milik pengguna ini
                $query = Laporan::with('kegiatan')
                    ->where('user_id', $searchedUser->id)
                    ->where('status', 'selesai'); // Hanya tampilkan yang sudah selesai

                // Terapkan filter hanya berdasarkan tanggal
                if ($tanggal) {
                    $query->whereDate('tanggal', $tanggal);
                }

                $laporans = $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'desc')->get();
            }
        }
        
        return view('admin.kelola-laporan', compact('searchedUser', 'laporans', 'search', 'bulan', 'tanggal'));
    }

    public function downloadRekap(Request $request)
    {
        $userId = $request->input('user_id');
        $bulan = $request->input('bulan'); 
        $tanggal = $request->input('tanggal');
        $tipe = $request->input('tipe'); // 'harian' atau 'bulanan'

        $user = User::findOrFail($userId);
        $query = Laporan::with('kegiatan')->where('user_id', $user->id)->where('status', 'selesai');

        $periode = '';
        if ($tipe === 'harian' && $tanggal) {
            $query->whereDate('tanggal', $tanggal);
            $periode = \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
            $judul = 'LAPORAN HARIAN';
        } elseif ($tipe === 'bulanan' && $bulan) {
            $parts = explode('-', $bulan);
            if (count($parts) == 2) {
                $query->whereYear('tanggal', $parts[0])->whereMonth('tanggal', $parts[1]);
                $periode = \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y');
            }
            $judul = 'LAPORAN BULANAN';
        } else {
            return redirect()->back();
        }

        $laporans = $query->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf-rekap', compact('user', 'laporans', 'judul', 'periode'));
        
        return $pdf->download('Rekap_' . $tipe . '_' . $user->username . '_' . time() . '.pdf');
    }

    public function downloadIndividu($id)
    {
        $laporan = Laporan::with(['user', 'kegiatan'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf-individu', compact('laporan'));
        
        return $pdf->download('Bukti_Foto_' . $laporan->user->username . '_' . \Carbon\Carbon::parse($laporan->tanggal)->format('Ymd') . '.pdf');
    }
}
