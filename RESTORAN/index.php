<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query ambil user
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

    // Jika query gagal, tampilkan error biar jelas
    if (!$query) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_assoc($query);

    // Username ditemukan
    if ($data) {

        // Cek password
        if (password_verify($password, $data['password'])) {

            $_SESSION['id_user']  = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['level']    = $data['level'];

            // ARAHKAN SESUAI LEVEL
            if ($data['level'] == 'admin') {
                header("Location: menu_admin.php");
                exit;
            } elseif ($data['level'] == 'user') {
                header("Location: menu_user.php");
                exit;
            } else {
                echo "Level user tidak dikenali!";
                exit;
            }

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

<?php if (!empty($error)) { ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

    <div class="inputBox">
        <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="inputBox">
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <button type="submit" name="login">Login</button>
</form>

<p>Belum punya akun? <a href="register.php">Daftar</a></p>

</div>

</body>
</html>
