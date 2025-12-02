<?php
include 'koneksi.php';

$nama   = $_POST['nama'];
$nis    = $_POST['nis'];
$alamat = $_POST['alamat'];

$result = mysqli_query($koneksi, "INSERT INTO siswa VALUES('', '$nama', '$nis', '$alamat')");
if (!$result) {
    echo "Gagal menyimpan: " . mysqli_error($koneksi);
    exit;
}

header("location:index.php");
?>