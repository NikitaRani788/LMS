<?php
session_start();

// 🔒 Force login
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login.php");
        exit;
    }
}

// 🔐 Role-based restriction
function requireRole($role) {
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        // Redirect to their correct dashboard instead of error
        header("Location: /" . $_SESSION['role'] . "/dashboard.php");
        exit;
    }
}