<?php
// menu.php (pembeli)
session_start();
if (!isset($_SESSION['user'])) header('Location: index.php');
require_once __DIR__.'/koneksi.php';

$nama_user = $_SESSION['user']['nama'] ?? $_SESSION['user']['username'];
$res = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY id_menu ASC");

function cart_count() {
    if (!isset($_SESSION['cart'])) return 0;
    $sum = 0;
    foreach($_SESSION['cart'] as $c) $sum += $c['qty'];
    return $sum;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Menu - Cafe</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<nav class="nav">
  <div class="nav-left">Cafe Online</div>
  <div class="nav-right">
    <span>Halo, <?=htmlspecialchars($nama_user)?></span>
    <a href="cart.php" class="btn small">Cart (<?=cart_count()?>)</a>
    <a href="transaksi.php" class="btn small">Transaksi</a>
    <a href="logout.php" class="btn small danger">Logout</a>
  </div>
</nav>

<main class="container">
  <h2>Daftar Menu</h2>

  <!-- Form input nama pemesan & kota (di atas menu) -->
  <div class="card">
    <form id="pemesan-form" method="get" action="menu.php" style="display:flex;gap:8px;flex-wrap:wrap;">
      <input type="text" name="nama_pemesan" placeholder="Nama Pemesan" value="<?=htmlspecialchars($_GET['nama_pemesan'] ?? $nama_user)?>" required>
      <input type="text" name="kota_pemesan" placeholder="Kota Pemesan (contoh: Jombang)" value="<?=htmlspecialchars($_GET['kota_pemesan'] ?? '')?>" required>
      <button type="submit" class="btn">Set</button>
      <small style="align-self:center;color:#555">Nama & kota akan dikirim saat checkout</small>
    </form>
  </div>

  <div class="menu-grid">
    <?php while($m = mysqli_fetch_assoc($res)): ?>
      <div class="menu-card">
        <img src="<?=htmlspecialchars($m['gambar']?: 'assets/img/placeholder.png')?>" onerror="this.src='assets/img/placeholder.png'">
        <div class="menu-body">
          <h3><?=htmlspecialchars($m['nama_menu'])?></h3>
          <p class="desc"><?=htmlspecialchars($m['deskripsi'])?></p>
          <div class="menu-footer">
            <strong>Rp <?=number_format($m['harga'],0,',','.')?></strong>
            <form action="add_to_cart.php" method="post" style="display:inline;">
              <input type="hidden" name="id_menu" value="<?=$m['id_menu']?>">
              <input type="hidden" name="nama_menu" value="<?=htmlspecialchars($m['nama_menu'])?>">
              <input type="hidden" name="harga" value="<?=$m['harga']?>">
              <input type="hidden" name="nama_pemesan" value="<?=htmlspecialchars($_GET['nama_pemesan'] ?? $nama_user)?>">
              <input type="hidden" name="kota_pemesan" value="<?=htmlspecialchars($_GET['kota_pemesan'] ?? '')?>">
              <button class="btn round">+</button>
            </form>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</main>

</body>
</html>
