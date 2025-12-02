<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Cek database
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Simpan session user
        $_SESSION['user'] = $data;

        // Jika admin → pindah ke dashboard admin
        if ($data['role'] == "admin") {
            header("Location: admin/index.php");
            exit;
        }

        // Jika pembeli → pindah ke halaman menu pembeli
        if ($data['role'] == "pembeli") {
            header("Location: pembeli/menu.php");
            exit;
        }

    } else {
        echo "Username atau password salah!";
    }
}
?>
