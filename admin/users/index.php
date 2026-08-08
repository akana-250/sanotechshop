<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}


require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";



$page_title = "Users Management";





/* ===========================
   GET USERS
=========================== */


try{


$stmt = $conn->prepare("

SELECT *

FROM users

ORDER BY id DESC

");


$stmt->execute();


$users = $stmt->fetchAll(PDO::FETCH_ASSOC);



}catch(Exception $e){


$users = [];


}




require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>





<main class="admin-content">


<div class="container-fluid">





<!-- ===========================
     PAGE HEADER
=========================== -->


<div class="d-flex justify-content-between align-items-center mb-4">



<div>


<h2>


<i class="fa-solid fa-users"></i>


Users Management


</h2>



<p class="text-muted">

Manage system users accounts

</p>



</div>







<a href="add_user.php"

class="btn btn-primary">


<i class="fa-solid fa-user-plus"></i>


Add User


</a>



</div>







<!-- ===========================
     ALERTS
=========================== -->


<?php if(isset($_SESSION['success'])): ?>


<div class="alert alert-success">


<i class="fa-solid fa-circle-check"></i>


<?= $_SESSION['success']; ?>


</div>


<?php unset($_SESSION['success']); ?>


<?php endif; ?>






<?php if(isset($_SESSION['error'])): ?>


<div class="alert alert-danger">


<i class="fa-solid fa-triangle-exclamation"></i>


<?= $_SESSION['error']; ?>


</div>


<?php unset($_SESSION['error']); ?>


<?php endif; ?>







<!-- ===========================
     USERS TABLE CARD
=========================== -->


<div class="content-card">



<div class="table-responsive">



<table class="erp-table">


<thead>


<tr>


<th>#</th>


<th>Full Name</th>


<th>Username</th>


<th>Email</th>


<th>Phone</th>


<th>Role</th>


<th>Status</th>


<th>Created</th>


<th>Actions</th>


</tr>


</thead>





<tbody>
<?php if(!empty($users)): ?>


<?php foreach($users as $key=>$user): ?>


<tr>


<td>

<?= $key + 1; ?>

</td>





<td>

<?= htmlspecialchars($user['fullname'] ?? ''); ?>

</td>





<td>

<?= htmlspecialchars($user['username'] ?? ''); ?>

</td>





<td>

<?= htmlspecialchars($user['email'] ?? ''); ?>

</td>





<td>

<?= htmlspecialchars($user['phone'] ?? '-'); ?>

</td>





<td>


<?php if(($user['role'] ?? '') == "Admin"): ?>


<span class="badge bg-danger">

Admin

</span>


<?php else: ?>


<span class="badge bg-primary">

User

</span>


<?php endif; ?>


</td>







<td>


<?php if(($user['status'] ?? '') == "Approved"): ?>


<span class="badge bg-success">

Approved

</span>


<?php else: ?>


<span class="badge bg-warning text-dark">

Pending

</span>


<?php endif; ?>


</td>







<td>

<?= htmlspecialchars($user['created_at'] ?? ''); ?>

</td>







<td>


<div class="action-buttons">





<a href="profile.php?id=<?= $user['id']; ?>"

class="btn btn-info btn-sm"

title="Profile">


<i class="fa-solid fa-user"></i>


</a>







<a href="edit_user.php?id=<?= $user['id']; ?>"

class="btn btn-warning btn-sm"

title="Edit">


<i class="fa-solid fa-pen"></i>


</a>








<?php if(($user['status'] ?? '') == "Pending"): ?>


<a href="approve_user.php?id=<?= $user['id']; ?>"

class="btn btn-success btn-sm"

onclick="return confirm('Approve this user?');"

title="Approve">


<i class="fa-solid fa-user-check"></i>


</a>


<?php endif; ?>







<a href="orders.php?id=<?= $user['id']; ?>"

class="btn btn-primary btn-sm"

title="Orders">


<i class="fa-solid fa-cart-shopping"></i>


</a>








<a href="quotations.php?id=<?= $user['id']; ?>"

class="btn btn-secondary btn-sm"

title="Quotations">


<i class="fa-solid fa-file-contract"></i>


</a>








<a href="delete_user.php?id=<?= $user['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Delete this user?');"

title="Delete">


<i class="fa-solid fa-trash"></i>


</a>






</div>


</td>





</tr>




<?php endforeach; ?>





<?php else: ?>



<tr>


<td colspan="9" class="text-center">


<i class="fa-solid fa-users-slash"></i>


No users found.


</td>


</tr>



<?php endif; ?>
</tbody>


</table>


</div>


</div>


</div>


</main>





<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>