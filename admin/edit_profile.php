<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/config.php";
require_once "../includes/admin_check.php";

$page_title = "Edit Profile";

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);

    $stmt = $conn->prepare("
        UPDATE users
        SET
            fullname=?,
            username=?,
            email=?,
            phone=?
        WHERE id=?
    ");

    if ($stmt->execute([
        $fullname,
        $username,
        $email,
        $phone,
        $userId
    ])) {

        $_SESSION['fullname'] = $fullname;

        header("Location: profile.php?updated=1");
        exit;

    } else {

        $error = "Failed to update profile.";

    }
}

require_once "../includes/header.php";
?>

<div class="d-flex">

<?php require_once "../includes/admin_sidebar.php"; ?>

<div class="flex-grow-1 p-4">

<div class="container-fluid">

<div class="card shadow-sm border-0">

<div class="card-header bg-warning">

<h3 class="mb-0">

<i class="bi bi-pencil-square"></i>

Edit Profile

</h3>

</div>

<div class="card-body"><?php if(!empty($error)): ?>

<div class="alert alert-danger">

    <?= $error; ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="fullname"
class="form-control"
value="<?= htmlspecialchars($user['fullname']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
value="<?= htmlspecialchars($user['username']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email Address

</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user['email']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Phone Number

</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($user['phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Role

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($user['role']); ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($user['status']); ?>"
readonly>

</div>

</div>

<hr>

<button
type="submit"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Save Changes

</button>

<a
href="profile.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</form>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>