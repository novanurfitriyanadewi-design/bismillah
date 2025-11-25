<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran Siswa</title>
</head>
<body>
    <h2>Form Pendaftaran Siswa</h2>
    <form action="simpan.php" method="POST">
        <label>Nama:</label><br>
        <input type="text" name="nama"><br><br>

        <label>Alamat:</label><br>
        <input type="text" name="alamat"><br><br>

        <label>Agama:</label><br>
        <input type="text" name="agama"><br><br>

        <label>Asal Sekolah:</label><br>
        <input type="text" name="asal_sekolah"><br><br>

        <input type="submit" value="Kirim">
    </form>
</body>
</html>