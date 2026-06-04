<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'asc')->get();

        return view('admin.kelola-pengguna', compact('users'));
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\,\'\`\-]+$/'],
            'nip' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:5', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'jabatan' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\,\/\-]+$/'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,pegawai'],
            'ttd' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'username.required' => 'Nama Lengkap wajib diisi.',
            'username.regex' => 'Nama Lengkap hanya boleh berisi huruf, angka, spasi, titik, koma, dan tanda kutip.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.regex' => 'NIP harus berupa angka.',
            'nip.min' => 'NIP minimal harus berisi 5 karakter.',
            'nip.max' => 'NIP maksimal harus berisi 20 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jabatan.regex' => 'Jabatan hanya boleh berisi huruf, angka, spasi, titik, koma, garis miring, dan strip.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus berisi 8 karakter.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role tidak valid.',
            'ttd.image' => 'Tanda tangan harus berupa gambar.',
            'ttd.mimes' => 'Tanda tangan harus berupa file dengan tipe: jpeg, png, jpg, gif.',
            'ttd.max' => 'Ukuran tanda tangan maksimal 2048 KB.',
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

    /**
     * Memperbarui data pengguna.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\,\'\`\-]+$/'],
            'nip' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:5', 'max:20', 'unique:users,nip,'.$user->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'jabatan' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\,\/\-]+$/'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,pegawai'],
            'ttd' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'username.required' => 'Nama Lengkap wajib diisi.',
            'username.regex' => 'Nama Lengkap hanya boleh berisi huruf, angka, spasi, titik, koma, dan tanda kutip.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.regex' => 'NIP harus berupa angka.',
            'nip.min' => 'NIP minimal harus berisi 5 karakter.',
            'nip.max' => 'NIP maksimal harus berisi 20 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jabatan.regex' => 'Jabatan hanya boleh berisi huruf, angka, spasi, titik, koma, garis miring, dan strip.',
            'password.min' => 'Password minimal harus berisi 8 karakter.',
            'role.in' => 'Role tidak valid.',
            'ttd.image' => 'Tanda tangan harus berupa gambar.',
            'ttd.mimes' => 'Tanda tangan harus berupa file dengan tipe: jpeg, png, jpg, gif.',
            'ttd.max' => 'Ukuran tanda tangan maksimal 2048 KB.',
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

    /**
     * Menghapus pengguna.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->ttd && Storage::disk('public')->exists($user->ttd)) {
            Storage::disk('public')->delete($user->ttd);
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Mencari pengguna berdasarkan nama atau NIP.
     */
    public function searchAllUsers(Request $request)
    {
        $q = $request->input('q');
        $users = User::where('username', 'LIKE', "%{$q}%")
            ->orWhere('nip', 'LIKE', "%{$q}%")
            ->select('username', 'nip')
            ->limit(7)
            ->get();
            
        return response()->json($users);
    }
}
