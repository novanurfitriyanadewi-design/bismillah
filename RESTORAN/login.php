<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $data  = mysqli_fetch_assoc($query);

    if ($data) {

        // Verifikasi password
        if (password_verify($password, $data['password'])) {

            // Simpan session user
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['level'] = $data['level'];  // ➜ WAJIB

            // Arahkan berdasarkan level
            if ($data['level'] == 'admin') {
                header("Location: menu_admin.php");
            } else if ($data['level'] == 'user') {
                header("Location: menu_user.php");
            } else {
                echo "Level user tidak dikenali!";
            }
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Akun</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>

<div class="container">
    <h2>Login</h2>

    <?php if ($error != "") { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <div class="inputBox">
            <input type="text" name="username" placeholder="username" required>
        </div>

        <div class="inputBox">
            <input type="password" name="password" placeholder="password" required>
        </div>

        <button type="submit" name="login">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Register</a></p>
</div>

</body>
</html>
