<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["AdminID"])) {
    header("Location: admin_login.php");
    exit();
}
?>