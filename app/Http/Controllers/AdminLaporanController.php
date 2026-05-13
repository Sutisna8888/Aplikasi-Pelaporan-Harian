<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    /**
     * Menampilkan halaman Kelola Laporan Admin
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan'); 
        $tanggal = $request->input('tanggal');

        $searchedUser = null;
        $laporans = [];

        if ($search) {
            $searchedUser = User::where('role', 'pegawai')
                ->where(function ($q) use ($search) {
                    $q->where('username', $search)
                        ->orWhere('nip', $search);
                })->first();

            if ($searchedUser && $tanggal) {
                $laporans = Laporan::with('kegiatan')
                    ->where('user_id', $searchedUser->id)
                    ->where('status', 'selesai')
                    ->whereDate('tanggal', $tanggal)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('jam_mulai', 'desc')
                    ->get();
            }
        }

        return view('admin.kelola-laporan', compact('searchedUser', 'laporans', 'search', 'bulan', 'tanggal'));
    }
    public function downloadRekap(Request $request)
    {
        $userId = $request->input('user_id');
        $bulan = $request->input('bulan');
        $tanggal = $request->input('tanggal');
        $tipe = $request->input('tipe'); 
        $user = User::findOrFail($userId);
        $query = Laporan::with('kegiatan')->where('user_id', $user->id)->where('status', 'selesai');

        $periode = '';
        if ($tipe === 'harian' && $tanggal) {
            $query->whereDate('tanggal', $tanggal);
            $periode = Carbon::parse($tanggal)->translatedFormat('d F Y');
            $judul = 'LAPORAN HARIAN';
        } elseif ($tipe === 'bulanan' && $bulan) {
            $parts = explode('-', $bulan);
            if (count($parts) == 2) {
                $query->whereYear('tanggal', $parts[0])->whereMonth('tanggal', $parts[1]);
                $periode = Carbon::parse($bulan.'-01')->translatedFormat('F Y');
            }
            $judul = 'LAPORAN BULANAN';
        } else {
            return redirect()->back();
        }

        $laporans = $query->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc')->get();

        $pdf = Pdf::loadView('admin.pdf-rekap', compact('user', 'laporans', 'judul', 'periode'));

        return $pdf->download('Rekap_'.$tipe.'_'.$user->username.'_'.time().'.pdf');
    }

    public function downloadIndividu($id)
    {
        $laporan = Laporan::with(['user', 'kegiatan'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.pdf-individu', compact('laporan'));

        return $pdf->download('Bukti_Foto_'.$laporan->user->username.'_'.Carbon::parse($laporan->tanggal)->format('Ymd').'.pdf');
    }

    public function searchUsers(Request $request)
    {
        $q = $request->input('q');
        $users = User::where('role', 'pegawai')
            ->where(function($query) use ($q) {
                $query->where('username', 'LIKE', "%{$q}%")
                      ->orWhere('nip', 'LIKE', "%{$q}%");
            })
            ->select('username', 'nip')
            ->limit(7)
            ->get();
            
        return response()->json($users);
    }
}
