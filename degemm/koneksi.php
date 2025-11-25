<?php
$konek = mysqli_connect("localhost", "root", "", "sekolahan");

if (!$konek) {
    echo "Koneksi database gagal!";
    exit;
}
?>
