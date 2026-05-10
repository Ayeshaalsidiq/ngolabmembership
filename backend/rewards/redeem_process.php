<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reward_id'])) {
    $user_id = 1;
    $reward_id = intval($_POST['reward_id']);

    // Get user points
    $res_user = $conn->query("SELECT poin FROM users WHERE id = $user_id");
    if($res_user->num_rows == 0) {
        header("Location: ../../pages/rewards/rewards.php?error=User tidak ditemukan!");
        exit;
    }
    $user = $res_user->fetch_assoc();
    $current_points = $user['poin'];

    // Get reward details
    $res_reward = $conn->query("SELECT nama_reward, poin_dibutuhkan FROM rewards WHERE id = $reward_id");
    if($res_reward->num_rows == 0) {
        header("Location: ../../pages/rewards/rewards.php?error=Hadiah tidak ditemukan!");
        exit;
    }
    $reward = $res_reward->fetch_assoc();
    $points_needed = $reward['poin_dibutuhkan'];
    $reward_name = $reward['nama_reward'];

    if ($current_points < $points_needed) {
        header("Location: ../../pages/rewards/rewards.php?error=Poin tidak mencukupi!");
        exit;
    }

    // Deduct points
    $new_points = $current_points - $points_needed;
    $conn->query("UPDATE users SET poin = $new_points WHERE id = $user_id");

    // Add activity
    $activity_name = "Redeem Hadiah (" . $conn->real_escape_string($reward_name) . ")";
    $points_deducted = -$points_needed;
    $query = "INSERT INTO activities (user_id, jenis_aktivitas, jumlah_poin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $user_id, $activity_name, $points_deducted);
    $stmt->execute();

    header("Location: ../../pages/rewards/rewards.php?success=Berhasil menukar poin dengan " . urlencode($reward_name) . "!");
} else {
    header("Location: ../../pages/rewards/rewards.php");
}
?>
