<?php
require_once 'config/database.php';

$sql = "ALTER TABLE users ADD COLUMN foto_profile varchar(255) DEFAULT NULL";
if ($conn->query($sql)) {
    echo "Migration successful: foto_profile column added.";
} else {
    echo "Migration failed or column already exists: " . $conn->error;
}
?>
