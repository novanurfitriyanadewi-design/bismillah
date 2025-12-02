<?php
session_start();
include "koneksi.php";

$nama = $_POST["nama"];
$meja = $_POST["meja"];

$cart = $_SESSION["cart"];
$total = 0;

foreach ($cart as $id => $jml) {
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga FROM menu WHERE id='$id'"));
    $total += $m["harga"] * $jml;
}

mysqli_query($koneksi, "INSERT INTO pesanan (nama_pemesan, no_meja, total, tanggal)
VALUES ('$nama', '$meja', '$total', NOW())");

$id_pesanan = mysqli_insert_id($koneksi);

foreach ($cart as $id => $jml) {
    mysqli_query($koneksi, "INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah)
    VALUES ('$id_pesanan', '$id', '$jml')");
}

unset($_SESSION["cart"]);

header("Location: berhasil.php?id=$id_pesanan");
?>
