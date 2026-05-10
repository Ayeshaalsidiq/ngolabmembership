<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/config/database.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_avatar = '';
if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT nama, foto_profile FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $u_res = $stmt->get_result();
    if ($u_data = $u_res->fetch_assoc()) {
        $user_avatar = !empty($u_data['foto_profile']) ? $u_data['foto_profile'] : 'https://ui-avatars.com/api/?name=' . urlencode($u_data['nama']) . '&background=f37021&color=fff';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Ngo+lab Membership' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="../dashboard/index.php" class="navbar-brand">
                <i class="ph-fill ph-bowl-food"></i>
                Ngo+lab Membership
            </a>
            
            <!-- Mobile Toggle -->
            <button class="mobile-toggle" id="navbarToggle">
                <i class="ph ph-list"></i>
            </button>

            <div class="navbar-collapse" id="navbarCollapse">
                <div class="navbar-links">
                    <a href="../dashboard/index.php" class="nav-link <?= (isset($active_page) && $active_page == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                    <a href="../menu/menu.php" class="nav-link <?= (isset($active_page) && $active_page == 'menu') ? 'active' : '' ?>">Menu</a>
                    <a href="../rewards/rewards.php" class="nav-link <?= (isset($active_page) && $active_page == 'rewards') ? 'active' : '' ?>">Katalog Hadiah</a>
                </div>
                
                <div class="navbar-auth">
                    <?php if ($is_logged_in): ?>
                        <div class="nav-profile-dropdown">
                            <img src="<?= $user_avatar ?>" alt="Avatar" class="nav-avatar" id="profileDropdown">
                            <div class="dropdown-menu" id="dropdownMenu">
                                <a href="../profile/profile.php"><i class="ph ph-user"></i> Profil Saya</a>
                                <a href="../../backend/auth/logout.php" class="text-danger"><i class="ph ph-sign-out"></i> Keluar</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="../auth/register.php" class="btn btn-primary navbar-btn-auth">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Toggle Mobile Menu
        const navbarToggle = document.getElementById('navbarToggle');
        if (navbarToggle) {
            navbarToggle.addEventListener('click', function() {
                document.getElementById('navbarCollapse').classList.toggle('active');
                const icon = this.querySelector('i');
                if (document.getElementById('navbarCollapse').classList.contains('active')) {
                    icon.classList.replace('ph-list', 'ph-x');
                } else {
                    icon.classList.replace('ph-x', 'ph-list');
                }
            });
        }

        // Toggle Profile Dropdown
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');
        if (profileDropdown && dropdownMenu) {
            profileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });
            document.addEventListener('click', function() {
                dropdownMenu.classList.remove('show');
            });
        }
    </script>
