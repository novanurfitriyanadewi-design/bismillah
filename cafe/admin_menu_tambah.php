<html>
<head>
<title>Tambah Menu</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2>Tambah Menu</h2>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" placeholder="Nama Menu" required>
    <input type="number" name="harga" placeholder="Harga" required>
    <input type="file" name="gambar" required>
    <button type="submit" name="tambah">Tambah Menu</button>
</form>

<?php
include "koneksi.php";

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    $namaFile = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "assets/" . $namaFile);

    mysqli_query($koneksi, "INSERT INTO menu (nama, harga, gambar)
    VALUES ('$nama', '$harga', '$namaFile')");

    echo "<script>alert('Menu berhasil ditambahkan');location='admin_menu.php';</script>";
}
?>

</body>
</html>
