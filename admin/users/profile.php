<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}


require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";



$page_title = "User Profile";





/* ===========================
   CHECK USER ID
=========================== */


if(!isset($_GET['id'])){


    $_SESSION['error'] = "User ID missing.";

    header("Location:index.php");

    exit;


}



$id = (int) $_GET['id'];







/* ===========================
   GET USER
=========================== */


try{


$stmt = $conn->prepare("

SELECT *

FROM users

WHERE id = ?

");


$stmt->execute([$id]);


$user = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$user){


    $_SESSION['error'] = "User not found.";

    header("Location:index.php");

    exit;


}



}catch(PDOException $e){


    $_SESSION['error'] = $e->getMessage();

    header("Location:index.php");

    exit;


}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>




<main class="admin-content">


<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2>

<i class="fa-solid fa-id-card"></i>

User Profile

</h2>


<p class="text-muted">

View user account information

</p>


</div>




<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>







<div class="content-card">



<div class="row">







<div class="col-md-4 text-center">



<?php if(!empty($user['photo'])): ?>


<img src="<?= BASE_URL ?>/assets/uploads/users/<?= htmlspecialchars($user['photo']); ?>"

class="img-fluid rounded-circle mb-3"

style="width:150px;height:150px;object-fit:cover;">



<?php else: ?>


<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3"

style="width:150px;height:150px;font-size:60px;">


<i class="fa-solid fa-user"></i>


</div>



<?php endif; ?>





<h4>

<?= htmlspecialchars($user['fullname']); ?>

</h4>




<span class="badge bg-primary">

<?= htmlspecialchars($user['role']); ?>

</span>



</div>







<div class="col-md-8">





<table class="table">



<tr>

<th width="180">

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

<?= htmlspecialchars($user['phone'] ?? '-'); ?>

</td>


</tr>







<tr>

<th>

Role

</th>


<td>


<?= htmlspecialchars($user['role']); ?>


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


<?php else: ?>


<span class="badge bg-warning text-dark">

Pending

</span>


<?php endif; ?>


</td>


</tr>
<tr>

<th>

Last Login

</th>


<td>

<?= htmlspecialchars($user['last_login'] ?? 'Never'); ?>

</td>


</tr>






<tr>

<th>

Created At

</th>


<td>

<?= htmlspecialchars($user['created_at']); ?>

</td>


</tr>






<tr>

<th>

Updated At

</th>


<td>

<?= htmlspecialchars($user['updated_at']); ?>

</td>


</tr>





</table>





<div class="mt-4">


<a href="edit_user.php?id=<?= $user['id']; ?>"

class="btn btn-warning">


<i class="fa-solid fa-pen"></i>


Edit User


</a>




<?php if($user['status']=="Pending"): ?>


<a href="approve_user.php?id=<?= $user['id']; ?>"

class="btn btn-success"

onclick="return confirm('Approve this user?');">


<i class="fa-solid fa-user-check"></i>


Approve User


</a>


<?php endif; ?>





</div>





</div>


</div>


</div>



</div>


</main>






<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>