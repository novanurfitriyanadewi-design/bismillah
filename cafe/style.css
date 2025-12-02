<?php
include "koneksi.php";

$id = $_GET["id"];
$p = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id='$id'"));
$d = mysqli_query($koneksi, "
    SELECT menu.nama, detail_pesanan.jumlah, menu.harga
    FROM detail_pesanan
    JOIN menu ON menu.id = detail_pesanan.id_menu
    WHERE id_pesanan='$id'
");
?>

<html>
<body>
<h2>Struk Pesanan</h2>

Nama: <?= $p["nama_pemesan"] ?><br>
Meja: <?= $p["no_meja"] ?><br>
Tanggal: <?= $p["tanggal"] ?><br><br>

<table border="1">
<tr><th>Menu</th><th>Qty</th><th>Subtotal</th></tr>

<?php while ($i = mysqli_fetch_assoc($d)) { ?>
<tr>
    <td><?= $i["nama"] ?></td>
    <td><?= $i["jumlah"] ?></td>
    <td><?= number_format($i["harga"] * $i["jumlah"]) ?></td>
</tr>
<?php } ?>

</table>

<h3>Total: Rp <?= number_format($p["total"]) ?></h3>

<script>window.print();</script>

</body>
</html>
