<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Message;
use App\Models\Photo;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicRoomController extends Controller
{
    // A1-A4: daftar kamar per rumah kos, status, harga, fasilitas, foto, dan filter
    public function index(Request $request)
    {
        $houses = House::query()
            ->withCount(['rooms as total_kamar', 'rooms as kosong_kamar' => fn ($q) => $q->where('status', 'kosong')])
            ->with(['photos', 'rooms' => function ($q) use ($request) {
                $q->with('photos')
                  ->when($request->filled('status'), fn ($x) => $x->where('status', $request->status))
                  ->when($request->filled('maks'), fn ($x) => $x->where('harga', '<=', (int) $request->maks))
                  ->when($request->filled('tipe'), fn ($x) => $x->where('tipe', $request->tipe))
                  ->orderBy('nomor');
            }])
            ->when($request->filled('rumah'), fn ($q) => $q->where('id', $request->rumah))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->jenis))
            ->orderBy('id')
            ->get()
            ->filter(fn ($h) => $h->rooms->isNotEmpty());

        // Pesan milik pengunjung ini (akun yang login, atau pesan yang dikirim di sesi browser ini)
        $pesanSaya = Message::query()
            ->where(function ($q) use ($request) {
                $q->whereIn('id', $request->session()->get('pesan_ids', []));
                if ($request->user()) {
                    $q->orWhere('user_id', $request->user()->id);
                }
            })
            ->latest()
            ->get();

        return view('rooms.public', [
            'pesanSaya' => $pesanSaya,
            'houses' => $houses,
            'semuaRumah' => House::orderBy('id')->get(),
            'tipe' => Room::select('tipe')->distinct()->pluck('tipe'),
            'banner' => Photo::where('jenis', 'banner')->latest()->first(),
        ]);
    }

    // Pesan dari pengunjung/penghuni untuk ibu kos (dibalas dari panel pemilik)
    public function kirimPesan(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'isi' => ['required', 'string', 'max:1000'],
        ]);

        $pesan = Message::create($data + ['user_id' => $request->user()?->id]);
        $request->session()->push('pesan_ids', $pesan->id);

        return back()->with('ok', 'Pesan terkirim. Ibu kos akan membalas lewat kontak yang kamu isi.');
    }
}
