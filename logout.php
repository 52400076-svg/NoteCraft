<?php
session_start();

// xoá toàn bộ session
session_destroy();

// quay về login
header("Location: login.php");
exit();
?>