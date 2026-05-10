<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../pages/dashboard/index.php");
exit;
?>
