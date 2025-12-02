<?php
// register.php
session_start();
require_once __DIR__.'/koneksi.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama']));
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $err = 'Username & password wajib diisi.';
    } else {
        $cek = mysqli_query($koneksi, "SELECT id_user FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $err = 'Username sudah dipakai.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            mysqli_query($koneksi, "INSERT INTO users (username,password,nama,is_admin) VALUES ('$username','$hash','$nama',0)");
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - Cafe</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="center-page">
  <div class="card auth-card">
    <h2>Daftar Akun</h2>
    <?php if($err): ?><div class="alert"><?=$err?></div><?php endif; ?>
    <form method="post">
      <label>Nama</label>
      <input type="text" name="nama">
      <label>Username</label>
      <input type="text" name="username" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button type="submit" class="btn">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="index.php">Login</a></p>
  </div>
</body>
</html>
