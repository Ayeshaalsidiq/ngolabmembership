<?php
$page_title = 'Daftar Member - Ngo+lab Membership';
include_once '../../includes/header.php';
?>

<!-- Main Content -->
<div class="container">
    <div class="register-container">
        <div class="register-header">
            <h1>Daftar Member Baru</h1>
            <p class="text-muted">Lengkapi data diri Anda untuk bergabung menjadi keluarga Ngo+lab dan nikmati berbagai keuntungannya!</p>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div style="background: var(--danger); color: white; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px; text-align: center;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <form action="../../backend/auth/register_process.php" method="POST">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Rusdi Mahasiswa" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor WhatsApp Aktif</label>
                <input type="tel" name="whatsapp" class="form-control" placeholder="Contoh: 081234567890" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Kampus / Pribadi</label>
                <input type="email" name="email" class="form-control" placeholder="Contoh: rusdi@student.ac.id" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100" style="width: 100%; padding: 14px; font-size: 16px; margin-top: 16px;">
                Daftar Sekarang
            </button>
            
            <p class="text-center mt-4 text-muted" style="text-align: center; margin-top: 24px;">
                Sudah punya akun? <a href="../dashboard/index.php" class="text-primary font-bold">Kembali ke Dashboard</a>
            </p>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
