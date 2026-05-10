<?php
require_once '../../config/database.php';
$page_title = 'Profil - Ngo+lab Membership';
$active_page = 'profile';
include_once '../../includes/header.php';

// Cek login ditiadakan sementara, gunakan user_id = 1
$user_id = 1;
$stmt = $conn->prepare("SELECT nama, whatsapp, email, tier FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_name = $row['nama'];
    $user_whatsapp = $row['whatsapp'];
    $user_email = $row['email'];
    $user_tier = $row['tier'];
} else {
    // Error handling
    $user_name = "User";
}
?>

<!-- Main Content -->
<div class="container">
    <div class="dashboard-grid" style="grid-template-columns: 350px 1fr;">
        <!-- Profile Sidebar -->
        <div class="sidebar">
            <div class="sidebar-card" style="text-align: center; padding-top: 40px;">
                <div style="position: relative; display: inline-block; margin-bottom: 24px;">
                    <img src="https://images.unsplash.com/photo-1531384441138-2736e62e0919?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Profile" style="width: 120px; height: 120px; border-radius: var(--radius-full); object-fit: cover; box-shadow: var(--shadow-md);">
                    <button style="position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; border-radius: var(--radius-full); background: var(--primary); color: white; border: 3px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="ph-fill ph-camera"></i></button>
                </div>
                <h2 style="font-size: 24px; font-weight: 800; color: var(--secondary); margin-bottom: 8px;"><?= htmlspecialchars($user_name) ?></h2>
                <div style="display: inline-block; background: var(--background); padding: 4px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 24px;">Member Ngo+lab</div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; text-align: left;">
                    <div style="background: var(--surface); border: 1px solid var(--border); padding: 16px; border-radius: var(--radius-md);">
                        <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); margin-bottom: 4px;">TIER</div>
                        <div style="font-weight: 700; color: #facc15; display: flex; align-items: center; gap: 4px;"><i class="ph-fill ph-medal"></i> <?= htmlspecialchars($user_tier) ?></div>
                    </div>
                    <div style="background: var(--surface); border: 1px solid var(--border); padding: 16px; border-radius: var(--radius-md);">
                        <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); margin-bottom: 4px;">LOKASI</div>
                        <div style="font-weight: 700; color: var(--secondary); display: flex; align-items: center; gap: 4px;"><i class="ph-fill ph-map-pin text-primary"></i> Tel-U</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="main-column">
            <div class="activity-card" style="margin-bottom: 24px;">
                <div class="section-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
                    <h2 class="section-title"><i class="ph ph-user text-primary" style="margin-right: 8px;"></i> Informasi Pribadi</h2>
                </div>
                
                <?php if(isset($_GET['success'])): ?>
                    <div style="background: #22c55e; color: white; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px; text-align: center;">
                        <?= htmlspecialchars($_GET['success']) ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div style="background: var(--danger); color: white; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px; text-align: center;">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="../../backend/profile/update_process.php" method="POST">
                    <div style="padding: 16px 0; border-bottom: 1px solid var(--border); display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($user_name) ?>" class="form-control" required>
                    </div>

                    <div style="padding: 16px 0; border-bottom: 1px solid var(--border); display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Nomor Telepon</label>
                        <input type="text" name="whatsapp" value="<?= htmlspecialchars($user_whatsapp) ?>" class="form-control" required>
                    </div>

                    <div style="padding: 16px 0; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user_email) ?>" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="margin-top: 16px; width: 100%;">Simpan Perubahan</button>
                </form>
            </div>

            <div class="activity-card">
                <div class="section-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
                    <h2 class="section-title"><i class="ph ph-shield-check text-primary" style="margin-right: 8px;"></i> Keamanan & Akun</h2>
                </div>
                
                <a href="#" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid var(--border); color: var(--secondary); font-weight: 600;">
                    <div style="display: flex; align-items: center; gap: 12px;"><i class="ph ph-lock-key" style="font-size: 20px;"></i> Ubah Kata Sandi</div>
                    <i class="ph ph-caret-right text-muted"></i>
                </a>
                
                <a href="#" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid var(--border); color: var(--secondary); font-weight: 600;">
                    <div style="display: flex; align-items: center; gap: 12px;"><i class="ph ph-receipt" style="font-size: 20px;"></i> Riwayat Transaksi</div>
                    <i class="ph ph-caret-right text-muted"></i>
                </a>

                <a href="../dashboard/index.php" style="display: flex; align-items: center; padding: 16px 0; color: var(--danger); font-weight: 600; margin-top: 8px;">
                    <i class="ph ph-sign-out" style="font-size: 20px; margin-right: 12px;"></i> Keluar (Ke Dashboard)
                </a>

                <form action="../../backend/profile/delete_process.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen? Data yang dihapus tidak bisa dikembalikan.');">
                    <button type="submit" style="background: none; border: none; width: 100%; cursor: pointer; display: flex; align-items: center; padding: 16px 0; border-top: 1px solid var(--border); color: var(--danger); font-weight: 600; text-align: left;">
                        <i class="ph ph-trash" style="font-size: 20px; margin-right: 12px;"></i> Hapus Akun Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
