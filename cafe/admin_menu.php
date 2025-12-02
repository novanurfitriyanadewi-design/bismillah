<?php
session_start();
include "koneksi.php";

$q = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id DESC");
?>
<html>
<head>
<title>Kelola Menu</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2>Kelola Menu Café</h2>

<a href="admin_menu_tambah.php">+ Tambah Menu</a>
<a href="admin_pesanan.php">← Kembali</a>

<table>
<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Harga</th>
    <th>Gambar</th>
    <th>Aksi</th>
</tr>

<?php while($m = mysqli_fetch_assoc($q)) { ?>
<tr>
    <td><?= $m['id'] ?></td>
    <td><?= $m['nama'] ?></td>
    <td>Rp <?= number_format($m['harga']) ?></td>
    <td><img src="assets/<?= $m['gambar'] ?>" width="50"></td>
    <td>
        <a href="admin_menu_edit.php?id=<?= $m['id'] ?>">Edit</a>
        <a href="admin_menu_hapus.php?id=<?= $m['id'] ?>" style="background:red;">Hapus</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
