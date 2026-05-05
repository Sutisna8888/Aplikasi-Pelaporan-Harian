<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Tampilkan semua secara memanjang ke bawah (tanpa pagination per request user)
        $users = $query->orderBy('id', 'asc')->get();

        return view('admin.kelolaPengguna', compact('users'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nip'      => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'jabatan'  => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,pegawai',
            'ttd'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ttdPath = null;
        if ($request->hasFile('ttd')) {
            $ttdPath = $request->file('ttd')->store('ttd', 'public');
        }

        User::create([
            'username' => $request->username,
            'nip'      => $request->nip,
            'email'    => $request->email,
            'jabatan'  => $request->jabatan,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'ttd'      => $ttdPath,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'nip'      => 'required|string|max:255|unique:users,nip,'.$user->id,
            'email'    => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'jabatan'  => 'nullable|string|max:255',
            'role'     => 'required|in:admin,pegawai',
            'ttd'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dataToUpdate = [
            'username' => $request->username,
            'nip'      => $request->nip,
            'email'    => $request->email,
            'jabatan'  => $request->jabatan,
            'role'     => $request->role,
        ];

        // Jika password diisi, update (harus dienkripsi)
        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        // Jika ada file TTD baru yang diupload
        if ($request->hasFile('ttd')) {
            // Hapus file lama jika ada
            if ($user->ttd && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->ttd)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->ttd);
            }
            $dataToUpdate['ttd'] = $request->file('ttd')->store('ttd', 'public');
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus file TTD jika ada
        if ($user->ttd && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->ttd)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->ttd);
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
