<?php
require_once '../../config/database.php';
$page_title = 'Profil - Ngo+lab Membership';
$active_page = 'profile';
include_once '../../includes/header.php';

// Cek login ditiadakan sementara, gunakan user_id = 1
$user_id = 1;
$stmt = $conn->prepare("SELECT nama, whatsapp, email, tier, role, nim, ktm_path, is_verified FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_name = $row['nama'];
    $user_whatsapp = $row['whatsapp'];
    $user_email = $row['email'];
    $user_tier = $row['tier'];
    $user_role = $row['role'];
    $user_nim = $row['nim'];
    $user_ktm = $row['ktm_path'];
    $user_verified = $row['is_verified'];
} else {
    $user_name = "User";
    $user_whatsapp = "";
    $user_email = "";
    $user_tier = "Silver";
    $user_role = "Pengunjung";
    $user_nim = "";
    $user_ktm = "";
    $user_verified = 0;
}

// Determine if we should show edit mode
$edit_mode = isset($_GET['mode']) && $_GET['mode'] === 'edit';
?>

<style>
    /* Tema Visual Ngo+lab - Clean & Modern */
    :root {
        --ngolab-brown: #5A3E31;
        --ngolab-orange: #F37021;
        --ngolab-bg: #F8FAFC; /* Lebih terang agar card putih lebih kontras */
        --ngolab-text: #1E293B;
        --ngolab-text-light: #64748B;
        --border-color: #F1F5F9;
        --border-radius-xl: 24px;
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
    }

    body {
        background-color: var(--ngolab-bg);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--ngolab-text);
        line-height: 1.6;
    }

    .profile-container {
        max-width: 1040px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 2rem;
        align-items: start;
    }

    /* Cards - Diperhalus shadow dan border-nya */
    .profile-card {
        background: #FFFFFF;
        border-radius: var(--border-radius-xl);
        border: 1px solid rgba(0, 0, 0, 0.03);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.04);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Sidebar Styles */
    .sidebar-profile-img {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .sidebar-profile-img img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 5px solid #FFFFFF;
    }

    .cam-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--ngolab-orange);
        color: white;
        border: 3px solid #FFFFFF;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(243, 112, 33, 0.3);
    }

    .cam-btn:hover {
        transform: scale(1.1);
        background: #E05D15;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ngolab-brown);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.5px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #F1F5F9;
        color: var(--ngolab-text-light);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 2rem;
    }

    .role-badge.verified {
        background: #FFF4ED;
        color: var(--ngolab-orange);
    }

    .mini-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        text-align: left;
    }

    .mini-stat-card {
        background: #F8FAFC;
        padding: 1.2rem;
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--border-color);
        transition: background 0.2s;
    }

    .mini-stat-card:hover {
        background: #F1F5F9;
    }

    .mini-stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ngolab-text-light);
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mini-stat-value {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--ngolab-text);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Main Content Styles */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ngolab-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .section-title i {
        color: var(--ngolab-orange);
        font-size: 1.4rem;
        padding: 0.4rem;
        background: #FFF4ED;
        border-radius: 10px;
    }

    .profile-info-list {
        display: flex;
        flex-direction: column;
    }

    .profile-info-item {
        display: flex;
        gap: 1.2rem;
        padding: 1.2rem 0;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
    }

    .profile-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .profile-info-item:first-child {
        padding-top: 0.5rem;
    }

    .profile-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #F8FAFC;
        color: var(--ngolab-text-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        border: 1px solid var(--border-color);
    }

    .profile-info-content {
        flex: 1;
    }

    .profile-info-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--ngolab-text-light);
        margin-bottom: 0.2rem;
    }

    .profile-info-value {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: var(--ngolab-text);
        font-size: 1.05rem;
    }

    .verified-inline-badge {
        background: #ECFDF5;
        color: #059669;
        font-size: 0.75rem;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        margin-left: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        border: 1px solid #A7F3D0;
    }

    /* Forms & Buttons */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ngolab-text);
        margin-bottom: 0.6rem;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1.2rem;
        border: 1px solid #CBD5E1;
        border-radius: var(--border-radius-md);
        font-family: inherit;
        font-size: 0.95rem;
        color: var(--ngolab-text);
        box-sizing: border-box;
        transition: all 0.2s ease;
        background: #FFFFFF;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--ngolab-orange);
        box-shadow: 0 0 0 3px rgba(243, 112, 33, 0.1);
    }

    .btn {
        padding: 0.85rem 1.5rem;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        font-family: inherit;
        font-size: 0.95rem;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--ngolab-orange);
        color: white;
        box-shadow: 0 4px 10px rgba(243, 112, 33, 0.2);
    }

    .btn-primary:hover {
        background: #E05D15;
        box-shadow: 0 6px 15px rgba(243, 112, 33, 0.3);
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #CBD5E1;
        color: var(--ngolab-text);
    }

    .btn-outline:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
    }

    /* Upload Area */
    .ktm-upload-area {
        border: 2px dashed #CBD5E1;
        border-radius: var(--border-radius-lg);
        padding: 2.5rem 2rem;
        text-align: center;
        cursor: pointer;
        background: #F8FAFC;
        transition: all 0.2s ease;
    }

    .ktm-upload-area:hover {
        border-color: var(--ngolab-orange);
        background: #FFF4ED;
    }

    /* Links inside card */
    .profile-link-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.2rem 0.5rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--ngolab-text);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border-radius: 8px;
    }

    .profile-link-item:hover {
        color: var(--ngolab-orange);
        padding-left: 1rem;
        background: #FAFAFA;
    }

    .profile-link-item:last-of-type:not(.profile-link-danger) {
        border-bottom: none;
    }

    .profile-link-item i.left-icon {
        font-size: 1.3rem;
        margin-right: 0.8rem;
        color: var(--ngolab-text-light);
        background: #F1F5F9;
        padding: 0.5rem;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .profile-link-item:hover i.left-icon {
        color: var(--ngolab-orange);
        background: #FFF4ED;
    }

    .profile-link-danger {
        color: #EF4444;
        margin-top: 1rem;
        border-bottom: none;
        border-top: 1px dashed var(--border-color);
    }

    .profile-link-danger i.left-icon {
        color: #EF4444 !important;
        background: #FEF2F2;
    }

    .profile-link-danger:hover {
        color: #DC2626;
        background: #FEF2F2;
    }

    /* Alerts */
    .profile-alert {
        padding: 1rem 1.2rem;
        border-radius: var(--border-radius-md);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1px solid transparent;
    }
    
    .profile-alert.success {
        background: #ECFDF5;
        color: #059669;
        border-color: #A7F3D0;
    }

    .profile-alert.error {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }

    .verify-success-box {
        text-align: center;
        padding: 3rem 1rem;
    }

    .verify-success-box i {
        font-size: 4.5rem;
        color: #059669;
        margin-bottom: 1rem;
        background: #ECFDF5;
        padding: 1.5rem;
        border-radius: 50%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-container {
            padding: 1.5rem 1rem;
        }
        .profile-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .profile-card {
            padding: 1.5rem;
        }
    }
</style>

<!-- Main Content -->
<div class="profile-container">
    <div class="profile-grid">
        
        <!-- Profile Sidebar (Kiri) -->
        <div class="sidebar">
            <!-- Profil Singkat -->
            <div class="profile-card" style="text-align: center;">
                <div class="sidebar-profile-img">
                    <img src="https://images.unsplash.com/photo-1531384441138-2736e62e0919?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Profile">
                    <button class="cam-btn"><i class="ph-fill ph-camera"></i></button>
                </div>
                
                <h2 class="profile-name"><?= htmlspecialchars($user_name) ?></h2>
                
                <!-- Role Badge -->
                <div class="role-badge <?= $user_verified ? 'verified' : '' ?>">
                    <i class="ph-fill <?= $user_verified ? 'ph-student' : 'ph-user' ?>"></i>
                    <?= htmlspecialchars($user_role) ?>
                    <?php if ($user_verified): ?>
                        <i class="ph-fill ph-seal-check" style="color: #F37021; margin-left: 4px;"></i>
                    <?php endif; ?>
                </div>
                
                <div class="mini-stats-grid">
                    <div class="mini-stat-card">
                        <div class="mini-stat-label">Tier</div>
                        <div class="mini-stat-value" style="color: #FBBF24;">
                            <i class="ph-fill ph-medal"></i> <?= htmlspecialchars($user_tier) ?>
                        </div>
                    </div>
                    <div class="mini-stat-card">
                        <div class="mini-stat-label">Lokasi</div>
                        <div class="mini-stat-value" style="color: var(--ngolab-orange);">
                            <i class="ph-fill ph-map-pin"></i> Tel-U
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keamanan & Akun (Dipindah ke Kiri) -->
            <div class="profile-card">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="ph-fill ph-shield-check"></i> 
                        Keamanan & Akun
                    </h2>
                </div>
                
                <a href="#" class="profile-link-item">
                    <div style="display: flex; align-items: center;"><i class="ph-fill ph-lock-key left-icon"></i> Ubah Kata Sandi</div>
                    <i class="ph ph-caret-right" style="color: #CBD5E1;"></i>
                </a>
                
                <a href="#" class="profile-link-item">
                    <div style="display: flex; align-items: center;"><i class="ph-fill ph-receipt left-icon"></i> Riwayat Transaksi</div>
                    <i class="ph ph-caret-right" style="color: #CBD5E1;"></i>
                </a>

                <a href="../dashboard/index.php" class="profile-link-item profile-link-danger">
                    <div style="display: flex; align-items: center;"><i class="ph-fill ph-sign-out left-icon"></i> Keluar (Ke Dashboard)</div>
                </a>

                <form action="../../backend/profile/delete_process.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen? Data yang dihapus tidak bisa dikembalikan.');">
                    <button type="submit" class="profile-link-item profile-link-danger" style="width: 100%; background: none; border: none; font-size: 1rem; font-family: inherit; cursor: pointer; padding-left: 0.5rem;">
                        <div style="display: flex; align-items: center;"><i class="ph-fill ph-trash left-icon"></i> Hapus Akun Permanen</div>
                    </button>
                </form>
            </div>
        </div>

        <!-- Profile Content (Kanan) -->
        <div class="main-column">
            
            <!-- Success/Error Messages -->
            <?php if(isset($_GET['success'])): ?>
                <div class="profile-alert success">
                    <i class="ph-fill ph-check-circle" style="font-size: 1.5rem;"></i>
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="profile-alert error">
                    <i class="ph-fill ph-warning-circle" style="font-size: 1.5rem;"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <!-- VIEW MODE: Informasi Pribadi -->
            <div class="profile-card" id="profileViewMode" <?= $edit_mode ? 'style="display:none;"' : '' ?>>
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="ph-fill ph-user-circle"></i> 
                        Informasi Pribadi
                    </h2>
                    <button onclick="toggleEditMode(true)" class="btn btn-outline" style="padding: 0.6rem 1rem; font-size: 0.85rem;">
                        <i class="ph ph-pencil-simple"></i> Edit Profil
                    </button>
                </div>
                
                <div class="profile-info-list">
                    <div class="profile-info-item">
                        <div class="profile-info-icon"><i class="ph-fill ph-user"></i></div>
                        <div class="profile-info-content">
                            <span class="profile-info-label">Nama Lengkap</span>
                            <span class="profile-info-value"><?= htmlspecialchars($user_name) ?></span>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-icon"><i class="ph-fill ph-phone"></i></div>
                        <div class="profile-info-content">
                            <span class="profile-info-label">Nomor Telepon</span>
                            <span class="profile-info-value"><?= htmlspecialchars($user_whatsapp) ?></span>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-icon"><i class="ph-fill ph-envelope-simple"></i></div>
                        <div class="profile-info-content">
                            <span class="profile-info-label">Email</span>
                            <span class="profile-info-value"><?= htmlspecialchars($user_email) ?></span>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-icon"><i class="ph-fill ph-medal"></i></div>
                        <div class="profile-info-content">
                            <span class="profile-info-label">Tier Membership</span>
                            <span class="profile-info-value"><?= htmlspecialchars($user_tier) ?></span>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-icon"><i class="ph-fill ph-identification-badge"></i></div>
                        <div class="profile-info-content">
                            <span class="profile-info-label">Role</span>
                            <span class="profile-info-value">
                                <?= htmlspecialchars($user_role) ?>
                                <?php if ($user_verified): ?>
                                    <span class="verified-inline-badge"><i class="ph-fill ph-seal-check"></i> Terverifikasi</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($user_nim): ?>
                    <div class="profile-info-item">
                        <div class="profile-info-icon"><i class="ph-fill ph-hash"></i></div>
                        <div class="profile-info-content">
                            <span class="profile-info-label">NIM</span>
                            <span class="profile-info-value"><?= htmlspecialchars($user_nim) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- EDIT MODE: Form Edit -->
            <div class="profile-card" id="profileEditMode" <?= !$edit_mode ? 'style="display:none;"' : '' ?>>
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="ph-fill ph-pencil-simple"></i> 
                        Edit Informasi
                    </h2>
                    <button onclick="toggleEditMode(false)" class="btn btn-outline" style="padding: 0.6rem 1rem; font-size: 0.85rem;">
                        <i class="ph ph-x"></i> Batal
                    </button>
                </div>
                
                <form action="../../backend/profile/update_process.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($user_name) ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="whatsapp" value="<?= htmlspecialchars($user_whatsapp) ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user_email) ?>" class="form-control" required>
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="ph-fill ph-floppy-disk"></i> Simpan Perubahan
                        </button>
                        <button type="button" onclick="toggleEditMode(false)" class="btn btn-outline">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Verifikasi Mahasiswa Section -->
            <?php if (!$user_verified): ?>
            <div class="profile-card">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="ph-fill ph-student"></i> 
                        Verifikasi Mahasiswa
                    </h2>
                    <span class="role-badge" style="margin-bottom: 0; background: #F1F5F9; color: #64748B;">
                        <i class="ph-fill ph-clock"></i> Belum Terverifikasi
                    </span>
                </div>
                
                <p style="color: var(--ngolab-text-light); font-size: 0.95rem; margin-bottom: 1.8rem; line-height: 1.6;">
                    Dapatkan role <strong>Mahasiswa</strong> dengan mengunggah Kartu Tanda Mahasiswa (KTM) dan memasukkan NIM Anda. 
                    Mahasiswa terverifikasi mendapat bonus poin dan benefit eksklusif!
                </p>
                
                <form action="../../backend/profile/verify_process.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" name="nim" class="form-control" placeholder="Contoh: 1301213456" required value="<?= htmlspecialchars($user_nim ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Upload Foto KTM</label>
                        <div class="ktm-upload-area" id="ktmUploadArea" onclick="document.getElementById('ktmInput').click();">
                            <input type="file" name="ktm" id="ktmInput" accept="image/*" required style="display: none;" onchange="previewKTM(this)">
                            <div id="ktmPreview">
                                <i class="ph-fill ph-upload-simple" style="font-size: 45px; color: var(--ngolab-orange); margin-bottom: 12px; opacity: 0.8;"></i>
                                <p style="font-weight: 600; font-size: 1.05rem; margin: 0 0 5px 0; color: var(--ngolab-text);">Klik untuk upload foto KTM</p>
                                <p style="font-size: 0.85rem; color: var(--ngolab-text-light); margin: 0;">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem; padding: 1rem;">
                        <i class="ph-fill ph-seal-check"></i> Kirim Permintaan Verifikasi
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="profile-card">
                <div class="verify-success-box">
                    <i class="ph-fill ph-seal-check"></i>
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; color: var(--ngolab-text);">Mahasiswa Terverifikasi</h3>
                    <p style="font-weight: 700; font-size: 1.1rem; margin: 0 0 0.8rem 0; color: var(--ngolab-brown);">NIM: <?= htmlspecialchars($user_nim) ?></p>
                    <p style="color: var(--ngolab-text-light); font-size: 1rem; margin: 0; max-width: 400px; margin: 0 auto;">Anda telah terverifikasi sebagai mahasiswa dan mendapatkan akses benefit eksklusif di Ngo+lab.</p>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script>
function toggleEditMode(showEdit) {
    const viewMode = document.getElementById('profileViewMode');
    const editMode = document.getElementById('profileEditMode');
    if (showEdit) {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    } else {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
    }
}

function previewKTM(input) {
    const preview = document.getElementById('ktmPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" style="max-width: 100%; max-height: 220px; border-radius: 12px; object-fit: contain; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <p style="font-size: 1rem; color: #059669; font-weight: 700; margin: 0; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="ph-fill ph-check-circle" style="font-size: 1.2rem;"></i> File dipilih: ${input.files[0].name}
                </p>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include_once '../../includes/footer.php'; ?>