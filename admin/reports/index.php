<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";
require_once "../../includes/header.php";

?>

<div class="d-flex">

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="flex-grow-1 p-4">

<?php

// ==============================
// TOTAL SALES
// ==============================

$stmt = $conn->prepare("
SELECT SUM(total) AS total_sales
FROM sales
");
$stmt->execute();

$sales = $stmt->fetch(PDO::FETCH_ASSOC);
$total_sales = $sales['total_sales'] ?? 0;


// ==============================
// TOTAL EXPENSES
// ==============================

$stmt = $conn->prepare("
SELECT SUM(amount) AS total_expenses
FROM expenses
");
$stmt->execute();

$expense = $stmt->fetch(PDO::FETCH_ASSOC);
$total_expenses = $expense['total_expenses'] ?? 0;


// ==============================
// PROFIT
// ==============================

$profit = $total_sales - $total_expenses;


// ==============================
// PRODUCTS
// ==============================

$stmt = $conn->prepare("
SELECT COUNT(*) AS total_products
FROM products
");
$stmt->execute();

$product = $stmt->fetch(PDO::FETCH_ASSOC);
$total_products = $product['total_products'];

?>

<!-- PAGE HEADER -->

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <h2 class="fw-bold mb-1">
            📊 Reports & Analytics
        </h2>

        <p class="text-muted mb-0">
            Monitor your business performance and reports.
        </p>

    </div>

</div>



<!-- SUMMARY CARDS -->

<div class="row g-4">

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Total Sales
                        </small>

                        <h3 class="fw-bold text-success mt-2">
                            <?= number_format($total_sales); ?> RWF
                        </h3>

                        <small class="text-muted">
                            Revenue Generated
                        </small>

                    </div>

                    <div class="fs-1">
                        💰
                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Expenses
                        </small>

                        <h3 class="fw-bold text-danger mt-2">
                            <?= number_format($total_expenses); ?> RWF
                        </h3>

                        <small class="text-muted">
                            Business Costs
                        </small>

                    </div>

                    <div class="fs-1">
                        💸
                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Profit
                        </small>

                        <h3 class="fw-bold text-primary mt-2">
                            <?= number_format($profit); ?> RWF
                        </h3>

                        <small class="text-muted">
                            Net Income
                        </small>

                    </div>

                    <div class="fs-1">
                        📈
                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Products
                        </small>

                        <h3 class="fw-bold text-dark mt-2">
                            <?= $total_products; ?>
                        </h3>

                        <small class="text-muted">
                            Registered Products
                        </small>

                    </div>

                    <div class="fs-1">
                        📦
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- REPORT MODULES -->

<div class="card border-0 shadow mt-5">

    <div class="card-header bg-white">

        <h4 class="mb-0">
            📑 Reports Modules
        </h4>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <a href="sales_report.php"
                   class="btn btn-success w-100 py-3">

                    📈 Sales Report

                </a>

            </div>

            <div class="col-md-4">

                <a href="profit_report.php"
                   class="btn btn-primary w-100 py-3">

                    💰 Profit Report

                </a>

            </div>

            <div class="col-md-4">

                <a href="stock_report.php"
                   class="btn btn-warning w-100 py-3">

                    📦 Stock Report

                </a>

            </div>

        </div>

    </div>

</div>



<!-- UPCOMING FEATURES -->

<div class="row mt-5 g-4">

    <div class="col-lg-8">

        <div class="card shadow border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    📊 Sales Analytics
                </h5>

            </div>

            <div class="card-body text-center py-5">

                <h1>📈</h1>

                <p class="text-muted">
                    Sales charts will appear here.
                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card shadow border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    ⚠ Low Stock Alert
                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted">
                    Low stock products will be displayed here.
                </p>

            </div>

        </div>

    </div>

</div>

</div>

</div>

<?php require_once "../../includes/footer.php"; ?>