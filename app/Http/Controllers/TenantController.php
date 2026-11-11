<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        return view('tenants.create', [
            'rooms' => Room::with('house')->where('status', 'kosong')->orderBy('house_id')->orderBy('nomor')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'nama' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'tanggal_masuk' => ['required', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'max:72'],
        ]);

        DB::transaction(function () use ($data) {
            $room = Room::lockForUpdate()->findOrFail($data['room_id']);
            if ($room->status !== 'kosong') {
                throw ValidationException::withMessages(['room_id' => 'Kamar sudah tidak tersedia.']);
            }

            $userId = null;
            if (! empty($data['email'])) {
                $email = mb_strtolower($data['email']);
                $user = User::where('email', $email)->lockForUpdate()->first();
                if ($user && ($user->role !== 'penghuni' || $user->tenant()->exists())) {
                    throw ValidationException::withMessages(['email' => 'Email ini tidak bisa dihubungkan ke kamar.']);
                }
                if (! $user) {
                    if (empty($data['password'])) {
                        throw ValidationException::withMessages(['password' => 'Isi kata sandi sementara untuk membuat akun baru.']);
                    }
                    $user = User::create([
                        'name' => $data['nama'],
                        'email' => $email,
                        'password' => $data['password'],
                        'role' => 'penghuni',
                    ]);
                }
                $userId = $user->id;
            }

            Tenant::create([
                'room_id' => $room->id,
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
        $changed = DB::transaction(function () use ($tenant) {
            $room = Room::whereKey($tenant->room_id)->lockForUpdate()->first();
            $tenant = Tenant::lockForUpdate()->findOrFail($tenant->id);
            if (! $tenant->aktif) {
                return false;
            }

            $tenant->update(['aktif' => false, 'tanggal_keluar' => now()]);
            $room?->update(['status' => 'kosong']);

            return true;
        });

        return back()->with('ok', $changed ? 'Penghuni keluar, kamar kembali kosong.' : 'Penghuni sudah tercatat keluar.');
    }
}
