<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    /**
     * Update the user's profile information.
     */
    public function update(\App\Http\Requests\ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // --- TAMBAHAN KODE UPLOAD FOTO PROFIL ---
        if ($request->hasFile('foto_profil')) {
            // Validasi foto (Maks 2MB, format gambar)
            $request->validate([
                'foto_profil' => 'image|mimes:jpeg,png,jpg|max:2048'
            ], [
                'foto_profil.max' => 'Ukuran foto maksimal adalah 2MB agar loading tetap cepat.',
                'foto_profil.image' => 'File harus berupa gambar.',
                'foto_profil.mimes' => 'Format gambar harus jpeg, png, atau jpg.'
            ]);

            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->hashName();

            // Pindahkan file ke folder public/foto_profil
            $file->move(public_path('foto_profil'), $filename);

            // Hapus foto fisik yang lama (jika sebelumnya sudah pernah upload foto)
            if ($request->user()->foto_profil && file_exists(public_path('foto_profil/' . $request->user()->foto_profil))) {
                @unlink(public_path('foto_profil/' . $request->user()->foto_profil));
            }

            // Simpan nama file foto baru ke database
            $request->user()->foto_profil = $filename;
        }
        // --- AKHIR TAMBAHAN KODE UPLOAD FOTO ---

        $request->user()->save();

        return \Illuminate\Support\Facades\Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
