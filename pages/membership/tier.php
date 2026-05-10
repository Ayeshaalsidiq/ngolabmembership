<?php
require_once '../../config/database.php';
$page_title = 'Tier Membership - Ngo+lab Membership';
$active_page = 'membership';
include_once '../../includes/header.php';

// Ambil data user
$user_id = 1;
$user_points = 0;
$user_tier = 'Silver';

$stmt = $conn->prepare("SELECT nama, poin, tier FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_name = $row['nama'];
    $user_points = $row['poin'];
    $user_tier = $row['tier'];
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

// Hitung progress
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

<style>
    /* Tema Visual Ngo+lab (Berdasarkan Gambar Desain) */
    :root {
        --ngolab-brown: #5A3E31;
        --ngolab-orange: #F37021;
        --ngolab-bg: #F4F6F9;
        --ngolab-text: #333333;
        --ngolab-text-light: #666666;
        --border-radius-xl: 20px;
        --border-radius-lg: 16px;
    }

    body {
        background-color: var(--ngolab-bg);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--ngolab-text);
    }

    .tier-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    .tier-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--ngolab-text-light);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: color 0.2s;
    }
    .tier-back-link:hover {
        color: var(--ngolab-orange);
    }

    /* Hero Section (Banner Cokelat) */
    .tier-hero {
        background-color: var(--ngolab-brown);
        border-radius: var(--border-radius-xl);
        padding: 2.5rem;
        color: white;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        box-shadow: 0 10px 30px rgba(90, 62, 49, 0.15);
    }

    .tier-hero-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .tier-hero-badge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        border: 4px solid rgba(255,255,255,0.2);
    }

    .tier-hero-info {
        flex: 1;
    }

    .tier-hero-label {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.8);
        margin: 0 0 0.5rem 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .tier-hero-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .tier-hero-points {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.15);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    /* Progress Bar Mirip Desain Kiri Bawah */
    .tier-hero-progress {
        background: rgba(0,0,0,0.2);
        padding: 1.5rem;
        border-radius: var(--border-radius-lg);
    }

    .tier-progress-labels {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .tier-progress-labels span:last-child {
        color: var(--ngolab-orange);
    }

    .tier-progress-track {
        height: 12px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        overflow: hidden;
        margin-bottom: 0.8rem;
    }

    .tier-progress-fill {
        height: 100%;
        background: var(--ngolab-orange); /* Oranye khas */
        border-radius: 50px;
        transition: width 1s ease-in-out;
    }

    .tier-progress-desc {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.7);
        margin: 0;
    }

    .tier-progress-desc a {
        color: rgba(255,255,255,0.9);
        text-decoration: underline;
        margin-left: 5px;
    }

    /* Section & Cards */
    .tier-section-title {
        font-size: 1.5rem;
        color: var(--ngolab-text);
        margin: 3rem 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
    }
    
    .tier-section-title i {
        color: var(--ngolab-orange);
    }

    .tier-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .tier-card {
        background: white;
        border-radius: var(--border-radius-xl);
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        position: relative;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 2px solid transparent;
    }

    .tier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .tier-card-active {
        border-color: var(--ngolab-orange);
    }

    .tier-card-ribbon {
        position: absolute;
        top: 1.2rem;
        right: -2.2rem;
        background: var(--ngolab-orange);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        padding: 0.3rem 2.5rem;
        transform: rotate(45deg);
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .tier-card-header {
        padding: 2rem 1.5rem;
        color: white;
        text-align: center;
    }

    .tier-card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
    }

    .tier-card-name {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .tier-card-range {
        margin: 0.5rem 0 0 0;
        font-size: 0.95rem;
        background: rgba(0,0,0,0.15);
        display: inline-block;
        padding: 0.3rem 1rem;
        border-radius: 50px;
    }

    .tier-card-body {
        padding: 2rem 1.5rem;
    }

    .tier-card-benefit-title {
        margin: 0 0 1.2rem 0;
        color: var(--ngolab-text);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .tier-benefit-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem 0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .tier-benefit-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        font-size: 0.95rem;
        color: var(--ngolab-text-light);
        line-height: 1.5;
    }

    .tier-benefit-list i {
        color: var(--ngolab-orange);
        font-size: 1.2rem;
        margin-top: 0.1rem;
    }

    .tier-card-status {
        padding: 1rem;
        border-radius: var(--border-radius-lg);
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .tier-card-status.active {
        background: #FFF1E8;
        color: var(--ngolab-orange);
    }

    .tier-card-status.passed {
        background: #E6F4EA;
        color: #1E8E3E;
    }

    .tier-card-status.locked {
        background: #F1F3F4;
        color: #5F6368;
    }

    /* Tips Section */
    .tier-tips-card {
        background: white;
        border-radius: var(--border-radius-xl);
        padding: 2.5rem;
        margin-top: 3rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .tier-tips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .tier-tip-item {
        text-align: center;
    }

    .tier-tip-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.2rem auto;
    }

    .tier-tip-item h4 {
        margin: 0 0 0.8rem 0;
        color: var(--ngolab-text);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .tier-tip-item p {
        margin: 0;
        color: var(--ngolab-text-light);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .tier-hero {
            padding: 1.5rem;
        }
        .tier-hero-content {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="tier-container">
    <!-- Back Navigation -->
    <a href="../dashboard/index.php" class="tier-back-link">
        <i class="ph ph-arrow-left"></i> Kembali ke Dashboard
    </a>

    <!-- Hero Progress Section (Mengadaptasi desain card progress di gambar) -->
    <div class="tier-hero">
        <div class="tier-hero-content">
            <div class="tier-hero-badge" style="background: <?= $tiers[$current_tier_idx]['gradient'] ?>;">
                <i class="ph-fill <?= $tiers[$current_tier_idx]['icon'] ?>"></i>
            </div>
            <div class="tier-hero-info">
                <p class="tier-hero-label">Tier Anda Saat Ini</p>
                <h1 class="tier-hero-title"><?= $user_tier ?> Member</h1>
                <p class="tier-hero-points"><i class="ph-fill ph-star" style="color: #FBBF24;"></i> <?= number_format($user_points) ?> Poin Terkumpul</p>
            </div>
        </div>
        
        <?php if ($next_tier): ?>
        <div class="tier-hero-progress">
            <div class="tier-progress-labels">
                <span><?= $user_tier ?></span>
                <span><?= $next_tier ?></span>
            </div>
            <div class="tier-progress-track">
                <!-- Fill warna oranye sesuai gambar -->
                <div class="tier-progress-fill" style="width: <?= $progress_percent ?>%;"></div>
            </div>
            <p class="tier-progress-desc">
                Kumpulkan <?= number_format($points_to_next) ?> poin lagi untuk naik ke <?= $next_tier ?> Tier. 
                <a href="#perbandingan">Lihat Detail &rarr;</a>
            </p>
        </div>
        <?php else: ?>
        <div class="tier-hero-progress" style="text-align: center;">
            <div class="tier-hero-points" style="background: rgba(255,255,255,0.2); padding: 1rem 2rem;">
                <i class="ph-fill ph-crown" style="color: #FBBF24;"></i> Selamat! Anda berada di tier tertinggi.
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
        <div class="tier-card <?= $is_current ? 'tier-card-active' : '' ?>">
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
                    Benefit Tier
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
        <h2 class="tier-section-title" style="margin-top: 0;"><i class="ph-fill ph-lightbulb"></i> Cara Mendapatkan Poin</h2>
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