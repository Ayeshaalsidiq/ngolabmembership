<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/database.php';

$page_title = 'Menu - Ngo+lab Membership';
$active_page = 'menu';
include_once '../../includes/header.php';

// Data Menu Diperbarui dengan Deskripsi agar sesuai dengan desain
$menus = [
    // Bakso
    ['id' => 4, 'kategori' => 'Bakso', 'nama' => 'Bakso Urat Spesial', 'deskripsi' => 'Kuah kaldu gurih, urat sapi asli, sayur lengkap.', 'harga' => 28000, 'poin' => 28, 'gambar' => 'https://images.unsplash.com/photo-1588167056086-508b040bf59a?w=500&q=80', 'promo' => false],
    ['id' => 5, 'kategori' => 'Bakso', 'nama' => 'Bakso Halus Kuah', 'deskripsi' => 'Bakso daging sapi halus lembut dengan kuah kaldu sapi bening.', 'harga' => 20000, 'poin' => 20, 'gambar' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=500&q=80', 'promo' => false],
    ['id' => 6, 'kategori' => 'Bakso', 'nama' => 'Bakso Biasa Jumbo', 'deskripsi' => 'Porsi lebih besar, ekstra mi kuning dan bihun.', 'harga' => 22000, 'poin' => 22, 'gambar' => 'https://images.unsplash.com/photo-1568909344668-6f14a07b56a0?w=500&q=80', 'promo' => false],
    
    // Mie Yamin
    ['id' => 1, 'kategori' => 'Mie Yamin', 'nama' => 'Mie Yamin Manis', 'deskripsi' => 'Ayam cincang melimpah, pangsit rebus nikmat.', 'harga' => 25000, 'poin' => 25, 'gambar' => 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=500&q=80', 'promo' => false],
    ['id' => 2, 'kategori' => 'Mie Yamin', 'nama' => 'Mie Yamin Asin Pedas', 'deskripsi' => 'Level pedas bisa disesuaikan, gurih nikmat.', 'harga' => 19000, 'poin' => 19, 'gambar' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500&q=80', 'promo' => false],
    
    // Minuman (Juice & Sparkling)
    ['id' => 11, 'kategori' => 'Minuman', 'nama' => 'Juice Stroberi Fresh', 'deskripsi' => '100% stroberi peras murni tanpa pengawet.', 'harga' => 16000, 'poin' => 16, 'gambar' => 'https://images.unsplash.com/photo-1568909344668-6f14a07b56a0?w=500&q=80', 'promo' => false],
    ['id' => 9, 'kategori' => 'Minuman', 'nama' => 'Ocean Blue Sparkling', 'deskripsi' => 'Minuman soda segar dengan sirup blue ocean dan selasih.', 'harga' => 18000, 'poin' => 18, 'gambar' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&q=80', 'promo' => false],
    ['id' => 12, 'kategori' => 'Minuman', 'nama' => 'Es Teh Manis', 'deskripsi' => 'Teh melati seduh segar dengan gula asli.', 'harga' => 5000, 'poin' => 5, 'gambar' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&q=80', 'promo' => false],
    
    // Es Krim
    ['id' => 7, 'kategori' => 'Es Krim', 'nama' => 'Vanilla Sundae', 'deskripsi' => 'Es krim vanilla lembut dengan saus cokelat lumer.', 'harga' => 15000, 'poin' => 15, 'gambar' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=500&q=80', 'promo' => false],
    ['id' => 8, 'kategori' => 'Es Krim', 'nama' => 'Chocolate Cone', 'deskripsi' => 'Es krim cokelat premium dalam cone renyah.', 'harga' => 12000, 'poin' => 12, 'gambar' => 'https://images.unsplash.com/photo-1553787762-b5f52ce15c0e?w=500&q=80', 'promo' => false],

    // Promo (Contoh)
    ['id' => 13, 'kategori' => 'Promo', 'nama' => 'Paket Kenyang 1', 'deskripsi' => 'Bakso Urat Spesial + Juice Stroberi. Ekstra Poin!', 'harga' => 40000, 'poin' => 50, 'gambar' => '', 'promo' => true],
];
?>

<style>
    /* Styling mengikuti desain mockup referensi */
    body {
        background-color: #FAFAFA;
    }
    
    .menu-page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 24px 80px 24px;
    }

    /* Header & Search */
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .catalog-title-group h1 {
        font-size: 32px;
        font-weight: 800;
        color: #5A3E31; /* Cokelat khas Ngolab */
        margin-bottom: 8px;
    }
    .catalog-title-group p {
        font-size: 15px;
        color: #666;
        max-width: 500px;
        line-height: 1.5;
    }
    .search-box {
        position: relative;
        min-width: 300px;
    }
    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 18px;
    }
    .search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 1px solid #E5E7EB;
        border-radius: 50px;
        background: white;
        font-size: 14px;
        color: #333;
        outline: none;
        transition: border-color 0.2s;
    }
    .search-input:focus {
        border-color: #F37021;
    }

    /* Filter Pills */
    .filter-scroll {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 12px;
        margin-bottom: 32px;
        scrollbar-width: none; /* Firefox */
    }
    .filter-scroll::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }
    .filter-pill {
        white-space: nowrap;
        padding: 10px 20px;
        border-radius: 50px;
        background: white;
        border: 1px solid #E5E7EB;
        color: #666;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .filter-pill:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }
    .filter-pill.active {
        background: #5A3E31;
        border-color: #5A3E31;
        color: white;
    }
    .filter-pill.active i {
        color: white;
    }

    /* Grid Layout */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    /* Card Styling */
    .menu-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #F3F4F6;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
    }

    /* Card Image & Badges */
    .card-img-box {
        position: relative;
        height: 200px;
        background: #F3F4F6; /* Placeholder color */
        padding: 12px;
    }
    .card-img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
    }
    .badge-points {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #5A3E31;
        color: white;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .badge-promo {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #EF4444; /* Merah promo */
        color: white;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Card Body */
    .card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .card-category {
        color: #F37021;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .card-title {
        font-size: 20px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
        line-height: 1.3;
    }
    .card-desc {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.5;
        margin-bottom: 24px;
        flex: 1;
    }

    /* Card Footer (Price & Action) */
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-top: 16px;
        border-top: 1px dashed #E5E7EB;
    }
    .price-label {
        font-size: 12px;
        color: #9CA3AF;
        margin-bottom: 2px;
        font-weight: 500;
    }
    .price-value {
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }

    /* Tombol Pesan (Tetap ada agar fungsi berjalan) */
    .btn-add {
        background: #FFF0E6;
        color: #F37021;
        border: none;
        padding: 10px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-add:hover {
        background: #F37021;
        color: white;
    }

    /* Alert Styling */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-success { background: #E6F4EA; color: #1E8E3E; border: 1px solid #bbf7d0; }
    .alert-error { background: #FCE8E6; color: #D93025; border: 1px solid #fecaca; }

    /* Pesan Data Kosong */
    .no-results {
        display: none;
        text-align: center;
        padding: 40px;
        color: #6B7280;
        font-weight: 600;
        width: 100%;
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .catalog-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .search-box {
            width: 100%;
        }
        .menu-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="menu-page-container">

    <!-- Notifikasi Pesan -->
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i class="ph-fill ph-check-circle" style="font-size: 24px;"></i>
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="ph-fill ph-warning-circle" style="font-size: 24px;"></i>
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="catalog-header">
        <div class="catalog-title-group">
            <h1>Katalog Menu Ngolab</h1>
            <p>Lihat daftar menu lengkap dan dapatkan <strong>Poin Member</strong> setiap kali kamu memesannya secara langsung di kasir!</p>
        </div>
        <div class="search-box">
            <i class="ph ph-magnifying-glass"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari menu favoritmu...">
        </div>
    </div>

    <!-- Filter Pills dengan Data Category -->
    <div class="filter-scroll">
        <a href="#" class="filter-pill active" data-category="all">
            <i class="ph-fill ph-fork-knife"></i> Semua Menu
        </a>
        <a href="#" class="filter-pill" data-category="Bakso">
            <i class="ph ph-bowl-food"></i> Bakso
        </a>
        <a href="#" class="filter-pill" data-category="Mie Yamin">
            <i class="ph ph-noodle"></i> Mie Yamin
        </a>
        <a href="#" class="filter-pill" data-category="Es Krim">
            <i class="ph ph-ice-cream"></i> Es Krim
        </a>
        <a href="#" class="filter-pill" data-category="Minuman">
            <i class="ph ph-brandy"></i> Minuman
        </a>
        <a href="#" class="filter-pill" data-category="Promo">
            <i class="ph ph-tag"></i> Promo
        </a>
    </div>

    <!-- Menu Grid -->
    <div class="menu-grid" id="menuGrid">
        <?php foreach ($menus as $item): ?>
        <!-- Tambahkan Atribut data-category & data-promo pada menu-card -->
        <div class="menu-card" data-category="<?= htmlspecialchars($item['kategori']) ?>" data-promo="<?= $item['promo'] ? 'true' : 'false' ?>">
            <!-- Image & Badges -->
            <div class="card-img-box">
                <?php if (!empty($item['gambar'])): ?>
                    <img src="<?= htmlspecialchars($item['gambar']) ?>" alt=") ?>]" class="card-img">
                <?php endif; ?>
                
                <div class="badge-points">
                    <i class="ph-fill ph-tag"></i> +<?= $item['poin'] ?> Poin
                </div>

                <?php if($item['promo']): ?>
                    <div class="badge-promo">
                        <i class="ph-fill ph-tag"></i> Promo
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Card Body -->
            <div class="card-body">
                <div class="card-category"><?= htmlspecialchars($item['kategori']) ?></div>
                <h3 class="card-title"><?= htmlspecialchars($item['nama']) ?></h3>
                <p class="card-desc"><?= htmlspecialchars($item['deskripsi']) ?></p>
                
                <!-- Card Footer (Price & Order Button) -->
                <div class="card-footer">
                    <div>
                        <div class="price-label">Harga</div>
                        <div class="price-value">Rp <?= $item['harga'] / 1000 ?>k</div>
                    </div>
                    
                    <!-- Form Pembelian tersembunyi namun tombolnya tampil -->
                    <form action="../../backend/menu/proses_pesanan.php" method="POST" onsubmit="return confirm('Pesan <?= htmlspecialchars($item['nama']) ?> seharga Rp <?= number_format($item['harga'], 0, ',', '.') ?>?');" style="margin: 0;">
                        <input type="hidden" name="menu_id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn-add">
                            Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Pesan ketika pencarian/filter tidak ditemukan -->
        <div class="no-results" id="noResults">
            <i class="ph ph-smiley-sad" style="font-size: 40px; margin-bottom: 12px; color: #D1D5DB;"></i><br>
            Maaf, menu yang kamu cari tidak ditemukan.
        </div>
    </div>

</div>

<!-- JavaScript untuk Filter dan Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterPills = document.querySelectorAll('.filter-pill');
    const menuCards = document.querySelectorAll('.menu-card');
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResults');

    // Fungsi utama untuk memfilter menu
    function filterMenu() {
        const activeCategory = document.querySelector('.filter-pill.active').getAttribute('data-category');
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;

        menuCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const category = card.getAttribute('data-category');
            const isPromo = card.getAttribute('data-promo') === 'true';

            const matchSearch = title.includes(searchTerm);
            let matchCategory = false;

            if (activeCategory === 'all') {
                matchCategory = true;
            } else if (activeCategory === 'Promo') {
                matchCategory = isPromo;
            } else {
                matchCategory = (category === activeCategory);
            }

            // Tampilkan atau Sembunyikan
            if (matchSearch && matchCategory) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Tampilkan pesan "Tidak ditemukan" jika tidak ada menu yang cocok
        if (visibleCount === 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }

    // Event Listener untuk tombol Kategori (Filter Pills)
    filterPills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hapus status aktif dari semua tombol
            filterPills.forEach(p => p.classList.remove('active'));
            // Tambahkan status aktif pada tombol yang diklik
            this.classList.add('active');
            
            // Jalankan fungsi filter
            filterMenu();
        });
    });

    // Event Listener untuk Kolom Pencarian (Search Input)
    searchInput.addEventListener('input', filterMenu);
});
</script>

<?php include_once '../../includes/footer.php'; ?>