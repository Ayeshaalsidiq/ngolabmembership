<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gunakan user_id = 1 sesuai kesepakatan karena login ditiadakan
    $user_id = 1;
    
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $whatsapp = $conn->real_escape_string(trim($_POST['whatsapp']));
    $email = $conn->real_escape_string(trim($_POST['email']));

    if (empty($nama) || empty($whatsapp) || empty($email)) {
        header("Location: ../../pages/profile/profile.php?error=Semua kolom harus diisi!");
        exit;
    }

    $query = "UPDATE users SET nama = ?, whatsapp = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nama, $whatsapp, $email, $user_id);

    if ($stmt->execute()) {
        header("Location: ../../pages/profile/profile.php?success=Profil berhasil diperbarui!");
    } else {
        header("Location: ../../pages/profile/profile.php?error=Terjadi kesalahan saat memperbarui profil.");
    }
} else {
    header("Location: ../../pages/profile/profile.php");
}
?>
