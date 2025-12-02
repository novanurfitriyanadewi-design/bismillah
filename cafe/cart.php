<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if ($_POST) {
    $id = $_POST["id_menu"];
    if (!isset($_SESSION["cart"][$id])) {
        $_SESSION["cart"][$id] = 1;
    } else {
        $_SESSION["cart"][$id]++;
    }
}

$total = 0;
?>

<html>
<head>
<title>Keranjang</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2>Keranjang</h2>

<table border="1">
<tr>
    <th>Menu</th>
    <th>Jumlah</th>
    <th>Total</th>
</tr>

<?php
foreach ($_SESSION["cart"] as $id => $jumlah) {
    $menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id='$id'"));
    $sub = $menu["harga"] * $jumlah;
    $total += $sub;
?>
<tr>
    <td><?= $menu["nama"] ?></td>
    <td><?= $jumlah ?></td>
    <td><?= number_format($sub) ?></td>
</tr>
<?php } ?>

</table>

<h3>Total: Rp <?= number_format($total) ?></h3>

<a href="checkout.php">Lanjutkan ke Checkout</a>

</body>
</html>
