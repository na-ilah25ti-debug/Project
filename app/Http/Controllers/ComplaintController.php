<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Room;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('complaints.index', [
            'complaints' => Complaint::with('room.house')->orderByRaw("status = 'selesai'")->latest()->get(),
            'rooms' => Room::with('house')->orderBy('house_id')->orderBy('nomor')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Complaint::create($request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
        ]));

        return back()->with('ok', 'Laporan dicatat.');
    }

    // C3: status penanganan diterima > diproses > selesai
    public function update(Request $request, Complaint $complaint)
    {
        $complaint->update($request->validate(['status' => ['required', 'in:diterima,diproses,selesai']]));

        return back()->with('ok', 'Status laporan diperbarui.');
    }
}
