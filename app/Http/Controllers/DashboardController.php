<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\GalonOrder;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Room;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $periode = now()->format('Y-m');

        return view('dashboard', [
            'total' => Room::count(),
            'kosong' => Room::where('status', 'kosong')->count(),
            'terisi' => Room::where('status', 'terisi')->count(),
            'perawatan' => Room::where('status', 'perawatan')->count(),
            'pemasukan' => Payment::where('periode', $periode)->where('status', 'lunas')->sum('jumlah'),
            'tunggakan' => Payment::whereIn('status', ['belum', 'menunggu'])->sum('jumlah'),
            'menunggu' => Payment::where('status', 'menunggu')->count(),
            'galonBaru' => GalonOrder::where('status', 'dipesan')->count(),
            'pesanBaru' => Message::where('dibaca', false)->count(),
            'terlambat' => Payment::with('tenant.room.house')
                ->where('status', 'belum')
                ->whereDate('jatuh_tempo', '<', now())
                ->orderBy('jatuh_tempo')
                ->get(),
            'laporan' => Complaint::with('room.house')->where('status', '!=', 'selesai')->latest()->take(5)->get(),
        ]);
    }
}
