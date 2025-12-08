<?php
session_start();
include "koneksi.php";

$pesanan_selesai = false;

// === KETIKA TOMBOL CHECKOUT DITEKAN ===
if (isset($_POST['checkout'])) {

    if (!empty($_SESSION['cart']) && !empty($_SESSION['nama_pemesan'])) {

        $nama_pemesan = $_SESSION['nama_pemesan'];
        $total = $_POST['total'];

        // SIMPAN PESANAN
        mysqli_query($koneksi, 
        "INSERT INTO pesanan (nama_pemesan, total_harga, tgl_pesan)
         VALUES ('$nama_pemesan', '$total', NOW())");

        $id_pesanan = mysqli_insert_id($koneksi);

        // SIMPAN DETAIL PESANAN
        foreach ($_SESSION['cart'] as $item) {
            $id_menu = $item['id_menu'];
            $qty = $item['qty'];
            $subtotal = $item['subtotal'];

            mysqli_query($koneksi,
            "INSERT INTO pesanan_detail (id_pesanan, id_menu, qty, subtotal)
             VALUES ('$id_pesanan', '$id_menu', '$qty', '$subtotal')");
        }

        // HAPUS KERANJANG
        unset($_SESSION['cart']);

        $pesanan_selesai = true;
    }
}
if (isset($_POST['tambah_cart'])) {
    $item = [
        'id_menu' => $_POST['id_menu'],
        'nama'    => $_POST['nama'],
        'harga'   => $_POST['harga'],
        'qty'     => $_POST['qty'],
        'sajian'  => isset($_POST['sajian']) ? $_POST['sajian'] : '-',
        'subtotal'=> $_POST['harga'] * $_POST['qty']
    ];

    $_SESSION['cart'][] = $item;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang Pesanan</title>

<!-- CSS TIDAK DIUBAH -->
<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
    h2 { text-align: center; font-size: 28px; font-weight: bold; margin-bottom: 10px; }
    h3 { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    th { background: #198754; color: white; padding: 12px; text-align: center; }
    td { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
    tr:hover { background-color: #f2f2f2; }
    .btn-danger { padding: 6px 12px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; }
    .btn-danger:hover { background: #b02a37; }
    .total-box { margin-top: 20px; background: #ffc107; padding: 14px; border-radius: 10px; font-size: 20px; font-weight: bold; text-align: center; width: 50%; margin-left: auto; margin-right: auto; box-shadow: 0 3px 8px rgba(0,0,0,0.15); }
    .btn-primary { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 8px; cursor: pointer; }
    .btn-primary:hover { background: #0056b3; }
    a { font-size: 17px; text-decoration: none; }
</style>
</head>

<body>

<h2>Daftar Pesanan</h2>

<?php if (isset($_SESSION['nama_pemesan'])): ?>
    <h3>👤 Nama Pemesan: <?= $_SESSION['nama_pemesan']; ?></h3>
<?php else: ?>
    <h3>👤 Nama Pemesan: - </h3>
<?php endif; ?>


<table>
<tr>
    <th>No</th>
    <th>Menu</th>
    <th>Sajian</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Aksi</th>
</tr>

<?php
$total = 0;
if (!empty($_SESSION['cart'])) {
    $no = 1;
    foreach ($_SESSION['cart'] as $index => $item) {
        echo "<tr>
                <td>$no</td>
                <td>{$item['nama']}</td>
                <td>{$item['sajian']}</td>
                <td>Rp {$item['harga']}</td>
                <td>{$item['qty']}</td>
                <td>Rp {$item['subtotal']}</td>
                <td><a href='hapus_cart.php?index=$index' class='btn-danger'>Hapus</a></td>
              </tr>";
        $total += $item['subtotal'];
        $no++;
    }
}
?>
</table>

<div class="total-box">
    Total Pembayaran: Rp <?= $total ?>
</div>

<div style="text-align:center; margin-top: 20px;">
    <a href="menu_user.php">Tambah Pesanan</a> |

    <!-- FORM CHECKOUT DIHALAMAN INI -->
    <form method="POST" style="display:inline;">
        <input type="hidden" name="total" value="<?= $total ?>">
        <button type="submit" name="checkout" class="btn-primary">Checkout</button>
    </form>
</div>

<?php if ($pesanan_selesai): ?>
    <div style="
        margin: 25px auto;
        background: #d4edda;
        border: 1px solid #155724;
        padding: 20px;
        width: 60%;
        border-radius: 10px;
        color: #155724;
        text-align: center;
        font-size: 18px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    ">
        <h2>✔ Pesanan Anda Akan Kami Proses</h2>
        <p>Silahkan pergi ke kasir dan lakukan pembayaran.</p>
        <p>Terima kasih telah memesan di restoran kami.</p>

        <a href="logout.php" class="btn-danger">Logout</a>
    </div>
<?php endif; ?>

</body>
</html>
