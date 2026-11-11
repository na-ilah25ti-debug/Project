<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Message;
use App\Models\Photo;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicRoomController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'rumah' => ['nullable', 'integer', 'min:1'],
            'jenis' => ['nullable', 'in:putri,putra'],
            'status' => ['nullable', 'in:kosong,terisi,perawatan'],
            'tipe' => ['nullable', 'string', 'max:50'],
            'maks' => ['nullable', 'integer', 'min:0'],
        ]);

        $rooms = Room::with(['house', 'photos'])
            ->when(isset($filters['rumah']), fn ($query) => $query->where('house_id', $filters['rumah']))
            ->when(isset($filters['jenis']), fn ($query) => $query->whereHas('house', fn ($house) => $house->where('jenis', $filters['jenis'])))
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['tipe']), fn ($query) => $query->where('tipe', $filters['tipe']))
            ->when(isset($filters['maks']), fn ($query) => $query->where('harga', '<=', $filters['maks']))
            ->orderByRaw("case when status = 'kosong' then 0 else 1 end")
            ->orderBy('house_id')
            ->orderBy('nomor')
            ->paginate(6)
            ->appends(array_filter($filters, fn ($value) => $value !== null))
            ->fragment('kamar');

        return view('rooms.public', [
            'rooms' => $rooms,
            'houses' => House::withCount([
                'rooms as total_kamar',
                'rooms as kosong_kamar' => fn ($query) => $query->where('status', 'kosong'),
            ])->with('photos')->orderBy('id')->get(),
            'tipe' => Room::select('tipe')->distinct()->orderBy('tipe')->pluck('tipe'),
            'banner' => Photo::where('jenis', 'banner')->latest()->first(),
            'filterAktif' => collect($filters)->contains(fn ($value) => $value !== null),
        ]);
    }

    public function show(Room $room)
    {
        $room->load(['house.photos', 'photos']);

        return view('rooms.detail', compact('room'));
    }

    public function kirimPesan(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kontak' => ['required', 'string', 'regex:/^(?:08|628|\+628)[0-9]{7,12}$/'],
            'isi' => ['required', 'string', 'max:1000'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
        ], [
            'kontak.regex' => 'Isi nomor WhatsApp Indonesia dengan awalan 08, 62, atau +62 tanpa spasi.',
        ]);

        $room = isset($data['room_id']) ? Room::with('house')->find($data['room_id']) : null;
        $isi = $room ? $room->label . "\n\n" . $data['isi'] : $data['isi'];
        $kontak = ltrim($data['kontak'], '+');
        if (str_starts_with($kontak, '0')) {
            $kontak = '62' . substr($kontak, 1);
        }

        Message::create([
            'nama' => $data['nama'],
            'kontak' => $kontak,
            'isi' => $isi,
            'user_id' => $request->user()?->id,
        ]);

        return back()->with('ok', 'Pesan terkirim. Pemilik akan menghubungimu lewat nomor WhatsApp yang kamu isi.');
    }
}
