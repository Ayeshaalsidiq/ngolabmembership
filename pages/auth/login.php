<?php
$page_title = 'Masuk Member - Ngo+lab Membership';
include_once '../../includes/header.php';
?>

<!-- Main Content -->
<div class="container">
    <div class="register-container" style="max-width: 400px;">
        <div class="register-header">
            <h1>Selamat Datang!</h1>
            <p class="text-muted">Masuk ke akun Ngo+lab Anda untuk melihat saldo poin dan menukarkan hadiah.</p>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div style="background: var(--danger); color: white; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px; text-align: center;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['success'])): ?>
            <div style="background: var(--success); color: white; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px; text-align: center;">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>
        
        <form action="../../backend/auth/login_process.php" method="POST">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control" placeholder="Kata sandi" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100" style="width: 100%; padding: 14px; font-size: 16px; margin-top: 16px;">
                Masuk
            </button>
            
            <p class="text-center mt-4 text-muted" style="text-align: center; margin-top: 24px;">
                Belum punya akun? <a href="register.php" class="text-primary font-bold">Daftar sekarang</a>
            </p>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
