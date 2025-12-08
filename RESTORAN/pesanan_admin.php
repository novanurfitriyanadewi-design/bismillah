<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

$pesanan = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id DESC");
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>RUMAH MAKAN KENYANG - Pesanan</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background-color: #f3f3f3; }

/* Navbar */
.navbar-custom { background-color: #212529; padding: 15px; }
.menu-btn { cursor: pointer; }
.menu-btn span {
    display: block; width: 30px; height: 4px; background: white;
    margin: 5px 0; border-radius: 4px;
}

/* Sidebar */
#sidebar {
    position: fixed;
    top: 0; left: -270px;
    width: 270px; height: 100%;
    background: #1b232c; padding: 30px 20px;
    transition: .4s; z-index: 9999; color: white;
}
#sidebar.active { left: 0; }
#sidebar h3 { margin-bottom: 25px; }
#sidebar a {
    display: block; color: white; font-size: 19px;
    margin: 15px 0; text-decoration: none;
}
#sidebar a:hover { padding-left: 6px; transition: .3s; }

/* Overlay */
#overlay {
    display: none; position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,.5); z-index: 999;
}
#overlay.active { display: block; }
</style>
</head>

<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3>RUMAH MAKAN</h3>
    <a href="menu_user.php">MENU</a>
    <a href="pesanan_admin.php">PESANAN</a>
    <a href="transaksi.php">TRANSAKSI</a>
</div>
<div id="overlay"></div>

<!-- Navbar -->
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
    
    <!-- Bagian kiri (hamburger + judul) -->
    <div class="d-flex align-items-center gap-3">
        <div class="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <h4 class="text-white m-0">DAFTAR PESANAN</h4>
    </div>

    <!-- Bagian kanan (logout) -->
    <div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

</nav>


<div class="container mt-4">
    <h3 class="mb-4"></h3>

    <?php while($p = mysqli_fetch_assoc($pesanan)) { ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Pesanan #<?= $p['id'] ?></strong> – <?= htmlspecialchars($p['nama_pemesan']) ?>
            </div>
            <div class="card-body">
                <p><b>Tanggal Pesan:</b> <?= $p['tgl_pesan'] ?></p>
                <p><b>Total Harga:</b> Rp <?= number_format($p['total_harga']) ?></p>

                <?php
                $detail = mysqli_query($koneksi, "
                    SELECT m.nama AS nama_menu, d.qty, d.subtotal
                    FROM pesanan_detail d
                    LEFT JOIN menu m ON d.id_menu = m.id_menu
                    WHERE d.id_pesanan = {$p['id']}
                ");
                ?>

                <table class="table table-bordered">
                    <tr style="background:#f2f2f2;">
                        <th>Nama Menu</th><th width="80">Qty</th><th>Sub Total</th>
                    </tr>
                    <?php if(mysqli_num_rows($detail) > 0) {
                        while($d = mysqli_fetch_assoc($detail)) { ?>
                            <tr>
                                <td><?= $d['nama_menu'] ?></td>
                                <td><?= $d['qty'] ?></td>
                                <td>Rp <?= number_format($d['subtotal']) ?></td>
                            </tr>
                    <?php } } else { ?>
                        <tr><td colspan="3" class="text-center text-danger">Tidak ada detail pesanan</td></tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php } ?>
</div>

<!-- JavaScript untuk membuka/menutup sidebar -->
<script>
const btn = document.querySelector(".menu-btn");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

btn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
});

overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
});
</script>

</body>
</html>
