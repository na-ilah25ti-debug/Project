<?php
// ============================================================
// FRONT CONTROLLER / ROUTER UTAMA APLIKASI
// Semua request masuk lewat file ini (index.php?aksi=xxx).
// Alur: index.php -> Controller -> Model -> Database -> View
// ============================================================

session_start(); // wajib di awal, dipakai untuk simpan status login (auth)

// load 3 controller: Resep (CRUD resep), Auth (login/register), Komentar (CRUD komentar)
require_once __DIR__ . '/controllers/ResepControllers.php';
require_once __DIR__ . '/controllers/AuthControllers.php';
require_once __DIR__ . '/controllers/KomentarControllers.php';


$controller = new ResepController(); 
$auth       = new AuthController();
$komentar   = new KomentarController();


// ambil parameter ?aksi= di URL, contoh: index.php?aksi=login
// kalau tidak ada, default tampilkan halaman utama (frontend)
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'frontend';

// -- PROTEKSI HALAMAN: harus login dulu baru boleh akses aksi-aksi ini --
$halaman_wajib_login = ['index', 'tambah', 'simpan', 'edit', 'update', 'hapus', 'simpanKomentar', 'editKomentar', 'updateKomentar', 'hapusKomentar'];
if (in_array($aksi, $halaman_wajib_login) && !isset($_SESSION['login'])) {
    header('Location: ?aksi=login'); // belum login -> tendang ke halaman login
    exit;
}

// -- PROTEKSI ROLE: hanya admin yang boleh buka halaman register/prosesRegister --
if (isset($_SESSION['login'])) {
    $role = $_SESSION['jabatan']; 

    $halaman_khusus_admin = [
        'register', 'prosesRegister'
    ];

    if (in_array($aksi, $halaman_khusus_admin) && $role !== 'admin') {
        header('Location: ?aksi=index&denied=1&role=' . urlencode($role));
        exit;
    }
}

// -- ROUTING: cocokkan nilai $aksi ke fungsi controller yang sesuai --
switch ($aksi) {
    // ---------- CRUD RESEP ----------
    case 'tambah': // buka form tambah resep
        $controller->tambah();
        break;

    case 'simpan': // proses simpan resep baru (submit form tambah) ke database
        $controller->simpan();
        break;

    case 'edit': // buka form edit, data lama diambil dari database via id
        $controller->edit();
        break;

    case 'update': // proses simpan perubahan resep (submit form edit) ke database
        $controller->update();
        break;

    case 'hapus': // hapus 1 resep berdasarkan id
        $controller->hapus();
        break;

    // ---------- CRUD KOMENTAR ----------
    case 'simpanKomentar': // proses simpan komentar baru pada sebuah resep
        $komentar->simpan();
        break;

    case 'editKomentar': // buka form edit komentar
        $komentar->edit();
        break;

    case 'updateKomentar': // proses simpan perubahan komentar
        $komentar->update();
        break;

    case 'hapusKomentar': // hapus komentar
        $komentar->hapus();
        break;

    // ---------- HALAMAN UTAMA (SETELAH LOGIN) ----------
    case 'index': // sama seperti frontend, tapi khusus untuk user yang sudah login
        $controller->index();
        break;

    // ---------- AUTENTIKASI ----------
    case 'login': // tampilkan form login
        $auth->login();
        break;

    case 'register': // tampilkan form register (khusus admin, lihat proteksi role di atas)
        $auth->register();
        break;

    case 'prosesLogin': // proses cek email + password dari form login
        $auth->prosesLogin();
        break;

    case 'prosesRegister': // proses simpan akun baru dari form register
        $auth->prosesRegister();
        break;

    case 'logout': // hancurkan session, kembali ke halaman login
        $auth->logout();
        break;

    // ---------- DEFAULT: halaman depan (daftar semua resep) ----------
    default:
        $controller->frontend();
        break;
}