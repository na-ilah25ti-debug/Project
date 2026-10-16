<?php


require_once 'config/koneksi.php';

class AuthModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn; 
    }

    public function register($nama, $email, $password, $jabatan) 
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO user (nama, email, password, jabatan) VALUES ('$nama', '$email', '$hashed_password', '$jabatan')";
        return mysqli_query($this->conn, $query);
    }

    public function getByemail($email)
    {
        $query = mysqli_query($this->conn, "SELECT * FROM user WHERE email = '$email'");
        return mysqli_fetch_assoc($query);
    }

}