<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/database.php';

// Jika belum login, biarkan sebagai tamu (Guest)
$is_logged_in = isset($_SESSION['user_id']);

$page_title = 'Dashboard - Ngo+lab Membership';
$active_page = 'dashboard';
include_once '../../includes/header.php';

$user_id = $is_logged_in ? $_SESSION['user_id'] : 0;
$user_name = "Pengunjung";
$user_points = 0;
$user_tier = "Silver";
$user_role = "Pengunjung";
$user_avatar = 'https://ui-avatars.com/api/?name=Guest&background=e2e8f0&color=475569';

if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($user = $res->fetch_assoc()) {
    $user_name = $user['nama'];
    $user_points = $user['poin'];
    $user_tier = $user['tier'];
    $user_role = $user['role'];
    $user_avatar = !empty($user['foto_profile']) ? $user['foto_profile'] : 'https://ui-avatars.com/api/?name=' . urlencode($user_name) . '&background=f37021&color=fff';
}

// Logika tier progress
$progress_percent = 0;
$next_tier = "Gold";
$points_needed = 0;

if ($user_tier == 'Silver') {
    $progress_percent = min(100, ($user_points / 500) * 100);
    $next_tier = "Gold";
    $points_needed = 500 - $user_points;
} else if ($user_tier == 'Gold') {
    $progress_percent = min(100, (($user_points - 500) / 1000) * 100);
    $next_tier = "Platinum";
    $points_needed = 1500 - $user_points;
} else {
    $progress_percent = 100;
    $next_tier = "Platinum";
    $points_needed = 0;
}
}
?>
<div class="container">
    <!-- Hero Section -->
    <div class="dashboard-hero">
        <div class="hero-profile">
            <img src="<?= $user_avatar ?>" alt="Profile">
            <div>
                <h1 class="hero-name">Halo, <?= htmlspecialchars($user_name) ?>!</h1>
                <p class="hero-subtitle">Selamat datang di Ngolab Dashboard</p>
                <div class="badge-gold">
                    <i class="ph-fill ph-medal"></i> <?= strtoupper($user_tier) ?> MEMBER
                </div>
            </div>
        </div>
        <div class="points-card">
            <div class="points-header">
                <span>SALDO POIN</span>
                <i class="ph ph-gift text-primary" style="font-size: 20px;"></i>
            </div>
            <div class="points-value"><?= number_format($user_points) ?><span>Pts</span></div>
            <a href="../rewards/rewards.php" class="btn btn-primary w-100" style="width: 100%;">
                <i class="ph ph-lightning"></i> Tukar Poin Sekarang
            </a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Tier Membership -->
            <a href="../membership/tier.php" class="tier-card-link">
                <div class="sidebar-card dark">
                    <div class="card-header">Tier Membership <i class="ph ph-arrow-right" style="margin-left: auto; font-size: 18px; opacity: 0.7;"></i></div>
                    <div class="tier-header">
                        <span><?= $user_tier ?></span>
                        <span class="text-primary"><?= isset($next_tier) ? $next_tier : '' ?></span>
                    </div>
                    <div class="progress-bg">
                        <div class="progress-bar" style="width: <?= $progress_percent ?>%;"></div>
                    </div>
                    <?php if(isset($points_needed) && $points_needed > 0): ?>
                        <div class="tier-desc">Kumpulkan <?= $points_needed ?> poin lagi untuk naik ke <?= $next_tier ?> Tier. <span style="text-decoration: underline; opacity: 0.9;">Lihat Detail →</span></div>
                    <?php else: ?>
                        <div class="tier-desc">Anda berada di tier tertinggi! <span style="text-decoration: underline; opacity: 0.9;">Lihat Detail →</span></div>
                    <?php endif; ?>
                </div>
            </a>
        </div>

        <!-- Main Column -->
        <div class="main-column">
            <!-- Katalog Hadiah (Dynamic) -->
            <div class="section-header">
                <h2 class="section-title">Katalog Hadiah Tersedia</h2>
                <a href="../rewards/rewards.php" class="link-primary">Lihat Semua <i class="ph ph-caret-right"></i></a>
            </div>
            <div class="grid-2">
                <?php
                $res_rewards = $conn->query("SELECT * FROM rewards LIMIT 2");
                while($rw = $res_rewards->fetch_assoc()):
                ?>
                <div class="item-card">
                    <div class="item-img-box">
                        <img src="<?= htmlspecialchars($rw['gambar']) ?>" alt="<?= htmlspecialchars($rw['nama_reward']) ?>">
                        <div class="badge-top-right"><?= number_format($rw['poin_dibutuhkan']) ?> Pts</div>
                    </div>
                    <div class="item-card-body">
                        <div class="item-title"><?= htmlspecialchars($rw['nama_reward']) ?></div>
                        <a href="../rewards/rewards.php" class="item-arrow"><i class="ph ph-caret-right"></i></a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- Aktivitas Terakhir (Dynamic) -->
            <div class="activity-card">
                <div class="section-header">
                    <h2 class="section-title">Aktivitas Terakhir</h2>
                </div>
                <div class="activity-list">
                    <?php if(isset($user_id)): 
                        $res_act = $conn->query("SELECT * FROM activities WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3");
                        if($res_act->num_rows > 0):
                            while($act = $res_act->fetch_assoc()):
                                $is_positive = $act['jumlah_poin'] > 0;
                    ?>
                        <div class="activity-item">
                            <div class="activity-icon <?= $is_positive ? 'green' : 'red' ?>"><i class="ph <?= $is_positive ? 'ph-gift' : 'ph-lightning' ?>"></i></div>
                            <div class="activity-details">
                                <div class="activity-name"><?= htmlspecialchars($act['jenis_aktivitas']) ?></div>
                                <div class="activity-time"><i class="ph ph-clock"></i> <?= date('d M Y, H:i', strtotime($act['created_at'])) ?></div>
                            </div>
                            <div class="activity-amount <?= $is_positive ? 'positive' : 'negative' ?>">
                                <?= $is_positive ? '+' : '' ?><?= $act['jumlah_poin'] ?> Pts
                            </div>
                        </div>
                    <?php 
                            endwhile;
                        else:
                            echo "<p class='text-muted'>Belum ada aktivitas.</p>";
                        endif;
                    else: ?>
                        <p class="text-muted">Silakan <a href="../auth/register.php">masuk</a> untuk melihat aktivitas Anda.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
