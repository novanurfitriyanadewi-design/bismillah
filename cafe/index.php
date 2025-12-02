<?php
// index.php
session_start();
require_once __DIR__.'/koneksi.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password = $_POST['password'];

    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' LIMIT 1");
    if ($q && mysqli_num_rows($q) === 1) {
        $u = mysqli_fetch_assoc($q);
        if (password_verify($password, $u['password'])) {
            // set session
            $_SESSION['user'] = [
                'id' => $u['id_user'],
                'username' => $u['username'],
                'nama' => $u['nama'],
                'is_admin' => (int)$u['is_admin']
            ];
            $_SESSION['cart'] = $_SESSION['cart'] ?? [];

            if ((int)$u['is_admin'] === 1) {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: menu.php');
            }
            exit;
        } else {
            $err = 'Password salah.';
        }
    } else {
        $err = 'Username tidak ditemukan.';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Cafe</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="center-page">
  <div class="card auth-card">
    <h2>Login</h2>
    <?php if($err): ?><div class="alert"><?=$err?></div><?php endif; ?>
    <form method="post">
      <label>Username</label>
      <input type="text" name="username" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button type="submit" class="btn">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    <p>Untuk akses admin: buat user dengan is_admin=1 lewat phpMyAdmin atau register lalu ubah di DB.</p>
  </div>
</body>
</html>
