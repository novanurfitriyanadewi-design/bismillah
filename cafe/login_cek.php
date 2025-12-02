<?php
session_start();
include "koneksi.php";

$user = $_POST["username"];
$pass = $_POST["password"];

$q = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
$d = mysqli_fetch_array($q);

if ($d) {
    $_SESSION["admin"] = $d["username"];
    header("Location: admin_pesanan.php");
} else {
    echo "Login gagal";
}
?>
