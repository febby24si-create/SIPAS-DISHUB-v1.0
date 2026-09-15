<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Pegawai;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::with(['role', 'pegawai'])->latest()->paginate(10);
        return view('pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        $roles = Role::all();
        // Hanya ambil pegawai yang belum terhubung dengan user lain
        $pegawais = Pegawai::whereDoesntHave('user')->where('status_aktif', true)->get();
        
        return view('pengguna.create', compact('roles', 'pegawais'));
    }

    public function store(StorePenggunaRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('pengguna.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        $roles = Role::all();
        
        // Ambil pegawai yang belum terhubung ATAU yang saat ini terhubung dengan user ini
        $pegawais = Pegawai::where('status_aktif', true)
            ->where(function ($query) use ($pengguna) {
                $query->whereDoesntHave('user')
                      ->orWhere('id', $pengguna->pegawai_id);
            })->get();
            
        return view('pengguna.edit', compact('pengguna', 'roles', 'pegawais'));
    }

    public function update(UpdatePenggunaRequest $request, User $pengguna)
    {
        // Proteksi role admin
        if ($pengguna->id === auth()->id() && $request->role_id != $pengguna->role_id) {
            $newRole = Role::find($request->role_id);
            if ($newRole && $newRole->name !== 'admin') {
                return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri menjadi non-admin.');
            }
        }

        $pengguna->update($request->validated());

        return redirect()->route('pengguna.index')->with('status', 'Data pengguna berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $pengguna)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $pengguna->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status', 'Password pengguna berhasil direset.');
    }

    public function toggleStatus(User $pengguna)
    {
        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $pengguna->update([
            'is_active' => !$pengguna->is_active
        ]);

        $statusText = $pengguna->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('status', "Akun pengguna berhasil $statusText.");
    }
}
