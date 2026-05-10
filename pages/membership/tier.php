<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/database.php';
$page_title = 'Tier Membership - Ngo+lab Membership';
$active_page = 'membership';
include_once '../../includes/header.php';

// Ambil data user dari session secara dinamis
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : 0;

$user_points = 0;
$user_tier = 'Silver';
$user_name = 'Guest';

if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT nama, poin, tier FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user_name = $row['nama'];
        $user_points = $row['poin'];
        $user_tier = $row['tier'];
    }
}

// Definisi tier
$tiers = [
    [
        'name' => 'Silver',
        'icon' => 'ph-medal',
        'min_points' => 0,
        'max_points' => 499,
        'color' => '#94a3b8',
        'gradient' => 'linear-gradient(135deg, #cbd5e1, #94a3b8)',
        'benefits' => [
            'Akses katalog reward dasar',
            'Mendapatkan poin setiap pembelian',
            'Notifikasi promo reguler',
            'Akumulasi poin tanpa batas waktu'
        ]
    ],
    [
        'name' => 'Gold',
        'icon' => 'ph-medal',
        'min_points' => 500,
        'max_points' => 1499,
        'color' => '#f59e0b',
        'gradient' => 'linear-gradient(135deg, #fbbf24, #f59e0b)',
        'benefits' => [
            'Semua benefit Silver',
            'Diskon 10% untuk semua menu',
            'Akses reward eksklusif Gold',
            'Double poin saat event spesial',
            'Prioritas promo terbatas'
        ]
    ],
    [
        'name' => 'Platinum',
        'icon' => 'ph-crown',
        'min_points' => 1500,
        'max_points' => 999999,
        'color' => '#8b5cf6',
        'gradient' => 'linear-gradient(135deg, #a78bfa, #7c3aed)',
        'benefits' => [
            'Semua benefit Gold',
            'Diskon 20% untuk semua menu',
            'Akses semua reward tersedia',
            'Prioritas event & undangan VIP',
            'Free birthday treat spesial',
            'Triple poin saat event spesial'
        ]
    ]
];

// Hitung progress berdasarkan tier user saat ini
if ($user_tier == 'Silver') {
    $current_tier_idx = 0;
    $progress_percent = min(100, ($user_points / 500) * 100);
    $points_to_next = max(0, 500 - $user_points);
    $next_tier = 'Gold';
} else if ($user_tier == 'Gold') {
    $current_tier_idx = 1;
    $progress_percent = min(100, (($user_points - 500) / 1000) * 100);
    $points_to_next = max(0, 1500 - $user_points);
    $next_tier = 'Platinum';
} else {
    $current_tier_idx = 2;
    $progress_percent = 100;
    $points_to_next = 0;
    $next_tier = null;
}
?>

