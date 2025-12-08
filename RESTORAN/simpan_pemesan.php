<?php
session_start();
include 'koneksi.php';

if (isset($_POST['checkout'])) {

    $nama_pemesan = $_SESSION['nama_pemesan'];
    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['subtotal'];
    }

    // Simpan ke tabel pesanan
    mysqli_query($koneksi, "INSERT INTO pesanan (nama_pemesan, total, tanggal) 
    VALUES ('$nama_pemesan', $total, NOW())");

    // Ambil id pesanan
    $id_pesanan = mysqli_insert_id($koneksi);

    // Simpan ke detail pesanan
    foreach ($_SESSION['cart'] as $item) {
        mysqli_query($koneksi, "INSERT INTO pesanan_detail
        (id_pesanan, nama_menu, harga, qty, subtotal)
        VALUES (
            $id_pesanan,
            '{$item['nama']}',
            {$item['harga']},
            {$item['qty']},
            {$item['subtotal']}
        )");
    }

    unset($_SESSION['cart']); // kosongkan keranjang

    echo "<script>alert('Pesanan berhasil disimpan!'); location='pesanan_user.php';</script>";
}
?>
