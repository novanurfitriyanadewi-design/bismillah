<?php
session_start();
include "koneksi.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Input pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : "";
$cari_esc = mysqli_real_escape_string($koneksi, $cari);

// Query menu
if ($cari_esc == "") {
    $sql = "SELECT * FROM menu ORDER BY id_menu ASC";
} else {
    $sql = "SELECT * FROM menu WHERE nama LIKE '%$cari_esc%' ORDER BY id_menu ASC";
}
$q = mysqli_query($koneksi, $sql);
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

        .menu-btn { cursor: pointer; }
        .menu-btn span { display: block; width: 30px; height: 4px; background: white; margin: 5px 0; }

        .menu-card { border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 0 6px rgba(0,0,0,0.15); }
        .menu-img { width: 100%; height: 180px; object-fit: cover; }

        /* SIDEBAR */
        #sidebar {
            position: fixed; top: 0; left: -270px; width: 270px; height: 100%;
            background: #1b232c; padding: 30px 20px; transition: .4s; z-index: 9999; color: white;
        }
        #sidebar.active { left: 0; }
        #sidebar a { display: block; font-size: 18px; color: white; margin: 15px 0; text-decoration: none; }
        #sidebar a:hover { padding-left: 6px; transition: .2s; }
        #overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,.5); z-index: 999;
        }
        #overlay.active { display: block; }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div id="sidebar">
    <h3>RUMAH MAKAN</h3>
    <a href="menu_admin.php">MENU</a>
    <a href="pesanan_admin.php">PESANAN</a>
    <a href="transaksi.php">TRANSAKSI</a>
</div>
<div id="overlay"></div>

<!-- NAVBAR -->
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">

    <!-- kiri = hamburger + judul -->
    <div class="d-flex align-items-center gap-3">
        <div class="menu-btn">
            <span></span><span></span><span></span>
        </div>
        <h4 class="text-white m-0">RUMAH MAKAN KENYANG</h4>
    </div>

    <!-- kanan = nama pemesan + logout -->
    <div class="d-flex align-items-center gap-3">
        <?php if(isset($_SESSION['nama_pembeli']) && $_SESSION['nama_pembeli'] != ''): ?>
            <span class="text-white">Pemesan: <?= htmlspecialchars($_SESSION['nama_pembeli']); ?></span>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

</nav>

<div class="container mt-4">

    <!-- Search -->
    <form action="" method="GET" class="mb-4">
        <div class="input-group" style="max-width:600px;">
            <input type="text" name="cari" class="form-control"
                   placeholder="Cari makanan / minuman / dessert..."
                   value="<?= htmlspecialchars($cari); ?>">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Simpan nama pemesan -->
    <form action="simpan_pemesan.php" method="POST" class="mb-4">
        <div class="input-group" style="max-width:600px;">
            <input type="text" name="nama_pemesan" class="form-control"
                   placeholder="Masukkan nama pemesan..." required>
            <button class="btn btn-success" name="simpan_data_pembeli">Simpan</button>
        </div>
    </form>

    <div class="row">
        <?php if(mysqli_num_rows($q) == 0): ?>
            <h5 class="text-center text-danger">Menu tidak ditemukan!</h5>
        <?php else: ?>
            <?php while($m = mysqli_fetch_assoc($q)): ?>
            <div class="col-md-4 mb-4">
                <div class="menu-card">
                    <img src="img/<?= htmlspecialchars($m['gambar'] ?: 'default.png'); ?>"
                         class="menu-img"
                         onerror="this.src='img/default.png';">

                    <div class="p-3">
                        <h5><?= htmlspecialchars($m['nama']); ?></h5>
                        <p>Harga: Rp<?= number_format($m['harga'], 0, ',', '.'); ?></p>
                        <p>Kategori: <?= htmlspecialchars($m['kategori']); ?></p>
                        <p>Status: <?= htmlspecialchars($m['status']); ?></p>

                        <form action="cart.php" method="POST">
                            <input type="hidden" name="nama" value="<?= htmlspecialchars($m['nama']); ?>">
                            <input type="hidden" name="harga" value="<?= $m['harga']; ?>">

                            <label>Qty :</label>
                            <input type="number" name="qty" min="1" class="form-control mb-2" required>

                            <?php if(strtolower($m['kategori']) == 'minuman'): ?>
                                <label>Pilih Sajian :</label>
                                <select name="sajian" class="form-control mb-2" required>
                                    <option value="Dingin">Dingin</option>
                                    <option value="Panas">Panas</option>
                                </select>
                            <?php else: ?>
                                <input type="hidden" name="sajian" value="-">
                            <?php endif; ?>

                            <button class="btn btn-success w-100" name="tambah_cart">Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script>
const menuBtn = document.querySelector(".menu-btn");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

menuBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
});
overlay.addEventListener("click", ()=>{
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
});
</script>

</body>
</html>
