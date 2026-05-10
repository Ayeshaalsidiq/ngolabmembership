<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gunakan user_id = 1 sesuai kesepakatan karena login ditiadakan
    $user_id = 1;
    
    $nim = $conn->real_escape_string(trim($_POST['nim']));
    
    // Validasi NIM
    if (empty($nim)) {
        header("Location: ../../pages/profile/profile.php?error=NIM harus diisi!");
        exit;
    }
    
    // Validasi file upload
    if (!isset($_FILES['ktm']) || $_FILES['ktm']['error'] !== UPLOAD_ERR_OK) {
        header("Location: ../../pages/profile/profile.php?error=Foto KTM harus diunggah!");
        exit;
    }
    
    $file = $_FILES['ktm'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    // Cek tipe file
    if (!in_array($file['type'], $allowed_types)) {
        header("Location: ../../pages/profile/profile.php?error=Format file harus JPG, PNG, atau JPEG!");
        exit;
    }
    
    // Cek ukuran file
    if ($file['size'] > $max_size) {
        header("Location: ../../pages/profile/profile.php?error=Ukuran file maksimal 2MB!");
        exit;
    }
    
    // Buat nama file unik
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'ktm_user' . $user_id . '_' . time() . '.' . $ext;
    $upload_dir = '../../assets/uploads/ktm/';
    $upload_path = $upload_dir . $filename;
    
    // Pastikan direktori ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Simpan ke database - auto verify karena tidak ada admin panel
        $ktm_db_path = 'assets/uploads/ktm/' . $filename;
        $query = "UPDATE users SET nim = ?, ktm_path = ?, role = 'Mahasiswa', is_verified = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nim, $ktm_db_path, $user_id);
        
        if ($stmt->execute()) {
            header("Location: ../../pages/profile/profile.php?success=Verifikasi berhasil! Anda sekarang terdaftar sebagai Mahasiswa.");
        } else {
            header("Location: ../../pages/profile/profile.php?error=Terjadi kesalahan saat menyimpan data verifikasi.");
        }
    } else {
        header("Location: ../../pages/profile/profile.php?error=Gagal mengunggah file KTM!");
    }
} else {
    header("Location: ../../pages/profile/profile.php");
}
?>
