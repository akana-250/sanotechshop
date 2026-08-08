<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/user_check.php";

$page_title = "Products";

require_once __DIR__ . "/../../includes/user_header.php";

/*
|--------------------------------------------------------------------------
| PRODUCT STATISTICS
|--------------------------------------------------------------------------
*/

$totalProducts = $conn->query("
    SELECT COUNT(*) FROM products
")->fetchColumn();

$activeProducts = $conn->query("
    SELECT COUNT(*)
    FROM products
    WHERE status='Active'
")->fetchColumn();

$lowStock = $conn->query("
    SELECT COUNT(*)
    FROM products
    WHERE quantity > 0
      AND quantity <= minimum_stock
")->fetchColumn();

$outOfStock = $conn->query("
    SELECT COUNT(*)
    FROM products
    WHERE quantity <= 0
")->fetchColumn();

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

$sql = "
SELECT
    p.*,
    c.category_name
FROM products p
LEFT JOIN categories c
ON p.category_id = c.id
WHERE 1
";

$params = [];

if ($search != "") {

    $sql .= "
    AND (
        p.product_name LIKE ?
        OR p.product_code LIKE ?
        OR p.barcode LIKE ?
    )
    ";

    $params = [
        "%{$search}%",
        "%{$search}%",
        "%{$search}%"
    ];
}

$sql .= " ORDER BY p.product_name ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold">

            <i class="bi bi-box-seam-fill text-primary"></i>

            Products

        </h2>

        <small class="text-muted">

            Browse all available products.

        </small>

    </div>

    <a href="add.php" class="btn btn-success">

        <i class="bi bi-plus-circle"></i>

        Add Product

    </a>

</div>

<!-- Statistics -->

<div class="row g-3 mb-4">

<div class="col-lg-3 col-md-6">

<div class="card">

<div class="card-body text-center">

<i class="bi bi-box-seam display-5 text-primary"></i>

<h3 class="mt-2">

<?= $totalProducts ?>

</h3>

<p class="text-muted mb-0">

Total Products

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card">

<div class="card-body text-center">

<i class="bi bi-check-circle-fill display-5 text-success"></i>

<h3 class="mt-2">

<?= $activeProducts ?>

</h3>

<p class="text-muted mb-0">

Active Products

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card">

<div class="card-body text-center">

<i class="bi bi-exclamation-triangle-fill display-5 text-warning"></i>

<h3 class="mt-2">

<?= $lowStock ?>

</h3>

<p class="text-muted mb-0">

Low Stock

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card">

<div class="card-body text-center">

<i class="bi bi-x-circle-fill display-5 text-danger"></i>

<h3 class="mt-2">

<?= $outOfStock ?>

</h3>

<p class="text-muted mb-0">

Out of Stock

</p>

</div>

</div>

</div>

</div>

<!-- Search -->

<div class="card mb-4">

<div class="card-body">

<form method="GET" class="row g-3">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search product by name, code or barcode..."
value="<?= htmlspecialchars($search); ?>">

</div>

<div class="col-md-2 d-grid">

<button class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</form>

</div>

</div>

<div class="card">

<div class="card-body">

<div class="table-responsive"><table class="table table-bordered table-hover align-middle">

    <thead class="table-dark">

        <tr>

            <th>ID</th>
            <th>Image</th>
            <th>Product</th>
            <th>Category</th>
            <th>Buying Price</th>
            <th>Selling Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th width="180">Action</th>

        </tr>

    </thead>

    <tbody>

    <?php if(count($products) > 0): ?>

        <?php foreach($products as $product): ?>

        <tr>

            <td><?= $product['id']; ?></td>

            <td>

                <?php if(!empty($product['image'])): ?>

                    <img
                        src="<?= UPLOAD_URL . $product['image']; ?>"
                        width="60"
                        height="60"
                        class="rounded border"
                        style="object-fit:cover;">

                <?php else: ?>

                    <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                         style="width:60px;height:60px;">

                        <i class="bi bi-image text-secondary"></i>

                    </div>

                <?php endif; ?>

            </td>

            <td>

                <strong>

                    <?= htmlspecialchars($product['product_name']); ?>

                </strong>

                <br>

                <small class="text-muted">

                    <?= htmlspecialchars($product['product_code']); ?>

                </small>

            </td>

            <td>

                <?= htmlspecialchars($product['category_name'] ?? 'N/A'); ?>

            </td>

            <td>

                <?= CURRENCY . " " . number_format($product['buying_price'],2); ?>

            </td>

            <td>

                <span class="fw-bold text-success">

                    <?= CURRENCY . " " . number_format($product['selling_price'],2); ?>

                </span>

            </td>

            <td>

                <?php if($product['quantity'] <= 0): ?>

                    <span class="badge bg-danger">

                        Out of Stock

                    </span>

                <?php elseif($product['quantity'] <= $product['minimum_stock']): ?>

                    <span class="badge bg-warning text-dark">

                        Low Stock (<?= $product['quantity']; ?>)

                    </span>

                <?php else: ?>

                    <span class="badge bg-success">

                        <?= $product['quantity']; ?> In Stock

                    </span>

                <?php endif; ?>

            </td>

            <td>

                <?php if($product['status'] == 'Active'): ?>

                    <span class="badge bg-success">

                        Active

                    </span>

                <?php else: ?>

                    <span class="badge bg-secondary">

                        Inactive

                    </span>

                <?php endif; ?>

            </td>

            <td>

                <div class="btn-group" role="group">

                    <a href="view.php?id=<?= $product['id']; ?>"
                       class="btn btn-info btn-sm">

                        <i class="bi bi-eye-fill"></i>

                    </a>

                    <a href="edit.php?id=<?= $product['id']; ?>"
                       class="btn btn-warning btn-sm">

                        <i class="bi bi-pencil-square"></i>

                    </a>

                    <a href="delete.php?id=<?= $product['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this product?');">

                        <i class="bi bi-trash-fill"></i>

                    </a>

                </div>

            </td>

        </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>

            <td colspan="9" class="text-center py-5">

                <i class="bi bi-box-seam display-4 text-muted"></i>

                <h4 class="mt-3">

                    No Products Found

                </h4>

                <p class="text-muted">

                    There are no products matching your search.

                </p>

            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<?php require_once __DIR__ . "/../../includes/footer.php"; ?>