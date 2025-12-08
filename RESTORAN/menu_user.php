<?php
// Debug mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "koneksi.php";

// Nama pemesan tidak perlu ditampilkan di navbar 

if (isset($_POST['set_nama'])) {
    $_SESSION['nama_pemesan'] = $_POST['nama_pemesan'];
}

// Cek koneksi
if (!isset($koneksi) || !$koneksi) {
    die("Koneksi DB gagal. Cek file koneksi.php");
}

// Ambil data pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : "";
$cari_esc = mysqli_real_escape_string($koneksi, $cari);

// Query menu
if ($cari_esc === "") {
    $sql = "SELECT * FROM menu ORDER BY id_menu ASC";
} else {
    $sql = "SELECT * FROM menu WHERE nama LIKE '%$cari_esc%' ORDER BY id_menu ASC";
}

$q = mysqli_query($koneksi, $sql);
if (!$q) {
    die("Query error: " . mysqli_error($koneksi));
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>RUMAH MAKAN KENYANG - Menu</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f3f3f3; }
        .navbar-custom { background-color: #212529; padding: 15px; }
        .menu-card { border-radius: 8px; overflow: hidden; background-color: white; box-shadow: 0 0 6px rgba(0,0,0,0.15); margin-bottom: 25px; }
        .menu-card-header { background-color: #00a9e0; color: white; padding: 8px; text-align: center; font-weight: bold; }
        .menu-img { width: 100%; height: 180px; object-fit: cover; border-bottom: 1px solid #ddd; }
        .menu-body { padding: 12px; font-size: 15px; }
        .menu-btn { width: 30px; cursor: pointer; }
        .menu-btn span { display: block; width: 30px; height: 4px; background: white; margin: 6px 0; border-radius: 4px; }
    </style>
</head>

<body>

<div id="overlay"></div>

<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
    
    <!-- Bagian kiri (hamburger + judul) -->
    <div class="d-flex align-items-center gap-3">
        <div class="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <h4 class="text-white m-0">RUMAH MAKAN KENYANG</h4>
    </div>

    <!-- Bagian kanan (logout) -->
    <div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

</nav>


<div class="container mt-4">

    <!-- FORM SEARCH -->
    <form method="GET" action="" class="mb-4">
        <div class="input-group" style="max-width: 600px;">
            <input type="text" name="cari" class="form-control" placeholder="Cari menu..."
                   value="<?php echo htmlspecialchars($cari); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- FORM NAMA PEMESAN -->
    <form method="POST" class="mb-4">
        <div class="input-group" style="max-width: 600px;">
            <input type="text" name="nama_pemesan" class="form-control"
                   placeholder="Masukkan nama pemesan..." required>
            <button type="submit" name="set_nama" class="btn btn-success">Simpan</button>
        </div>
    </form>

    <!-- LIST MENU -->
    <?php if(mysqli_num_rows($q) == 0): ?>
        <h5 class="text-center text-danger">Menu tidak ditemukan!</h5>
    <?php else: ?>
        <div class="row">
            <?php while($m = mysqli_fetch_assoc($q)): ?>
            <?php
                $nama = $m['nama'];
                $harga = $m['harga'];
                $kategori = $m['kategori'];
                $status = $m['status'];
                $gambar = (!empty($m['gambar'])) ? $m['gambar'] : 'default.png';
            ?>
            <div class="col-md-4 mb-4">
                <div class="menu-card">
                    <img src="img/<?php echo $gambar; ?>" class="menu-img" alt="<?php echo $nama; ?>">

                    <div class="p-3">
                        <h5><?php echo $nama; ?></h5>
                        <p>Harga: Rp<?php echo number_format($harga, 0, ',', '.'); ?></p>
                        <p>Kategori: <?php echo $kategori; ?></p>
                        <p>Status: <?php echo $status; ?></p>

                        <form action="cart.php" method="POST">
                            <input type="hidden" name="id_menu" value="<?php echo $m['id_menu']; ?>">
                            <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                            <input type="hidden" name="harga" value="<?php echo $harga; ?>">

                            <label>Qty:</label>
                            <input type="number" min="1" name="qty" class="form-control mb-2" required>

                            <?php if(strtolower($kategori) == 'minuman'): ?>
                                <label>Pilih Sajian :</label>
                                <select name="sajian" class="form-control mb-2" required>
                                    <option value="Dingin">Dingin</option>
                                    <option value="Panas">Panas</option>
                                </select>
                            <?php else: ?>
                                <input type="hidden" name="sajian" value="-">
                            <?php endif; ?>

                            <button type="submit" name="tambah_cart" class="btn btn-success w-100">Pesan</button>
                        </form>

                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
