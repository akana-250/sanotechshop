<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

$role = strtolower($_SESSION['role'] ?? '');

if ($role !== 'user') {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}