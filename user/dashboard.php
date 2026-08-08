<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/user_check.php";

$page_title = "User Dashboard";

$userId = $_SESSION['user_id'] ?? 0;

if ($userId <= 0) {

    header("Location: " . BASE_URL . "/auth/login.php");
    exit;

}

/*
|--------------------------------------------------------------------------
| Get Logged-in User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {

    session_destroy();

    header("Location: " . BASE_URL . "/auth/login.php");
    exit;

}

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$totalProducts = (int)$conn->query("
    SELECT COUNT(*)
    FROM products
")->fetchColumn();

$totalSales = (int)$conn->query("
    SELECT COUNT(*)
    FROM sales
")->fetchColumn();

$totalNotifications = (int)$conn->query("
    SELECT COUNT(*)
    FROM notifications
")->fetchColumn();

$totalCustomers = (int)$conn->query("
    SELECT COUNT(*)
    FROM customers
")->fetchColumn();

/*
|--------------------------------------------------------------------------
| Load User Header
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../includes/user_header.php";
?>

<div class="container-fluid py-4">

    <!-- Welcome -->

    <div class="row mb-4">

        <div class="col-12">

            <div class="card border-0 shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Welcome,
                                <?= htmlspecialchars($user['fullname']); ?>

                            </h2>

                            <p class="text-muted mb-0">

                                Welcome back to
                                <strong><?= SITE_NAME; ?></strong>

                            </p>

                        </div>

                        <div>

                            <span class="badge bg-success fs-6">

                                <?= htmlspecialchars($user['role']); ?>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Statistics Row -->

    <div class="row"><!-- Products -->

<div class="col-lg-3 col-md-6 mb-4">

    <a href="<?= BASE_URL ?>/user/products/index.php"
       class="text-decoration-none">

        <div class="card border-0 shadow h-100 dashboard-card">

            <div class="card-body text-center">

                <i class="bi bi-box-seam text-primary"
                   style="font-size:50px;"></i>

                <h2 class="mt-3 fw-bold">

                    <?= $totalProducts; ?>

                </h2>

                <p class="text-muted mb-0">

                    Products

                </p>

            </div>

        </div>

    </a>

</div>


<!-- Customers -->

<div class="col-lg-3 col-md-6 mb-4">

    <a href="<?= BASE_URL ?>/user/customers/index.php"
       class="text-decoration-none">

        <div class="card border-0 shadow h-100 dashboard-card">

            <div class="card-body text-center">

                <i class="bi bi-people-fill text-success"
                   style="font-size:50px;"></i>

                <h2 class="mt-3 fw-bold">

                    <?= $totalCustomers; ?>

                </h2>

                <p class="text-muted mb-0">

                    Customers

                </p>

            </div>

        </div>

    </a>

</div>


<!-- Sales -->

<div class="col-lg-3 col-md-6 mb-4">

    <a href="<?= BASE_URL ?>/user/sales/index.php"
       class="text-decoration-none">

        <div class="card border-0 shadow h-100 dashboard-card">

            <div class="card-body text-center">

                <i class="bi bi-cart-check-fill text-warning"
                   style="font-size:50px;"></i>

                <h2 class="mt-3 fw-bold">

                    <?= $totalSales; ?>

                </h2>

                <p class="text-muted mb-0">

                    Sales

                </p>

            </div>

        </div>

    </a>

</div>


<!-- Notifications -->

<div class="col-lg-3 col-md-6 mb-4">

    <a href="<?= BASE_URL ?>/user/notifications/index.php"
       class="text-decoration-none">

        <div class="card border-0 shadow h-100 dashboard-card">

            <div class="card-body text-center">

                <i class="bi bi-bell-fill text-danger"
                   style="font-size:50px;"></i>

                <h2 class="mt-3 fw-bold">

                    <?= $totalNotifications; ?>

                </h2>

                <p class="text-muted mb-0">

                    Notifications

                </p>

            </div>

        </div>

    </a>

</div>

</div>


<!-- My Account -->

<div class="row">

<div class="col-lg-12">

<div class="card border-0 shadow mb-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-person-circle"></i>

My Account

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3">

<strong>Full Name</strong><br>

<?= htmlspecialchars($user['fullname']); ?>

</div>

<div class="col-md-3">

<strong>Username</strong><br>

<?= htmlspecialchars($user['username']); ?>

</div>

<div class="col-md-3">

<strong>Email</strong><br>

<?= htmlspecialchars($user['email']); ?>

</div>

<div class="col-md-3">

<strong>Status</strong><br>

<span class="badge bg-success">

<?= htmlspecialchars($user['status']); ?>

</span>

</div>

</div>

</div>

</div>

</div>

</div><!-- Recent Notifications & Quick Actions -->

<div class="row">

    <!-- Recent Notifications -->

    <div class="col-lg-8 mb-4">

        <div class="card border-0 shadow">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">

                    <i class="bi bi-bell-fill"></i>

                    Recent Notifications

                </h5>

            </div>

            <div class="card-body">

                <?php

                $stmt = $conn->query("
                    SELECT *
                    FROM notifications
                    ORDER BY id DESC
                    LIMIT 5
                ");

                $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                ?>

                <?php if (!empty($notifications)): ?>

                    <div class="list-group">

                        <?php foreach ($notifications as $note): ?>

                            <div class="list-group-item">

                                <h6 class="mb-1">

                                    <?= htmlspecialchars($note['title']); ?>

                                </h6>

                                <p class="mb-1 text-muted">

                                    <?= htmlspecialchars($note['message']); ?>

                                </p>

                                <small class="text-secondary">

                                    <?= htmlspecialchars($note['created_at']); ?>

                                </small>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="alert alert-info mb-0">

                        <i class="bi bi-info-circle"></i>

                        No notifications available.

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <!-- Quick Actions -->

    <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">

                    <i class="bi bi-lightning-charge-fill"></i>

                    Quick Actions

                </h5>

            </div>

            <div class="card-body d-grid gap-2">

                <a href="<?= BASE_URL ?>/user/profile.php"
                   class="btn btn-outline-primary">

                    <i class="bi bi-person-circle"></i>

                    My Profile

                </a>

                <a href="<?= BASE_URL ?>/user/products/index.php"
                   class="btn btn-outline-success">

                    <i class="bi bi-box-seam"></i>

                    View Products

                </a>

                <a href="<?= BASE_URL ?>/user/sales/index.php"
                   class="btn btn-outline-warning">

                    <i class="bi bi-cart-check"></i>

                    View Sales

                </a>

                <a href="<?= BASE_URL ?>/user/quotations/index.php"
                   class="btn btn-outline-info">

                    <i class="bi bi-file-earmark-text"></i>

                    Quotations

                </a>

                <a href="<?= BASE_URL ?>/auth/logout.php"
                   class="btn btn-outline-danger"
                   onclick="return confirm('Logout now?');">

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </a>

            </div>

        </div>

    </div>

</div>

<!-- Footer Card -->

<div class="row">

    <div class="col-12">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center">

                <h5 class="fw-bold">

                    <i class="bi bi-shop"></i>

                    <?= SITE_NAME; ?>

                </h5>

                <p class="text-muted mb-2">

                    Thank you for using the SANO TECH SHOP ERP System.

                </p>

                <small class="text-secondary">

                    Logged in as

                    <strong>

                        <?= htmlspecialchars($user['fullname']); ?>

                    </strong>

                    (<?= htmlspecialchars($user['role']); ?>)

                </small>

            </div>

        </div>

    </div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>