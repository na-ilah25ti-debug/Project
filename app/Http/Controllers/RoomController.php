<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    private function rules(Request $request, ?Room $room = null): array
    {
        return [
            'house_id' => ['required', 'exists:houses,id'],
            'nomor' => [
                'required', 'string', 'max:20',
                Rule::unique('rooms', 'nomor')->where('house_id', $request->house_id)->ignore($room?->id),
            ],
            'tipe' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'integer', 'min:0'],
            'fasilitas' => ['nullable', 'string'],
            'status' => ['required', 'in:kosong,terisi,perawatan'],
        ];
    }

    public function index()
    {
        return view('rooms.index', [
            'rooms' => Room::with(['house', 'activeTenant'])->orderBy('house_id')->orderBy('nomor')->get(),
        ]);
    }

    public function create()
    {
        return view('rooms.form', ['room' => new Room(['status' => 'kosong', 'tipe' => 'Kipas']), 'houses' => House::orderBy('id')->get()]);
    }

    public function store(Request $request)
    {
        Room::create($request->validate($this->rules($request)));

        return redirect()->route('kamar.index')->with('ok', 'Kamar ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('rooms.form', ['room' => $room, 'houses' => House::orderBy('id')->get()]);
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate($this->rules($request, $room));

        if ($room->status === 'terisi' && $data['status'] !== 'terisi') {
            return back()->withErrors(['status' => 'Kamar masih dihuni. Gunakan menu Penghuni > Keluar.'])->withInput();
        }

        $room->update($data);

        return redirect()->route('kamar.index')->with('ok', 'Kamar diperbarui.');
    }

    public function destroy(Room $room)
    {
        if ($room->status === 'terisi') {
            return back()->withErrors(['hapus' => 'Kamar yang sedang dihuni tidak bisa dihapus.']);
        }

        $room->delete();

        return back()->with('ok', 'Kamar dihapus.');
    }
}
