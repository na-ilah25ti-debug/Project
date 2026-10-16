<?php


require_once __DIR__ . '/../config/koneksi.php';

class ResepModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll()
    {
        return mysqli_query($this->conn, "SELECT * FROM resep ORDER BY id DESC");
    }

   public function insert($nama, $kategori, $harga, $waktu, $rating, $bahan, $langkah, $gambar)
{
    $nama     = mysqli_real_escape_string($this->conn, $nama);
    $kategori = mysqli_real_escape_string($this->conn, $kategori);
    $bahan    = mysqli_real_escape_string($this->conn, $bahan);
    $langkah  = mysqli_real_escape_string($this->conn, $langkah);
    $harga    = (int) $harga;    
    $waktu    = (int) $waktu;
    $rating   = (float) $rating;
    $gambar   = mysqli_real_escape_string($this->conn, $gambar);

    $query = "INSERT INTO resep (Nama, Kategori, Harga, Waktu, Rating, Bahan, Langkah, Gambar)
              VALUES ('$nama', '$kategori', '$harga', '$waktu', '$rating', '$bahan', '$langkah', '$gambar')";
    return mysqli_query($this->conn, $query);
}

    
    public function getById($id)
    {
        $query = mysqli_query($this->conn, "SELECT * FROM resep WHERE id='$id'");
        return mysqli_fetch_assoc($query);
    }

    public function update($id, $nama, $kategori, $harga, $waktu, $rating, $bahan, $langkah, $gambar)
    {
        $query = "UPDATE resep SET
                    nama='$nama',
                    kategori='$kategori',
                    harga='$harga',
                    waktu='$waktu',
                    rating='$rating',
                    bahan='$bahan',
                    langkah='$langkah',
                    gambar='$gambar'
                  WHERE id='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function delete($id)
    {
        return mysqli_query($this->conn, "DELETE FROM resep WHERE id='$id'");
    }
}