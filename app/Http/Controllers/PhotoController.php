<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Photo;
use App\Models\Room;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index()
    {
        return view('photos.index', [
            'photos' => Photo::with(['house', 'room.house'])->latest()->get(),
            'houses' => House::orderBy('id')->get(),
            'rooms' => Room::with('house')->orderBy('house_id')->orderBy('nomor')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:banner,rumah,fasilitas,kamar'],
            'house_id' => ['nullable', 'exists:houses,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'keterangan' => ['nullable', 'string', 'max:150'],
            'foto' => ['required', 'array', 'min:1'],
            'foto.*' => ['image', 'max:4096'],
        ]);

        $houseId = null;
        $roomId = null;

        if ($data['jenis'] === 'kamar') {
            if (empty($data['room_id'])) {
                return back()->withErrors(['room_id' => 'Pilih kamar untuk foto kamar.'])->withInput();
            }
            $room = Room::findOrFail($data['room_id']);
            $roomId = $room->id;
            $houseId = $room->house_id;
        } elseif (in_array($data['jenis'], ['rumah', 'fasilitas'])) {
            if (empty($data['house_id'])) {
                return back()->withErrors(['house_id' => 'Pilih rumah kos.'])->withInput();
            }
            $houseId = $data['house_id'];
        }

        foreach ($request->file('foto') as $file) {
            Photo::create([
                'house_id' => $houseId,
                'room_id' => $roomId,
                'jenis' => $data['jenis'],
                'path' => $file->store('foto', 'public'),
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        }

        return back()->with('ok', count($request->file('foto')) . ' foto diunggah.');
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();

        return back()->with('ok', 'Foto dihapus.');
    }
}
