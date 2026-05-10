<?php
require_once '../../config/database.php';
$page_title = 'Rewards - Ngo+lab Membership';
$active_page = 'rewards';
include_once '../../includes/header.php';

$user_points = 0;
$user_tier = "Silver";
$user_id = 1;

$stmt = $conn->prepare("SELECT poin, tier FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_points = $row['poin'];
    $user_tier = $row['tier'];
}
?>

<!-- Main Content -->
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; margin-top: 24px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: var(--secondary); margin-bottom: 8px;">Katalog Hadiah</h1>
            <p class="text-muted">Tukar poinmu dengan berbagai penawaran menarik.</p>
        </div>
        <div class="points-card" style="min-width: auto; padding: 16px 24px; display: flex; align-items: center; gap: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
            <div style="width: 56px; height: 56px; background: var(--primary-light); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center;">
                <i class="ph ph-gift text-primary" style="font-size: 32px;"></i>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 4px;">TOTAL POIN SAYA</div>
                <div style="font-size: 32px; font-weight: 800; color: var(--secondary); line-height: 1;"><?= number_format($user_points) ?><span style="font-size: 14px; color: var(--primary); margin-left: 4px;">Pts</span></div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 12px; margin-bottom: 32px;">
        <button class="btn btn-secondary" style="padding: 12px 32px;">Semua Katalog</button>
        <button class="btn btn-outline" style="padding: 12px 32px;">Voucher Saya</button>
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

    <div class="grid-3">
        <?php
        $res_rewards = $conn->query("SELECT * FROM rewards");
        if($res_rewards->num_rows > 0):
            while($rw = $res_rewards->fetch_assoc()):
                // Logic cek cukup poin
                $is_enough_points = $user_points >= $rw['poin_dibutuhkan'];
                
                // Styling badge tier minimal
                $tier_min = $rw['tier_minimal'];
                $badge_bg = '#94a3b8'; // Silver default
                if($tier_min == 'Gold') $badge_bg = '#eab308';
                if($tier_min == 'Platinum') $badge_bg = '#334155';
        ?>
        <div class="item-card">
            <div class="item-img-box">
                <img src="<?= htmlspecialchars($rw['gambar']) ?>" alt="<?= htmlspecialchars($rw['nama_reward']) ?>">
                <div class="badge-top-right" style="background: <?= $badge_bg ?>; color: white;"><i class="ph-fill ph-star"></i> <?= $tier_min ?></div>
            </div>
            <div class="item-card-body" style="display: block; text-align: center;">
                <div class="item-title" style="margin-bottom: 8px;"><?= htmlspecialchars($rw['nama_reward']) ?></div>
                <div style="font-size: 16px; font-weight: 800; color: var(--primary); margin-bottom: 16px;"><?= number_format($rw['poin_dibutuhkan']) ?> Pts</div>
                
                <?php if($is_enough_points): ?>
                    <form action="../../backend/rewards/redeem_process.php" method="POST" onsubmit="return confirm('Tukar poin dengan hadiah ini?');">
                        <input type="hidden" name="reward_id" value="<?= $rw['id'] ?>">
                        <button type="submit" class="btn btn-danger w-100" style="width: 100%;">Tukar <i class="ph ph-caret-right"></i></button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-outline-danger w-100" style="width: 100%; border: 1px solid var(--danger); color: var(--danger); background: transparent; border-radius: 99px; padding: 10px; cursor: not-allowed; font-weight: 600;" disabled>Poin Tidak Cukup</button>
                <?php endif; ?>
            </div>
        </div>
        <?php 
            endwhile;
        else:
            echo "<p>Katalog hadiah belum tersedia.</p>";
        endif; 
        ?>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
