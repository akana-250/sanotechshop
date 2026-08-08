<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/config.php";

$page_title = $page_title ?? "User Dashboard";

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($page_title) ?> | <?= SITE_NAME ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- USER CSS -->

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">

</head>

<body>

<div class="user-wrapper">

<header class="user-topbar">

<div class="container-fluid h-100">

<div class="d-flex justify-content-between align-items-center h-100">

<div class="d-flex align-items-center">

<button
class="btn btn-light me-3"
id="sidebarToggle">

<i class="bi bi-list fs-5"></i>

</button>

<a href="<?= BASE_URL ?>/user/dashboard.php"
class="text-decoration-none d-flex align-items-center">

<img
src="<?= BASE_URL ?>/assets/img/logo.png"
alt="Logo"
width="45"
height="45"
class="rounded me-2"
onerror="this.style.display='none';">

<div>

<h5 class="mb-0 fw-bold">

<?= SITE_NAME ?>

</h5>

<small class="text-muted">

User Panel

</small>

</div>

</a>

</div>

<div class="d-flex align-items-center">

<input
type="search"
class="form-control me-3 d-none d-lg-block"
placeholder="Search..."
style="width:250px;">

<div class="dropdown">

<a href="#"
class="text-decoration-none text-dark dropdown-toggle d-flex align-items-center"
data-bs-toggle="dropdown">

<img
src="<?= BASE_URL ?>/assets/img/avatar.png"
width="40"
height="40"
class="rounded-circle border me-2"
onerror="this.style.display='none';">

<div class="text-start">

<strong>

<?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?>

</strong>

<br>

<small class="text-muted">

<?= htmlspecialchars($_SESSION['role'] ?? 'User'); ?>

</small>

</div>

</a>

<ul class="dropdown-menu dropdown-menu-end">

<li>

<a class="dropdown-item"
href="<?= BASE_URL ?>/user/profile.php">

<i class="bi bi-person-circle me-2"></i>

My Profile

</a>

</li>

<li>

<a class="dropdown-item"
href="<?= BASE_URL ?>/user/settings/index.php">

<i class="bi bi-gear me-2"></i>

Settings

</a>

</li>

<li><hr class="dropdown-divider"></li>

<li>

<a class="dropdown-item text-danger"
href="<?= BASE_URL ?>/auth/logout.php">

<i class="bi bi-box-arrow-right me-2"></i>

Logout

</a>

</li>

</ul>

</div>

</div>

</div>

</div>

</header>

<?php require_once __DIR__ . "/user_sidebar.php"; ?>

<main class="user-content">