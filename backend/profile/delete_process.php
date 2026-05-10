<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gunakan user_id = 1
    $user_id = 1;
    
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect ke register karena akun sudah dihapus
        header("Location: ../../pages/auth/register.php?error=Akun Anda telah berhasil dihapus.");
    } else {
        header("Location: ../../pages/profile/profile.php?error=Terjadi kesalahan saat menghapus akun.");
    }
} else {
    header("Location: ../../pages/profile/profile.php");
}
?>
