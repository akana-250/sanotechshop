<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";
require_once __DIR__ . "/../../includes/db.php";


$page_title = "Users Management";


// Use existing header
require_once __DIR__ . "/../../includes/header.php";



// =============================
// FILTERS
// =============================

$search = $_GET['search'] ?? '';
$role   = $_GET['role'] ?? '';
$status = $_GET['status'] ?? '';



$sql = "SELECT * FROM users WHERE 1";

$params = [];



if(!empty($search)){

    $sql .= "
    AND (
        fullname LIKE ?
        OR username LIKE ?
        OR email LIKE ?
        OR phone LIKE ?
    )
    ";

    $params[] = "%".$search."%";
    $params[] = "%".$search."%";
    $params[] = "%".$search."%";
    $params[] = "%".$search."%";

}



if(!empty($role)){

    $sql .= " AND role=?";

    $params[] = $role;

}



if(!empty($status)){

    $sql .= " AND status=?";

    $params[] = $status;

}



$sql .= " ORDER BY id DESC";



$stmt = $conn->prepare($sql);

$stmt->execute($params);


$users = $stmt->fetchAll(PDO::FETCH_ASSOC);




// =============================
// SUMMARY
// =============================


$totalUsers = $conn
->query("SELECT COUNT(*) FROM users")
->fetchColumn();



$totalAdmins = $conn
->query("
SELECT COUNT(*)
FROM users
WHERE role='Admin'
")
->fetchColumn();



$totalApproved = $conn
->query("
SELECT COUNT(*)
FROM users
WHERE status='Approved'
")
->fetchColumn();



$totalPending = $conn
->query("
SELECT COUNT(*)
FROM users
WHERE status='Pending'
")
->fetchColumn();



?>



<div class="content-wrapper">


<main class="main-content flex-grow-1">


<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2 class="fw-bold">

<i class="bi bi-people-fill text-primary"></i>

Users Management

</h2>


<p class="text-muted">

Manage system users

</p>


</div>



<a href="<?= BASE_URL ?>/admin/user/add_user.php"
class="btn btn-primary">

<i class="bi bi-person-plus"></i>

Add User

</a>


</div>





<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<h6 class="text-muted">
Total Users
</h6>

<h2 class="fw-bold">
<?= $totalUsers ?>
</h2>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<h6 class="text-muted">
Admins
</h6>

<h2 class="fw-bold">
<?= $totalAdmins ?>
</h2>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<h6 class="text-muted">
Approved
</h6>

<h2 class="fw-bold">
<?= $totalApproved ?>
</h2>

</div>

</div>

</div>




<div class="col-md-3">

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<h6 class="text-muted">
Pending
</h6>

<h2 class="fw-bold">
<?= $totalPending ?>
</h2>

</div>

</div>

</div>


</div>





<div class="card shadow-sm border-0 rounded-4">


<div class="card-body">



<form method="GET" class="row g-3 mb-4">



<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search users..."
value="<?= htmlspecialchars($search) ?>">

</div>




<div class="col-md-3">

<select name="role" class="form-select">

<option value="">
All Roles
</option>

<option value="Admin"
<?= $role=="Admin"?'selected':'' ?>>
Admin
</option>

<option value="User"
<?= $role=="User"?'selected':'' ?>>
User
</option>

</select>

</div>




<div class="col-md-3">

<select name="status" class="form-select">


<option value="">
All Status
</option>


<option value="Approved"
<?= $status=="Approved"?'selected':'' ?>>
Approved
</option>


<option value="Pending"
<?= $status=="Pending"?'selected':'' ?>>
Pending
</option>


<option value="Blocked"
<?= $status=="Blocked"?'selected':'' ?>>
Blocked
</option>


</select>

</div>




<div class="col-md-2">

<button class="btn btn-dark w-100">

Search

</button>

</div>


</form>





<div class="table-responsive">


<table class="table table-hover align-middle">


<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Username</th>
<th>Email</th>
<th>Phone</th>
<th>Role</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>



<tbody>


<?php if(count($users)>0): ?>


<?php foreach($users as $user): ?>


<tr>


<td><?= $user['id'] ?></td>


<td>
<?= htmlspecialchars($user['fullname'] ?? '') ?>
</td>


<td>
<?= htmlspecialchars($user['username'] ?? '') ?>
</td>


<td>
<?= htmlspecialchars($user['email'] ?? '') ?>
</td>


<td>
<?= htmlspecialchars($user['phone'] ?? '') ?>
</td>



<td>

<?php if(($user['role'] ?? '')=="Admin"): ?>

<span class="badge bg-primary">
Admin
</span>

<?php else: ?>

<span class="badge bg-secondary">
User
</span>

<?php endif; ?>

</td>




<td>

<?php if(($user['status'] ?? '')=="Approved"): ?>

<span class="badge bg-success">
Approved
</span>

<?php elseif(($user['status'] ?? '')=="Pending"): ?>

<span class="badge bg-warning text-dark">
Pending
</span>

<?php else: ?>

<span class="badge bg-danger">
Blocked
</span>

<?php endif; ?>

</td>




<td>


<a href="<?= BASE_URL ?>/admin/user/edit_user.php?id=<?= $user['id'] ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil"></i>

</a>



<a href="<?= BASE_URL ?>/admin/user/delete_user.php?id=<?= $user['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this user?');">

<i class="bi bi-trash"></i>

</a>


</td>


</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="8" class="text-center">

No users found

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>




</div>

</div>




</div>


</main>


</div>




<?php require_once __DIR__ . "/../../includes/footer.php"; ?>