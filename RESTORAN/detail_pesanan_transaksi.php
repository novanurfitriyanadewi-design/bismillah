<?php
include "koneksi.php";
$id = $_GET['id'];
$detail = mysqli_query($koneksi, "SELECT * FROM detail_transaksi WHERE id_transaksi = '$id'");
?>

<h2>Detail Pesanan</h2>
<table border="1" cellpadding="8">
<tr>
    <th>Menu</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($detail)) { ?>
<tr>
    <td><?= $row['nama_menu']; ?></td>
    <td>Rp <?= $row['harga']; ?></td>
    <td><?= $row['qty']; ?></td>
    <td>Rp <?= $row['subtotal']; ?></td>
</tr>
<?php } ?>
</table>
