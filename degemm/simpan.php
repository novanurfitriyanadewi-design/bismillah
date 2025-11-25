<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $asal_sekolah = $_POST['asal_sekolah'];

    $simpan = mysqli_query($konek, "INSERT INTO data_siswa (nama_siswa, alamat_siswa, agama_siswa, asal_sekolah)
                                    VALUES ('$nama', '$alamat', '$agama', '$asal_sekolah')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Tersimpan</title>
</head>
<body>
    <h2>Data Siswa</h2>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>Asal Sekolah</th>
        </tr>
        <?php
        $no = 1;
        $query = mysqli_query($konek, "SELECT * FROM data_siswa");
        while ($data = mysqli_fetch_array($query)) {
            echo "<tr>
                    <td>$no</td>
                    <td>{$data['nama_siswa']}</td>
                    <td>{$data['alamat_siswa']}</td>
                    <td>{$data['agama_siswa']}</td>
                    <td>{$data['asal_sekolah']}</td>
                  </tr>";
            $no++;
        }
        ?>
    </table>
</body>
</html>