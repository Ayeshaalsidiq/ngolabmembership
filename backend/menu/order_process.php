<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/database.php';

// Pastikan user sudah login sebelum memproses pesanan
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/menu/menu.php?error=Silakan login terlebih dahulu untuk memesan menu!");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id'])) {
    // Ambil user_id dari sesi akun yang sedang login (bukan hardcode angka 1)
    $user_id = $_SESSION['user_id'];
    $menu_id = intval($_POST['menu_id']);

    // Get menu details
    $res_menu = $conn->query("SELECT nama_menu, poin_didapat FROM menus WHERE id = $menu_id");
    if($res_menu->num_rows == 0) {
        header("Location: ../../pages/menu/menu.php?error=Menu tidak ditemukan!");
        exit;
    }
    $menu = $res_menu->fetch_assoc();
    $points_earned = $menu['poin_didapat'];
    $menu_name = $menu['nama_menu'];

    // Add points to user
    $conn->query("UPDATE users SET poin = poin + $points_earned WHERE id = $user_id");

    // Add activity
    $activity_name = "Pesan Menu (" . $conn->real_escape_string($menu_name) . ")";
    $query = "INSERT INTO activities (user_id, jenis_aktivitas, jumlah_poin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $user_id, $activity_name, $points_earned);
    $stmt->execute();

    header("Location: ../../pages/menu/menu.php?success=Berhasil memesan " . urlencode($menu_name) . ". Anda mendapatkan " . $points_earned . " Pts!");
} else {
    header("Location: ../../pages/menu/menu.php");
}
?>