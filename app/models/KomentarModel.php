<?php

require_once __DIR__ . '/../config/koneksi.php';

class KomentarModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll()
    {
        return mysqli_query($this->conn, "SELECT * FROM komentar ORDER BY id DESC");
    }

    public function getByResepId($resep_id)
    {
        $resep_id = (int) $resep_id;
        $query = mysqli_query($this->conn, "SELECT * FROM komentar WHERE resep_id = '$resep_id' ORDER BY id DESC");
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }


    public function getById($id)
    {
        $id = (int) $id;
        $query = mysqli_query($this->conn, "SELECT * FROM komentar WHERE id = '$id'");
        return mysqli_fetch_assoc($query);
    }

    public function insert($resep_id, $nama, $isi, $rating = 5)
    {
        $resep_id = (int) $resep_id;
        $nama     = mysqli_real_escape_string($this->conn, $nama);
        $isi      = mysqli_real_escape_string($this->conn, $isi);
        $rating   = max(1, min(5, (int) $rating)); 

        $query = "INSERT INTO komentar (resep_id, nama, isi, rating, created_at)
                   VALUES ('$resep_id', '$nama', '$isi', '$rating', NOW())";
        return mysqli_query($this->conn, $query);
    }

    public function update($id, $isi, $rating = 5)
    {
        $id     = (int) $id;
        $isi    = mysqli_real_escape_string($this->conn, $isi);
        $rating = max(1, min(5, (int) $rating));

        $query = "UPDATE komentar SET isi = '$isi', rating = '$rating' WHERE id = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function delete($id)
    {
        $id = (int) $id;
        return mysqli_query($this->conn, "DELETE FROM komentar WHERE id = '$id'");
    }
}
