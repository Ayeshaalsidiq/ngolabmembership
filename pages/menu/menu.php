<?php
require_once '../../config/database.php';
$page_title = 'Menu - Ngo+lab Membership';
$active_page = 'menu';
include_once '../../includes/header.php';
?>

<!-- Main Content -->
<div class="container">
    <div class="section-header mt-4">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: var(--secondary); margin-bottom: 8px;">Katalog Menu</h1>
            <p class="text-muted">Dapatkan <span class="text-primary font-bold">Poin Member</span> tiap pesanan!</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <input type="text" placeholder="Cari menu favoritmu..." style="padding: 12px 20px; border-radius: var(--radius-full); border: 1px solid var(--border); width: 250px;">
            <button class="btn btn-secondary"><i class="ph ph-magnifying-glass"></i></button>
        </div>
    </div>

    <div style="display: flex; gap: 12px; margin-bottom: 32px;">
        <button class="btn btn-secondary">Semua Menu</button>
        <button class="btn btn-outline">Bakso</button>
        <button class="btn btn-outline">Mie Yamin</button>
        <button class="btn btn-outline">Minuman</button>
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
        $res_menus = $conn->query("SELECT * FROM menus");
        if($res_menus->num_rows > 0):
            while($menu = $res_menus->fetch_assoc()):
        ?>
        <div class="item-card">
            <div class="item-img-box">
                <img src="<?= htmlspecialchars($menu['gambar']) ?>" alt="<?= htmlspecialchars($menu['nama_menu']) ?>">
                <?php if($menu['is_promo']): ?>
                    <div class="badge-top-right" style="background: var(--danger); color: white;"><i class="ph-fill ph-tag"></i> PROMO</div>
                    <div class="badge-top-right" style="top: 48px; background: var(--secondary); color: white;">+<?= $menu['poin_didapat'] ?> Pts</div>
                <?php else: ?>
                    <div class="badge-top-right" style="background: var(--secondary); color: white;">+<?= $menu['poin_didapat'] ?> Pts</div>
                <?php endif; ?>
            </div>
            <div class="item-card-body" style="display: block;">
                <div style="color: var(--primary); font-size: 11px; font-weight: 800; margin-bottom: 4px;"><?= strtoupper(htmlspecialchars($menu['kategori'])) ?></div>
                <div class="item-title" style="margin-bottom: 4px;"><?= htmlspecialchars($menu['nama_menu']) ?></div>
                <div class="text-muted" style="font-size: 13px; margin-bottom: 12px;"><?= htmlspecialchars($menu['deskripsi']) ?></div>
                <div style="font-size: 18px; font-weight: 800; color: var(--secondary); margin-bottom: 12px;">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                <form action="../../backend/menu/order_process.php" method="POST" onsubmit="return confirm('Pesan menu ini?');">
                    <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                    <button type="submit" class="btn btn-primary w-100" style="width: 100%; padding: 10px;">Pesan Sekarang</button>
                </form>
            </div>
        </div>
        <?php 
            endwhile;
        else:
            echo "<p>Menu belum tersedia.</p>";
        endif;
        ?>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
