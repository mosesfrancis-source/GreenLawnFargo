<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["CustomerID"])) {
    header("Location: login.php");
    exit();
}
?>