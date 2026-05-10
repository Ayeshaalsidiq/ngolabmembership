<?php
// Menerapkan session_start() dari Modul 4
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gunakan user_id = 1 sesuai kesepakatan karena login ditiadakan
    $user_id = 1;
    
    // Menerapkan Security Tip (Sanitasi) dari Modul 1 untuk mencegah injeksi
    $nim = $conn->real_escape_string(trim($_POST['nim']));
    $nim = htmlspecialchars($nim); // Tambahan proteksi XSS
    
    // Validasi NIM (Error Handling)
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
        
        // Simpan ke database dengan Prepared Statement (Modul 3 & Modul 1-5 Keamanan)
        $ktm_db_path = 'assets/uploads/ktm/' . $filename;
        
        // PERUBAHAN: Role tidak diubah & is_verified diset 0 (menunggu persetujuan admin)
        $query = "UPDATE users SET nim = ?, ktm_path = ?, is_verified = 0 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nim, $ktm_db_path, $user_id);
        
        if ($stmt->execute()) {
            
            // ================================================================
            // IMPLEMENTASI MODUL 4: COOKIES
            // Menyimpan status PENDING verifikasi pengguna ke dalam browser Cookies
            // Cookie akan valid selama 30 hari (86400 detik * 30)
            // ================================================================
            setcookie("is_pending_verification", "1", time() + (86400 * 30), "/");
            setcookie("user_nim", $nim, time() + (86400 * 30), "/");
            
            // Redirect dengan pesan sukses menunggu
            header("Location: ../../pages/profile/profile.php?success=KTM berhasil diunggah! Mohon menunggu persetujuan Admin untuk mendapatkan role Mahasiswa.");
            exit;
            
        } else {
            header("Location: ../../pages/profile/profile.php?error=Terjadi kesalahan saat menyimpan data verifikasi.");
            exit;
        }
    } else {
        header("Location: ../../pages/profile/profile.php?error=Gagal mengunggah file KTM!");
        exit;
    }
} else {
    // Memblokir akses jika bukan dari method POST
    header("Location: ../../pages/profile/profile.php");
    exit;
}
?>