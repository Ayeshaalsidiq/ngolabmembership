<?php
$page_title = 'Masuk - Ngo+lab Membership';
include_once '../../includes/header.php';
?>

<!-- Main Content -->
<div class="container">
    <div class="register-container" style="max-width: 450px; margin: 0 auto; padding: 40px 0;">
        <div class="register-header" style="text-align: center; margin-bottom: 32px;">
            <h1 style="font-size: 32px; font-weight: 800; color: var(--secondary); margin-bottom: 8px;">Masuk Member</h1>
            <p class="text-muted">Masuk untuk mengakses poin, reward, dan status membership Anda.</p>
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
        
        <form action="../../backend/auth/login_process.php" method="POST" class="sidebar-card">
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 8px;">Email</label>
                <input type="email" name="email" class="form-control" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border);" placeholder="nama@email.com" required>
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 8px;">Kata Sandi</label>
                <input type="password" name="password" class="form-control" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border);" placeholder="********" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100" style="width: 100%; padding: 14px; font-size: 16px;">
                Masuk
            </button>
            
            <p style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
                Belum punya akun? <a href="register.php" style="color: var(--primary); font-weight: 700;">Daftar Sekarang</a>
            </p>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
