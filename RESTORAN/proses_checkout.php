<?php
session_start();
include 'koneksi.php';

// Ambil data dari form
$nama  = $_SESSION['nama_pemesan'];
$total = $_POST['total'];

// Simpan ke tabel pesanan
mysqli_query($koneksi, "INSERT INTO pesanan (nama_pemesan,total_harga)
                        VALUES ('$nama','$email', '$total')");

// Ambil id pesanan yang baru dibuat
$id_pesanan = mysqli_insert_id($koneksi);

// Simpan detail pesanan (pesanan dari session cart)
foreach ($_SESSION['cart'] as $id_menu => $item) {

    $qty = $item['qty'];
    $subtotal = $item['qty'] * $item['harga'];

    mysqli_query($koneksi, 
        "INSERT INTO pesanan_detail (id_pesanan, id_menu, qty, subtotal)
         VALUES ('$id_pesanan', '$id_menu', '$qty', '$subtotal')");
}

// Kosongkan cart
unset($_SESSION['cart']);


