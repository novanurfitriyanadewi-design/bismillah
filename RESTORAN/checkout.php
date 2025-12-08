<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['nama_pemesan']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Nama pemesan atau pesanan masih kosong!'); window.location='menu_user.php';</script>";
    exit;
}

$nama = $_SESSION['nama_pemesan'];
$cart = $_SESSION['cart'];

$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}

// simpan ke tabel pesanan
mysqli_query($koneksi, "INSERT INTO pesanan (nama_pemesan, total_harga) VALUES ('$nama', '$total')");
$id_pesanan = mysqli_insert_id($koneksi);

// simpan ke tabel pesanan_detail
foreach ($cart as $item) {
    $id_menu = $item['id_menu'];
    $qty = $item['qty'];
    $subtotal = $item['harga'] * $qty;

    mysqli_query($koneksi, "INSERT INTO pesanan_detail (id_pesanan, id_menu, qty, subtotal)
                            VALUES ('$id_pesanan', '$id_menu', '$qty', '$subtotal')");
}

unset($_SESSION['cart']);
unset($_SESSION['nama_pemesan']);
?>
<!DOCTYPE html>
<html>
<body style="font-family: Arial; text-align: center; margin-top: 70px;">
    <h2 style="color:green;">✔ Pesanan Anda Akan Kami Proses</h2>
    <p>Silahkan pergi ke kasir dan lakukan pembayaran.</p>
    <p>Terima kasih telah memesan di restoran kami.</p>
    <br><br>
    <a href="login.php" class="btn btn-primary">Logout & Kembali ke Login</a>
</body>
</html>
