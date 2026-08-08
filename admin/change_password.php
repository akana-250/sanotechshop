<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/config.php";
require_once "../includes/admin_check.php";

$page_title = "Change Password";

$userId = $_SESSION['user_id'];

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current_password = trim($_POST['current_password']);
    $new_password     = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->execute([$userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        $error = "User not found.";

    } elseif (!password_verify($current_password, $user['password'])) {

        $error = "Current password is incorrect.";

    } elseif ($new_password != $confirm_password) {

        $error = "New passwords do not match.";

    } elseif (strlen($new_password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $conn->prepare("
            UPDATE users
            SET password=?
            WHERE id=?
        ");

        if ($update->execute([$hashedPassword, $userId])) {

            $message = "Password changed successfully.";

        } else {

            $error = "Failed to change password.";

        }

    }

}

require_once "../includes/header.php";
?>

<div class="d-flex">

<?php require_once "../includes/admin_sidebar.php"; ?>

<div class="flex-grow-1 p-4">

<div class="container-fluid">

<div class="card shadow-sm border-0">

<div class="card-header bg-primary text-white">

<h3 class="mb-0">

<i class="bi bi-key-fill"></i>

Change Password

</h3>

</div>

<div class="card-body"><?php if (!empty($message)): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="bi bi-check-circle-fill"></i>

    <?= $message; ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if (!empty($error)): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="bi bi-exclamation-triangle-fill"></i>

    <?= $error; ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Current Password

</label>

<div class="input-group">

<input
type="password"
name="current_password"
id="current_password"
class="form-control"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('current_password')">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<div class="mb-3">

<label class="form-label">

New Password

</label>

<div class="input-group">

<input
type="password"
name="new_password"
id="new_password"
class="form-control"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('new_password')">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<div class="mb-4">

<label class="form-label">

Confirm New Password

</label>

<div class="input-group">

<input
type="password"
name="confirm_password"
id="confirm_password"
class="form-control"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('confirm_password')">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<div class="d-flex gap-2">

<button
type="submit"
class="btn btn-primary">

<i class="bi bi-key-fill"></i>

Change Password

</button>

<a
href="profile.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back to Profile

</a>

</div>

</form>

</div>

</div>

</div>

</div>

<script>

function togglePassword(id){

    var input = document.getElementById(id);

    if(input.type === "password"){

        input.type = "text";

    }else{

        input.type = "password";

    }

}

</script>

<?php require_once "../includes/footer.php"; ?>