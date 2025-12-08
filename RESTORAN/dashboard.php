<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
        }
        .navbar {
            background: #007bff;
            color: white;
            padding: 15px;
            font-size: 20px;
        }
        .container {
            padding: 30px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            width: 300px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        a.button {
            display: block;
            background: #007bff;
            padding: 10px;
            text-align: center;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 15px;
        }
        a.logout {
            background: red;
        }
    </style>

</head>
<body>

<div class="navbar">
    Selamat datang, <b><?= $username; ?></b>
</div>

<div class="container">
    
    <div class="card">
        <h3>Menu Makanan & Minuman</h3>
        <p>Pilih makanan dan minuman di sini.</p>
        <a class="button" href="menu.php">Pilih Menu</a>
    </div>

    <div class="card">
        <h3>Riwayat Transaksi</h3>
        <p>Lihat pesanan yang pernah kamu buat.</p>
        <a class="button" href="riwayat.php">Lihat Riwayat</a>
    </div>

    <a href="logout.php" class="button logout">Logout</a>

</div>

</body>
</html>
