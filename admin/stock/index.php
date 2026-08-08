<?php

declare(strict_types=1);

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";
require_once __DIR__ . "/../../includes/db.php";

$page_title = "Stock Management";


/*
|--------------------------------------------------------------------------
| STOCK STATISTICS
|--------------------------------------------------------------------------
*/

$total_products = 0;
$total_quantity = 0;
$low_stock = 0;
$recent_moves = [];

try {

    // Total Products
    $stmt = $conn->query("
        SELECT COUNT(*) AS total
        FROM products
    ");
    $total_products = (int)$stmt->fetchColumn();


    // Total Quantity Available
    $stmt = $conn->query("
        SELECT COALESCE(SUM(quantity),0)
        FROM products
    ");
    $total_quantity = (int)$stmt->fetchColumn();


    // Low Stock
    $stmt = $conn->query("
        SELECT COUNT(*)
        FROM products
        WHERE quantity <= minimum_stock
    ");
    $low_stock = (int)$stmt->fetchColumn();


    // Recent Stock Movements
    $stmt = $conn->query("
        SELECT
            sm.id,
            sm.movement_type,
            sm.quantity,
            sm.created_at,
            p.product_name
        FROM stock_movements sm
        LEFT JOIN products p
            ON p.id = sm.product_id
        ORDER BY sm.id DESC
        LIMIT 10
    ");

    $recent_moves = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    $total_products = 0;
    $total_quantity = 0;
    $low_stock = 0;
    $recent_moves = [];

}


require_once __DIR__ . "/../../includes/admin_header.php";
require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>

<main>

<div class="container-fluid"><!-- PAGE HEADER -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fa-solid fa-boxes-stacked text-primary"></i>

            Stock Management

        </h2>

        <p class="text-muted mb-0">

            Manage inventory, stock in, stock out and movement history.

        </p>

    </div>


    <div class="d-flex gap-2">

        <a href="stock_in.php" class="btn btn-success">

            <i class="fa-solid fa-plus"></i>

            Stock In

        </a>


        <a href="stock_out.php" class="btn btn-danger">

            <i class="fa-solid fa-minus"></i>

            Stock Out

        </a>


        <a href="stock_history.php" class="btn btn-primary">

            <i class="fa-solid fa-clock-rotate-left"></i>

            Stock History

        </a>

    </div>

</div>



<!-- SUCCESS MESSAGE -->

<?php if(isset($_SESSION['success'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="fa-solid fa-circle-check"></i>

    <?= htmlspecialchars($_SESSION['success']); ?>

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>



<!-- ERROR MESSAGE -->

<?php if(isset($_SESSION['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="fa-solid fa-circle-exclamation"></i>

    <?= htmlspecialchars($_SESSION['error']); ?>

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?><!-- =========================
     STOCK SUMMARY CARDS
========================= -->

<div class="row g-4 mb-4">

    <!-- Total Products -->
    <div class="col-lg-4 col-md-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted text-uppercase">
                            Total Products
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            <?= number_format($total_products); ?>

                        </h2>

                    </div>

                    <div class="fs-1 text-primary">

                        <i class="fa-solid fa-box"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- Available Stock -->

    <div class="col-lg-4 col-md-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted text-uppercase">
                            Available Stock
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            <?= number_format($total_quantity); ?>

                        </h2>

                    </div>

                    <div class="fs-1 text-success">

                        <i class="fa-solid fa-boxes-stacked"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- Low Stock -->

    <div class="col-lg-4 col-md-12">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted text-uppercase">
                            Low Stock Items
                        </small>

                        <h2 class="fw-bold mt-2 mb-0 text-danger">

                            <?= number_format($low_stock); ?>

                        </h2>

                    </div>

                    <div class="fs-1 text-danger">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div><!-- ===========================================
     RECENT STOCK MOVEMENTS
=========================================== -->

<div class="card shadow-sm border-0">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fa-solid fa-clock-rotate-left text-primary"></i>
            Recent Stock Movements
        </h5>

        <a href="stock_history.php" class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-list"></i>
            View All
        </a>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="70">#</th>

                        <th>Product</th>

                        <th width="120">Type</th>

                        <th width="120">Quantity</th>

                        <th width="180">Date</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(!empty($recent_moves)): ?>

                    <?php foreach($recent_moves as $move): ?>

                    <tr>

                        <td>
                            <?= (int)$move['id']; ?>
                        </td>

                        <td>

                            <strong>

                                <?= htmlspecialchars($move['product_name'] ?? 'Unknown Product'); ?>

                            </strong>

                        </td>

                        <td>

                            <?php if($move['movement_type'] === 'IN'): ?>

                                <span class="badge bg-success">

                                    <i class="fa-solid fa-arrow-down"></i>

                                    IN

                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    OUT

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?= number_format((int)$move['quantity']); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($move['created_at']); ?>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5" class="text-center py-5 text-muted">

                            <i class="fa-solid fa-box-open fa-2x mb-3 d-block"></i>

                            No stock movement found.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div></div>

</main>

<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

ob_end_flush();

?>