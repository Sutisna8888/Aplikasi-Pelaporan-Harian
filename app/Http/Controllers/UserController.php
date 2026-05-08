<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('username', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('id', 'asc')->get();

        return view('admin.kelola-pengguna', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'jabatan' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,pegawai',
            'ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ttdPath = null;
        if ($request->hasFile('ttd')) {
            $ttdPath = $request->file('ttd')->store('ttd', 'public');
        }

        User::create([
            'username' => $request->username,
            'nip' => $request->nip,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'ttd' => $ttdPath,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:users,nip,'.$user->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'jabatan' => 'nullable|string|max:255',
            'role' => 'required|in:admin,pegawai',
            'ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dataToUpdate = [
            'username' => $request->username,
            'nip' => $request->nip,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('ttd')) {
            if ($user->ttd && Storage::disk('public')->exists($user->ttd)) {
                Storage::disk('public')->delete($user->ttd);
            }
            $dataToUpdate['ttd'] = $request->file('ttd')->store('ttd', 'public');
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->ttd && Storage::disk('public')->exists($user->ttd)) {
            Storage::disk('public')->delete($user->ttd);
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
