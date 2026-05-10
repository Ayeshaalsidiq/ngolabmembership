<?php
// Session login/logout ditiadakan
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
            <div class="navbar-links">
                <a href="../dashboard/index.php" class="nav-link <?= (isset($active_page) && $active_page == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                <a href="../menu/menu.php" class="nav-link <?= (isset($active_page) && $active_page == 'menu') ? 'active' : '' ?>">Menu</a>
                <a href="../rewards/rewards.php" class="nav-link <?= (isset($active_page) && $active_page == 'rewards') ? 'active' : '' ?>">Katalog Hadiah</a>
                <a href="../profile/profile.php" class="nav-link <?= (isset($active_page) && $active_page == 'profile') ? 'active' : '' ?>">Profil</a>
            </div>
            <?php /* Login sementara ditiadakan, selalu tampilkan tombol Keluar / Profil */ ?>
            <a href="../profile/profile.php" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">Profil Saya</a>
        </div>
    </nav>
