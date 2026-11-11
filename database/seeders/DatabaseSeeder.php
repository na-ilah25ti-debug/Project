<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $umum = 'Parkir, CCTV';
        $saka = House::firstOrCreate(['nama' => 'Saka Kost'], ['jenis' => 'putri', 'fasilitas_umum' => $umum]);
        $syariah = House::firstOrCreate(['nama' => 'Kos Syariah'], ['jenis' => 'putri', 'fasilitas_umum' => $umum]);
        $cowok = House::firstOrCreate(['nama' => 'Kos Cowok'], ['jenis' => 'putra', 'fasilitas_umum' => $umum]);

        // Saka Kost: 8 kamar (4 kipas Rp700 ribu, 4 AC Rp1 juta)
        $sisa = 'kasur, lemari, WC dalam, listrik token, WiFi, dapur bersama';
        for ($i = 1; $i <= 8; $i++) {
            $ac = $i > 4;
            Room::firstOrCreate(['house_id' => $saka->id, 'nomor' => sprintf('%02d', $i)], [
                'tipe' => $ac ? 'AC' : 'Kipas',
                'harga' => $ac ? 1000000 : 700000,
                'fasilitas' => ($ac ? 'AC, ' : 'Kipas, ') . $sisa,
            ]);
        }

        // Kos Syariah: 6 kamar (Rp400 ribu - Rp500 ribu)
        for ($i = 1; $i <= 6; $i++) {
            Room::firstOrCreate(['house_id' => $syariah->id, 'nomor' => sprintf('%02d', $i)], [
                'tipe' => 'Kipas',
                'harga' => $i <= 3 ? 400000 : 500000,
                'fasilitas' => 'Kipas, kasur, lemari, WC luar/bersama, dapur bersama',
            ]);
        }

        // Kos Cowok: 10 kamar (Rp700 ribu)
        for ($i = 1; $i <= 10; $i++) {
            Room::firstOrCreate(['house_id' => $cowok->id, 'nomor' => sprintf('%02d', $i)], [
                'tipe' => 'Kipas',
                'harga' => 700000,
                'fasilitas' => 'Kasur, lemari, kipas, WC dalam',
            ]);
        }

    }
}
