<?php

namespace App\Http\Controllers;

use App\Models\User;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::with('role')->latest()->paginate(10);

        return view('pengguna.index', compact('pengguna'));
    }
}
