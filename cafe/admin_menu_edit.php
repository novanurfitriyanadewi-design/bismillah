<?php
include "koneksi.php";
$id = $_GET["id"];

$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id='$id'"));
?>

<html>
<head>
<title>Edit Menu</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2>Edit Menu</h2>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" value="<?= $m['nama'] ?>" required>
    <input type="number" name="harga" value="<?= $m['harga'] ?>" required>
    <p>Gambar sekarang: <br><img src="assets/<?= $m['gambar'] ?>" width="100"></p>
    <input type="file" name="gambar">
    <button type="submit" name="edit">Simpan Perubahan</button>
</form>

<?php
if (isset($_POST['edit'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    // jika gambar diganti
    if (!empty($_FILES['gambar']['name'])) {
        $namaFile = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "assets/" . $namaFile);
    } else {
        $namaFile = $m['gambar'];
    }

    mysqli_query($koneksi, "UPDATE menu SET 
        nama='$nama',
        harga='$harga',
        gambar='$namaFile'
        WHERE id='$id'");

    echo "<script>alert('Menu diperbarui');location='admin_menu.php';</script>";
}
?>

</body>
</html>

