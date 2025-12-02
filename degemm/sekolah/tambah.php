<!DOCTYPE html>
<html>
<head>
    <title>SI Sekolah | Tambah Data Siswa</title>
</head>
<body>
    <h2>SI Sekolah | Tambah Data Siswa</h2>
    <br>
    <a href="index.php">KEMBALI</a>
    <h3>Tambah Data Siswa</h3>

    <form method="post" action="tambah_aksi.php">
        <table>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama"></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td><input type="number" name="nis"></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><input type="text" name="alamat"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="SIMPAN"></td>
            </tr>
        </table>
    </form>
</body>
</html>
