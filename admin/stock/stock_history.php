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

$page_title = "Stock History";


try {

    $stmt = $conn->prepare("

        SELECT

            sm.id,

            sm.movement_type,

            sm.quantity,

            sm.created_at,

            p.product_name,

            p.product_code

        FROM stock_movements sm

        LEFT JOIN products p

            ON p.id = sm.product_id

        ORDER BY sm.created_at DESC

    ");

    $stmt->execute();

    $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    $movements = [];

}


require_once __DIR__ . "/../../includes/admin_header.php";
require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>

<main>

<div class="container-fluid"><!-- ===========================
     PAGE HEADER
=========================== -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fa-solid fa-clock-rotate-left text-primary"></i>

            Stock History

        </h2>

        <p class="text-muted mb-0">

            View all stock movements (IN & OUT)

        </p>

    </div>


    <div class="d-flex gap-2">

        <a href="index.php" class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left"></i>

            Dashboard

        </a>


        <a href="stock_in.php" class="btn btn-success">

            <i class="fa-solid fa-plus"></i>

            Stock In

        </a>


        <a href="stock_out.php" class="btn btn-danger">

            <i class="fa-solid fa-minus"></i>

            Stock Out

        </a>

    </div>

</div>



<!-- SUCCESS MESSAGE -->

<?php if(isset($_SESSION['success'])): ?>

<div class="alert alert-success alert-dismissible fade show" role="alert">

    <i class="fa-solid fa-circle-check me-2"></i>

    <?= htmlspecialchars($_SESSION['success']); ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>



<!-- ERROR MESSAGE -->

<?php if(isset($_SESSION['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <i class="fa-solid fa-circle-exclamation me-2"></i>

    <?= htmlspecialchars($_SESSION['error']); ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>



<!-- ===========================
     HISTORY TABLE CARD
=========================== -->

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-list"></i>

            Stock Movement History

        </h5>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive"><table class="table table-hover align-middle mb-0">

    <thead class="table-dark">

        <tr>

            <th width="70">#</th>

            <th>Product Code</th>

            <th>Product Name</th>

            <th width="120">Type</th>

            <th width="120" class="text-end">Quantity</th>

            <th width="180">Date</th>

        </tr>

    </thead>

    <tbody>

    <?php if(!empty($movements)): ?>

        <?php foreach($movements as $move): ?>

        <tr>

            <td>

                <?= (int)$move['id']; ?>

            </td>

            <td>

                <?= htmlspecialchars($move['product_code'] ?? '-'); ?>

            </td>

            <td>

                <strong>

                    <?= htmlspecialchars($move['product_name'] ?? 'Unknown Product'); ?>

                </strong>

            </td>

            <td>

                <?php if(($move['movement_type'] ?? '') === 'IN'): ?>

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

            <td class="text-end">

                <?= number_format((int)$move['quantity']); ?>

            </td>

            <td>

                <?= htmlspecialchars($move['created_at']); ?>

            </td>

        </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>

            <td colspan="6" class="text-center py-5">

                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>

                <h5 class="mt-3 mb-1">No Stock History Found</h5>

                <p class="text-muted mb-0">

                    No stock movements have been recorded yet.

                </p>

            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>

        </div>

    </div>

</div>    </div>
</div>

</main>

<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

ob_end_flush();

?>