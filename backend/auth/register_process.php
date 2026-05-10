<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $whatsapp = $conn->real_escape_string(trim($_POST['whatsapp']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    // Validasi sederhana
    if (empty($nama) || empty($whatsapp) || empty($email) || empty($password)) {
        header("Location: ../../pages/auth/register.php?error=Semua kolom harus diisi!");
        exit;
    }

    // Cek apakah email sudah terdaftar
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        header("Location: ../../pages/auth/register.php?error=Email sudah terdaftar!");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert ke database
    $query = "INSERT INTO users (nama, whatsapp, email, password, poin, tier) VALUES (?, ?, ?, ?, 0, 'Silver')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nama, $whatsapp, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_nama'] = $nama;
        header("Location: ../../pages/dashboard/index.php?success=Registrasi berhasil! Selamat datang.");
    } else {
        header("Location: ../../pages/auth/register.php?error=Terjadi kesalahan saat menyimpan data.");
    }
} else {
    header("Location: ../../pages/auth/register.php");
}
?>
