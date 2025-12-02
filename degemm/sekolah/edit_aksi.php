<?php
// koneksi database
include 'koneksi.php';

// menangkap data yg dikirim dari form
$id     = $_POST['id'];
$nama   = $_POST['nama'];
$nis    = $_POST['nis'];
$alamat = $_POST['alamat'];

// update data ke database
$result = mysqli_query($koneksi, "UPDATE siswa SET nama='$nama', nis='$nis', alamat='$alamat' WHERE id='$id'");

// cek jika query gagal
if (!$result) {
    echo "Gagal mengupdate: " . mysqli_error($koneksi);
    exit;
}

// kembali ke index
header("location:index.php");
?>
