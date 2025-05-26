<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class BemController extends Controller
{
    public function dashboard()
    {
        // Ambil semua data gedung untuk ditampilkan
        $gedungs = Gedung::all(); 

        // Kirim ke view pages.bem.dashboard
        return view('pages.bem.dashboard', compact('gedungs'));
    }
}
