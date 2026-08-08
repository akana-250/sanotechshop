<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/config.php";
require_once "../includes/admin_check.php";

$page_title = "My Profile";

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {

    die("User not found.");

}

require_once "../includes/header.php";

?>

<div class="d-flex">

<?php require_once "../includes/admin_sidebar.php"; ?>

<div class="flex-grow-1 p-4">

<div class="container-fluid">

<div class="card shadow-sm border-0">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h3 class="mb-0">

<i class="bi bi-person-circle"></i>

My Profile

</h3>

<a
href="edit_profile.php"
class="btn btn-light">

<i class="bi bi-pencil-square"></i>

Edit Profile

</a>

</div>

<div class="card-body">

<div class="row">

<div class="col-lg-4 text-center">

<?php

$image = !empty($user['photo'])
? UPLOAD_URL . $user['photo']
: BASE_URL . "/assets/img/default-user.png";

?>

<img
src="<?= $image; ?>"
class="img-thumbnail rounded-circle shadow"
style="width:180px;height:180px;object-fit:cover;">

<h4 class="mt-3">

<?= htmlspecialchars($user['fullname']); ?>

</h4>

<p class="text-muted">

<?= htmlspecialchars($user['role']); ?>

</p>

</div>

<div class="col-lg-8">

<table class="table table-bordered">

<tr>

<th width="220">

Full Name

</th>

<td>

<?= htmlspecialchars($user['fullname']); ?>

</td>

</tr>

<tr>

<th>

Username

</th>

<td>

<?= htmlspecialchars($user['username']); ?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?= htmlspecialchars($user['email']); ?>

</td>

</tr>

<tr>

<th>

Phone

</th>

<td>

<?= htmlspecialchars($user['phone']); ?>

</td>

</tr>

<tr>

<th>

Role

</th>

<td>

<span class="badge bg-primary">

<?= htmlspecialchars($user['role']); ?>

</span>

</td>

</tr>

<tr>

<th>

Status

</th>

<td>

<?php if($user['status']=="Approved"): ?>

<span class="badge bg-success">

Approved

</span>

<?php elseif($user['status']=="Pending"): ?>

<span class="badge bg-warning text-dark">

Pending

</span>

<?php else: ?>

<span class="badge bg-danger">

Blocked

</span>

<?php endif; ?>

</td>

</tr>

<tr>

<th>

Account Created

</th>

<td>

<?= date("d M Y H:i", strtotime($user['created_at'])); ?>

</td>

</tr>

</table>

<div class="mt-4">

<a
href="edit_profile.php"
class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit Profile

</a>

<a
href="change_password.php"
class="btn btn-primary">

<i class="bi bi-key-fill"></i>

Change Password

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>