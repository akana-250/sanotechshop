<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";

$page_title = "Edit Customer";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

// Get Customer
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$id]);
$customer = $stmt->fetch();

if (!$customer) {
    header("Location: index.php");
    exit();
}

$message = "";

if (isset($_POST['update'])) {

    $customer_name = trim($_POST['customer_name']);
    $phone         = trim($_POST['phone']);
    $email         = trim($_POST['email']);
    $address       = trim($_POST['address']);

    if (empty($customer_name)) {

        $message = "Customer name is required";

    } else {

        $update = $conn->prepare("
            UPDATE customers SET
                customer_name = ?,
                phone = ?,
                email = ?,
                address = ?
            WHERE id = ?
        ");

        $update->execute([
            $customer_name,
            $phone,
            $email,
            $address,
            $id
        ]);

        header("Location: index.php?success=updated");
        exit();
    }
}

// ===== Admin Layout =====
require_once "../../includes/admin_header.php";
require_once "../../includes/admin_sidebar.php";
?>

<div class="admin-content">

    <div class="container-fluid py-4">

        <div class="card shadow-sm">

            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Customer
                </h4>
            </div>

            <div class="card-body">

                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input
                            type="text"
                            name="customer_name"
                            class="form-control"
                            value="<?= htmlspecialchars($customer['customer_name']) ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="<?= htmlspecialchars($customer['phone']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars($customer['email']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea
                            name="address"
                            rows="4"
                            class="form-control"><?= htmlspecialchars($customer['address']) ?></textarea>
                    </div>

                    <button type="submit" name="update" class="btn btn-success">
                        <i class="bi bi-check-circle"></i>
                        Update Customer
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once "../../includes/admin_footer.php"; ?>