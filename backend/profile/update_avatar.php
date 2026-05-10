<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['avatar'];

    if ($file['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $file['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = "avatar_" . $user_id . "_" . time() . "." . $ext;
            $upload_path = "../../assets/uploads/avatars/";
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $upload_path . $new_name)) {
                // Hapus foto lama jika ada
                $old_q = $conn->query("SELECT foto_profile FROM users WHERE id = $user_id");
                if ($old_row = $old_q->fetch_assoc()) {
                    if (!empty($old_row['foto_profile']) && strpos($old_row['foto_profile'], 'http') === false) {
                        @unlink($old_row['foto_profile']);
                    }
                }

                $relative_path = "../../assets/uploads/avatars/" . $new_name;
                $conn->query("UPDATE users SET foto_profile = '$relative_path' WHERE id = $user_id");
                
                header("Location: ../../pages/profile/profile.php?success=Foto profil berhasil diperbarui!");
            } else {
                header("Location: ../../pages/profile/profile.php?error=Gagal mengupload file.");
            }
        } else {
            header("Location: ../../pages/profile/profile.php?error=Format file tidak didukung.");
        }
    } else {
        header("Location: ../../pages/profile/profile.php?error=Terjadi kesalahan saat upload.");
    }
} else {
    header("Location: ../../pages/profile/profile.php");
}
?>
