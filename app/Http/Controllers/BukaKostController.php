<?php

namespace App\Http\Controllers;

use App\Models\KosApplication;
use Illuminate\Http\Request;

// Pengajuan dari versi lama tetap bisa ditinjau oleh pemilik.
class BukaKostController extends Controller
{
    public function index()
    {
        return view('buka-kost.index', ['items' => KosApplication::orderByRaw("status = 'baru' desc")->latest()->get()]);
    }

    public function update(Request $request, KosApplication $application)
    {
        $application->update($request->validate(['status' => ['required', 'in:baru,disetujui,ditolak']]));

        return back()->with('ok', 'Status pengajuan diperbarui.');
    }
}
