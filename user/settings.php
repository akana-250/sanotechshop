<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

require_once "../config/config.php";


if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit();

}


$user_id=$_SESSION['user_id'];


// GET USER

$stmt=$conn->prepare("

SELECT *

FROM users

WHERE id=?

");


$stmt->execute([$user_id]);

$user=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$user){

    die("User not found");

}




// UPDATE PROFILE

if(isset($_POST['update_profile'])){


$fullname=$_POST['fullname'];
$email=$_POST['email'];
$phone=$_POST['phone'];



$update=$conn->prepare("

UPDATE users SET

fullname=?,
email=?,
phone=?

WHERE id=?

");


$update->execute([

$fullname,
$email,
$phone,
$user_id

]);



$_SESSION['success']="Profile updated successfully";


header("Location: settings.php");

exit();


}




// CHANGE PASSWORD

if(isset($_POST['change_password'])){


$old=$_POST['old_password'];
$new=$_POST['new_password'];



if(password_verify($old,$user['password'])){


$newpass=password_hash($new,PASSWORD_DEFAULT);



$update=$conn->prepare("

UPDATE users

SET password=?

WHERE id=?

");


$update->execute([

$newpass,
$user_id

]);



$_SESSION['success']="Password changed successfully";



}else{


$_SESSION['error']="Old password incorrect";


}



header("Location: settings.php");

exit();


}



?>


<!DOCTYPE html>
<html>

<head>

<title>User Settings | SANO TECH SHOP</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


<style>


body{

background:#f5f7fb;

}



.sidebar{

height:100vh;

background:#0866ff;

color:white;

position:fixed;

width:240px;

}



.sidebar a{

color:white;

text-decoration:none;

display:block;

padding:12px 20px;

}



.sidebar a:hover{

background:#0051d4;

}



.main{

margin-left:240px;

padding:30px;

}



.card{

border:none;

border-radius:15px;

box-shadow:0 5px 20px rgba(0,0,0,.08);

}



.profile-img{

width:120px;

height:120px;

border-radius:50%;

object-fit:cover;

border:5px solid white;

}



</style>


</head>


<body>



<!-- SIDEBAR -->

<div class="sidebar">


<h4 class="p-3">

SANO TECH SHOP

</h4>


<hr>


<a href="dashboard.php">

<i class="bi bi-speedometer2"></i>
Dashboard

</a>


<a href="products.php">

<i class="bi bi-box"></i>
Products

</a>


<a href="quotations.php">

<i class="bi bi-file-text"></i>
Quotations

</a>


<a href="purchases.php">

<i class="bi bi-cart"></i>
Purchases

</a>



<a href="profile.php">

<i class="bi bi-person"></i>
Profile

</a>


<a href="notifications.php">

<i class="bi bi-bell"></i>
Notifications

</a>



<a href="settings.php">

<i class="bi bi-gear"></i>
Settings

</a>



<hr>


<a href="logout.php">

<i class="bi bi-box-arrow-right"></i>
Logout

</a>


</div>




<!-- MAIN -->


<div class="main">



<h2>

⚙️ Account Settings

</h2>


<p class="text-muted">

Manage your account information

</p>



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






<div class="row">



<!-- PROFILE -->

<div class="col-md-4">


<div class="card text-center p-4">


<?php

$photo="../uploads/".$user['photo'];

if(!empty($user['photo']) && file_exists($photo)){

?>

<img src="<?= $photo ?>" class="profile-img mx-auto">


<?php

}else{

?>

<img src="https://ui-avatars.com/api/?name=<?= $user['fullname']; ?>"
class="profile-img mx-auto">


<?php } ?>



<h4 class="mt-3">

<?= htmlspecialchars($user['fullname']); ?>

</h4>


<p>
@<?= htmlspecialchars($user['username']); ?>
</p>



<hr>


<p>
📧 <?= $user['email']; ?>
</p>


<p>
📞 <?= $user['phone']; ?>
</p>



<span class="badge bg-success">

<?= $user['status']; ?>

</span>



</div>


</div>





<!-- SETTINGS FORM -->


<div class="col-md-8">


<div class="card p-4">


<h5>
Personal Information
</h5>


<form method="POST">


<label>Full Name</label>

<input class="form-control mb-3"
name="fullname"
value="<?= $user['fullname']; ?>">



<label>Email</label>

<input class="form-control mb-3"
name="email"
value="<?= $user['email']; ?>">



<label>Phone</label>

<input class="form-control mb-3"
name="phone"
value="<?= $user['phone']; ?>">



<button class="btn btn-primary"
name="update_profile">

Save Changes

</button>


</form>



<hr>


<h5>
🔐 Change Password
</h5>



<form method="POST">


<input type="password"
class="form-control mb-3"
placeholder="Old Password"
name="old_password">



<input type="password"
class="form-control mb-3"
placeholder="New Password"
name="new_password">



<button class="btn btn-warning"
name="change_password">

Change Password

</button>



</form>



</div>


</div>


</div>




</div>



</body>

</html>