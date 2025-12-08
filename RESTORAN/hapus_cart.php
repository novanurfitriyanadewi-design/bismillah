<?php
session_start();

// pastikan index dikirim
if (isset($_GET['index'])) {
    $index = (int) $_GET['index']; // cast ke integer untuk keamanan

    // kalau index valid, hapus dan rapikan array
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // rapikan index
    }
}

// kembali ke halaman cart
header('Location: cart.php');
exit;
?>
