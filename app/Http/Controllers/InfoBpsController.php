<?php

namespace App\Http\Controllers;

use App\Models\InfoBps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoBpsController extends Controller
{
    public function index()
    {
        $infos = InfoBps::orderBy('tanggal', 'desc')->get();
        return view('admin.info-bps', compact('infos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $path = $request->file('foto')->store('info_bps', 'public');

        InfoBps::create([
            'tanggal' => $request->tanggal,
            'judul' => $request->judul,
            'foto' => $path,
        ]);

        return redirect()->route('admin.info-bps.index')->with('success', 'Info berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $info = InfoBps::findOrFail($id);
        
        if (Storage::disk('public')->exists($info->foto)) {
            Storage::disk('public')->delete($info->foto);
        }
        
        $info->delete();

        return redirect()->route('admin.info-bps.index')->with('success', 'Info berhasil dihapus.');
    }
}
