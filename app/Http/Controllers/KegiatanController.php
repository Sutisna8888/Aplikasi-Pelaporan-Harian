<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::orderBy('created_at', 'desc')->get();

        return view('admin.kelola-kegiatan', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255|unique:kegiatans,nama_kegiatan',
        ]);

        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.edit-kegiatan', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255|unique:kegiatans,nama_kegiatan,'.$kegiatan->id,
        ]);

        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diupdate');
    }

    public function toggleActive(Kegiatan $kegiatan)
    {
        $kegiatan->update([
            'is_active' => ! $kegiatan->is_active,
        ]);

        $status = $kegiatan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.kegiatan.index')->with('success', "Kegiatan berhasil {$status}");
    }
}
