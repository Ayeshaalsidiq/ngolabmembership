<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../../pages/auth/login.php?error=Email dan kata sandi harus diisi!");
        exit;
    }

    $query = "SELECT id, nama, password, poin, tier FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Set session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            
            header("Location: ../../pages/dashboard/index.php");
            exit;
        } else {
            header("Location: ../../pages/auth/login.php?error=Kata sandi salah!");
            exit;
        }
    } else {
        header("Location: ../../pages/auth/login.php?error=Email tidak ditemukan!");
        exit;
    }
} else {
    header("Location: ../../pages/auth/login.php");
}
?>