<div class="container">
    <!-- Back Navigation -->
    <a href="../dashboard/index.php" class="tier-back-link">
        <i class="ph ph-arrow-left"></i> Kembali ke Dashboard
    </a>

    <!-- Hero Progress Section -->
    <div class="tier-hero">
        <div class="tier-hero-content">
            <div class="tier-hero-badge" style="background: <?= $tiers[$current_tier_idx]['gradient'] ?>;">
                <i class="ph-fill <?= $tiers[$current_tier_idx]['icon'] ?>"></i>
            </div>
            <div>
                <p class="tier-hero-label">Tier Anda Saat Ini</p>
                <h1 class="tier-hero-title"><?= $user_tier ?> Member</h1>
                <p class="tier-hero-points"><i class="ph-fill ph-star"></i> <?= number_format($user_points) ?> Poin Terkumpul</p>
            </div>
        </div>
        
        <?php if ($next_tier): ?>
        <div>
            <div class="tier-progress-labels">
                <span><?= $user_tier ?></span>
                <span style="color: var(--primary);"><?= $next_tier ?></span>
            </div>
            <div class="tier-progress-track">
                <div class="tier-progress-fill" style="width: <?= $progress_percent ?>%; background: <?= $tiers[$current_tier_idx]['gradient'] ?>;"></div>
            </div>
            <p class="tier-progress-need">
                <i class="ph-fill ph-info"></i> Kumpulkan <?= number_format($points_to_next) ?> poin lagi untuk naik ke <?= $next_tier ?> Tier.
            </p>
        </div>
        <?php else: ?>
        <div style="text-align: center;">
            <div class="tier-max-badge">
                <i class="ph-fill ph-crown"></i> Selamat! Anda berada di tier tertinggi.
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tier Comparison Cards -->
    <h2 class="tier-section-title" id="perbandingan">
        <i class="ph-fill ph-ranking"></i> Perbandingan Tier Membership
    </h2>
    <div class="tier-cards-grid">
        <?php foreach ($tiers as $idx => $tier): 
            $is_current = ($tier['name'] == $user_tier);
            $is_locked = ($idx > $current_tier_idx);
            $is_passed = ($idx < $current_tier_idx);
        ?>
        <div class="tier-card <?= $is_current ? 'tier-card-active' : '' ?> <?= $is_locked ? 'tier-card-locked' : '' ?>">
            <?php if ($is_current): ?>
                <div class="tier-card-ribbon">TIER ANDA</div>
            <?php endif; ?>
            
            <div class="tier-card-header" style="background: <?= $tier['gradient'] ?>;">
                <div class="tier-card-icon">
                    <i class="ph-fill <?= $tier['icon'] ?>"></i>
                </div>
                <h3 class="tier-card-name"><?= $tier['name'] ?></h3>
                <p class="tier-card-range"><?= number_format($tier['min_points']) ?><?= $tier['name'] != 'Platinum' ? ' - ' . number_format($tier['max_points']) : '+' ?> Poin</p>
            </div>
            
            <div class="tier-card-body">
                <h4 class="tier-card-benefit-title">
                    <i class="ph-fill ph-star"></i> Benefit Tier
                </h4>
                <ul class="tier-benefit-list">
                    <?php foreach ($tier['benefits'] as $benefit): ?>
                    <li>
                        <i class="ph-fill ph-check-circle"></i>
                        <span><?= $benefit ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <?php if ($is_current): ?>
                    <div class="tier-card-status active">
                        <i class="ph-fill ph-check-circle"></i> Tier Aktif Saat Ini
                    </div>
                <?php elseif ($is_passed): ?>
                    <div class="tier-card-status passed">
                        <i class="ph-fill ph-check-circle"></i> Telah Tercapai
                    </div>
                <?php else: ?>
                    <div class="tier-card-status locked">
                        <i class="ph ph-lock"></i> Butuh <?= number_format($tier['min_points'] - $user_points) ?> poin lagi
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- How to earn points -->
    <div class="tier-tips-card">
        <h2 class="tier-tips-title"><i class="ph-fill ph-lightbulb"></i> Cara Mendapatkan Poin</h2>
        <div class="tier-tips-grid">
            <div class="tier-tip-item">
                <div class="tier-tip-icon" style="background: #fff7ed; color: #f97316;">
                    <i class="ph-fill ph-shopping-cart"></i>
                </div>
                <h4>Pesan Menu</h4>
                <p>Dapatkan poin setiap kali membeli menu di Ngo+lab</p>
            </div>
            <div class="tier-tip-item">
                <div class="tier-tip-icon" style="background: #f0fdf4; color: #22c55e;">
                    <i class="ph-fill ph-megaphone"></i>
                </div>
                <h4>Ikuti Event</h4>
                <p>Poin bonus saat mengikuti event spesial Ngo+lab</p>
            </div>
            <div class="tier-tip-item">
                <div class="tier-tip-icon" style="background: #eff6ff; color: #3b82f6;">
                    <i class="ph-fill ph-users-three"></i>
                </div>
                <h4>Referral Teman</h4>
                <p>Ajak teman bergabung & dapatkan poin referral</p>
            </div>
            <div class="tier-tip-item">
                <div class="tier-tip-icon" style="background: #faf5ff; color: #8b5cf6;">
                    <i class="ph-fill ph-student"></i>
                </div>
                <h4>Verifikasi Mahasiswa</h4>
                <p>Bonus poin khusus untuk mahasiswa terverifikasi</p>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>