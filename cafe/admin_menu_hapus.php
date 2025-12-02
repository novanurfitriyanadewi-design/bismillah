<?php
include "koneksi.php";

$id = $_GET["id"];
mysqli_query($koneksi, "DELETE FROM menu WHERE id='$id'");

echo "<script>alert('Menu dihapus!');location='admin_menu.php';</script>";
?>
