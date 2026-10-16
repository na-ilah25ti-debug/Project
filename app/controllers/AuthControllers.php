<?php

require_once __DIR__ . '/../models/AuthModel.php';

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new AuthModel();
    }

    
    public function login()
    {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        include __DIR__ . '/../views/auth/register.php';
    }

  public function prosesRegister()
{
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $jabatan = $_POST['jabatan'];
    $password = $_POST['password']; 

    $this->model->register($nama, $email, $password, $jabatan); 

    header('Location: ?aksi=login');
    exit;
}
    public function prosesLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $this->model->getByemail($email); 

        if ($user && password_verify($password, $user['password'])) { 
            $_SESSION['login'] = true;              
            $_SESSION['nama'] = $user['nama'];      
            $_SESSION['jabatan'] = strtolower($user['jabatan']);
            header('Location: index.php');
            exit;
        } else {
            echo "Email atau Password salah"; 
        }
    }

    public function logout()
    {
        session_destroy();

        header('Location: /Project/app/index.php?aksi=login');
        exit;
    }

}