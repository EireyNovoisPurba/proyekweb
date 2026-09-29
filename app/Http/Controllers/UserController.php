<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsiparis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        // Memanggil semua user beserta relasi data arsiparisnya
        $users = User::with('arsiparis')->latest()->paginate(10);
        // Memanggil daftar nama arsiparis untuk form pilihan
        $daftarArsiparis = Arsiparis::orderBy('nama', 'asc')->get();

        return view('admin.users.index', compact('users', 'daftarArsiparis'));
    }
    public function store(Request $request)
 {
     $request->validate([
         'name'     => 'required|string|max:255',
         'email'    => 'required|string|email|max:255|unique:users',
         'password' => 'required|string|min:8',
         'role'     => 'required|string',
     ]);

     User::create([
         'name'         => $request->name,
         'email'        => $request->email,
         'password'     => Hash::make($request->password), // Password dienkripsi
         'role'         => $request->role,
         'arsiparis_id' => $request->arsiparis_id ?: null,
     ]);

     return back()->with('success', 'Akun pengguna baru berhasil dibuat!');
 }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'role' => $request->role,
            // Jika memilih opsi kosong, simpan sebagai null di database
            'arsiparis_id' => $request->arsiparis_id ?: null,
        ]);

        return back()->with('success', 'Hak akses dan identitas pengguna berhasil diperbarui!');
    }
}
