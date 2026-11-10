<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        return view('tenants.index', [
            'tenants' => Tenant::with(['room.house', 'user'])->orderByDesc('aktif')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('tenants.create', ['rooms' => Room::with('house')->where('status', 'kosong')->orderBy('house_id')->orderBy('nomor')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'nama' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'tanggal_masuk' => ['required', 'date'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['nullable', 'required_with:email', 'string', 'min:6'],
        ]);

        // A8: status kamar berubah otomatis saat penghuni masuk
        DB::transaction(function () use ($data) {
            $room = Room::lockForUpdate()->findOrFail($data['room_id']);
            abort_if($room->status !== 'kosong', 422, 'Kamar tidak tersedia.');

            // Akun login untuk anak kos (opsional)
            $userId = null;
            if (! empty($data['email'])) {
                $userId = User::create([
                    'name' => $data['nama'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'role' => 'penghuni',
                ])->id;
            }

            Tenant::create([
                'room_id' => $data['room_id'],
                'user_id' => $userId,
                'nama' => $data['nama'],
                'no_hp' => $data['no_hp'],
                'tanggal_masuk' => $data['tanggal_masuk'],
            ]);
            $room->update(['status' => 'terisi']);
        });

        return redirect()->route('penghuni.index')->with('ok', 'Penghuni ditambahkan.');
    }

    public function checkout(Tenant $tenant)
    {
        // A8: status kamar kembali kosong saat penghuni keluar
        DB::transaction(function () use ($tenant) {
            $tenant->update(['aktif' => false, 'tanggal_keluar' => now()]);
            $tenant->room?->update(['status' => 'kosong']);
        });

        return back()->with('ok', 'Penghuni keluar, kamar kembali kosong.');
    }
}
