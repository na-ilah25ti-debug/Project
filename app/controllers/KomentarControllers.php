<?php

require_once __DIR__ . '/../models/KomentarModel.php';

class KomentarController
{
    private $model;

    public function __construct()
    {
        $this->model = new KomentarModel();
    }

    public function simpan()
    {
        $resep_id = $_POST['resep_id'];
        $nama     = $_SESSION['nama'] ?? 'Anonim'; 
        $isi      = trim($_POST['isi']);
        $rating   = isset($_POST['rating']) ? (int) $_POST['rating'] : 5; 

        if ($isi !== '') { 
            $this->model->insert($resep_id, $nama, $isi, $rating);
        }

        header('Location: index.php?aksi=index#resep=' . $resep_id); 
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $komentar = $this->model->getById($id);
        include __DIR__ . '/../views/Komentar/edit.php';
    }

    public function update()
    {
        $id     = $_POST['id'];
        $isi    = trim($_POST['isi']);
        $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 5;

        $lama = $this->model->getById($id);      
        $resep_id = $lama['resep_id'] ?? '';      

        $this->model->update($id, $isi, $rating);

        header('Location: index.php?aksi=index#resep=' . $resep_id);
        exit;
    }

    public function hapus()
    {
        $id = $_GET['id'];

        $lama = $this->model->getById($id);
        $resep_id = $lama['resep_id'] ?? '';

        $this->model->delete($id);

        header('Location: index.php?aksi=index#resep=' . $resep_id);
        exit;
    }
}
