<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";

$page_title = "Add User";

/* ===========================
   SAVE USER
=========================== */

if (isset($_POST['save_user'])) {

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $status   = $_POST['status'];

    try {

        // Check username
        $checkUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkUser->execute([$username]);

        if ($checkUser->rowCount() > 0) {
            $_SESSION['error'] = "Username already exists.";
            header("Location:add_user.php");
            exit;
        }

        // Check email
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->execute([$email]);

        if ($checkEmail->rowCount() > 0) {
            $_SESSION['error'] = "Email already exists.";
            header("Location:add_user.php");
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users
            (
                fullname,
                username,
                email,
                phone,
                password,
                role,
                status
            )
            VALUES
            (
                ?,?,?,?,?,?,?
            )
        ");

        $stmt->execute([
            $fullname,
            $username,
            $email,
            $phone,
            $hashedPassword,
            $role,
            $status
        ]);

        $_SESSION['success'] = "User created successfully.";

        header("Location:index.php");
        exit;

    } catch (PDOException $e) {

        $_SESSION['error'] = $e->getMessage();
    }
}

require_once __DIR__ . "/../../includes/admin_header.php";
require_once __DIR__ . "/../../includes/admin_sidebar.php";
?>

<main class="admin-content">

<div class="container-fluid">

<?php if(isset($_SESSION['success'])): ?>

<div class="alert alert-success">
    <?= $_SESSION['success']; ?>
</div>

<?php unset($_SESSION['success']); endif; ?>

<?php if(isset($_SESSION['error'])): ?>

<div class="alert alert-danger">
    <?= $_SESSION['error']; ?>
</div>

<?php unset($_SESSION['error']); endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>
<i class="fa-solid fa-user-plus"></i>
Add User
</h2>

<p class="text-muted">
Create new system user account
</p>

</div>

<a href="index.php" class="btn btn-secondary">
<i class="fa-solid fa-arrow-left"></i>
Back
</a>

</div>

<div class="content-card">

<form method="POST">

<div class="row"><div class="col-md-6 mb-3">

    <label class="form-label">
        Full Name
    </label>

    <input
        type="text"
        name="fullname"
        class="form-control"
        placeholder="Enter full name"
        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
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
        placeholder="Enter username"
        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
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
        placeholder="Enter email"
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
        placeholder="Enter phone number"
        value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

</div>


<div class="col-md-6 mb-3">

    <label class="form-label">
        Password
    </label>

    <input
        type="password"
        name="password"
        class="form-control"
        placeholder="Enter password"
        required>

</div>


<div class="col-md-3 mb-3">

    <label class="form-label">
        Role
    </label>

    <select name="role" class="form-select">

        <option value="User">User</option>

        <option value="Admin">Admin</option>

    </select>

</div>


<div class="col-md-3 mb-3">

    <label class="form-label">
        Status
    </label>

    <select name="status" class="form-select">

        <option value="Pending">Pending</option>

        <option value="Approved">Approved</option>

    </select>

</div>

</div>


<div class="mt-4">

    <button
        type="submit"
        name="save_user"
        class="btn btn-primary">

        <i class="fa-solid fa-save"></i>

        Save User

    </button>

    <a
        href="index.php"
        class="btn btn-secondary">

        Cancel

    </a>

</div>

</form>

</div>

</div>

</main>

<?php
require_once __DIR__ . "/../../includes/admin_footer.php";

ob_end_flush();
?>