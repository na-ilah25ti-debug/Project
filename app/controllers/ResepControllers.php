<?php

require_once __DIR__ . '/../models/ResepModel.php';

class ResepController
{
    private $model;

    public function __construct()
    {
        $this->model = new ResepModel(); 
    }

    public function index()
    {
        $this->frontend();
    }

    public function tambah()
    {
        include __DIR__ . '/../views/Home/tambah.php';
    }

    public function simpan()
    {
        $nama     = $_POST['nama'];
        $kategori = $_POST['kategori'];
        $harga    = $_POST['harga'];
        $waktu    = $_POST['waktu'];
        $rating   = $_POST['rating'];
        $bahan    = $_POST['bahan'];
        $langkah  = $_POST['langkah'];

        $gambar = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $ext        = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $namaFile   = time() . '_' . uniqid() . '.' . $ext; // nama file dibuat unik biar tidak bentrok
            $uploadDir  = __DIR__ . '/../views/Home/images/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uploadPath = $uploadDir . $namaFile;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
                $gambar = $namaFile; 
            }
        }

        
        $this->model->insert(
            $nama,
            $kategori,
            $harga,
            $waktu,
            $rating,
            $bahan,
            $langkah,
            $gambar
        );

        header('Location:index.php'); 
    }

    
    public function edit()
    {
        $id = $_GET['id'];
        $resep = $this->model->getById($id);
        include __DIR__ . '/../views/Home/edit.php';
    }

    
    public function update()
    {
        $id       = $_POST['id'];
        $nama     = $_POST['nama'];
        $kategori = $_POST['kategori'];
        $harga    = $_POST['harga'];
        $waktu    = $_POST['waktu'];
        $rating   = $_POST['rating'];
        $bahan    = $_POST['bahan'];
        $langkah  = $_POST['langkah'];

        $gambar = $_POST['gambar_lama'] ?? '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $ext        = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $namaFile   = time() . '_' . uniqid() . '.' . $ext;
            $uploadDir  = __DIR__ . '/../views/Home/images/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uploadPath = $uploadDir . $namaFile;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
                $gambar = $namaFile;
            }
        }

        $this->model->update(
            $id,
            $nama,
            $kategori,
            $harga,
            $waktu,
            $rating,
            $bahan,
            $langkah,
            $gambar
        );

        header('Location:index.php');
    }

    public function hapus()
    {
        $id = $_GET['id'];
        $this->model->delete($id);
        header('Location:index.php');
    }

    public function frontend()
    {
        require_once __DIR__ . '/../models/KomentarModel.php';
        $resep = $this->model->getAll();          
        $komentarModel = new KomentarModel();
        $komentarRaw = $komentarModel->getAll();   
        include __DIR__ . '/../views/Home/home.php'; 
    }
}
