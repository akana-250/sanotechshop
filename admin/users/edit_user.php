<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";



$page_title = "Edit User";





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
   GET USER DATA
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






/* ===========================
   UPDATE USER
=========================== */


if(isset($_POST['update_user'])){


    $fullname = trim($_POST['fullname']);

    $username = trim($_POST['username']);

    $email = trim($_POST['email']);

    $phone = trim($_POST['phone']);

    $role = $_POST['role'];

    $status = $_POST['status'];



    try{


        if(!empty($_POST['password'])){


            $password = password_hash(

                $_POST['password'],

                PASSWORD_DEFAULT

            );


            $stmt = $conn->prepare("

            UPDATE users SET

            fullname=?,
            username=?,
            email=?,
            phone=?,
            password=?,
            role=?,
            status=?

            WHERE id=?

            ");



            $stmt->execute([

                $fullname,
                $username,
                $email,
                $phone,
                $password,
                $role,
                $status,
                $id

            ]);



        }else{


            $stmt = $conn->prepare("

            UPDATE users SET

            fullname=?,
            username=?,
            email=?,
            phone=?,
            role=?,
            status=?

            WHERE id=?

            ");



            $stmt->execute([

                $fullname,
                $username,
                $email,
                $phone,
                $role,
                $status,
                $id

            ]);

        }



        $_SESSION['success']="User updated successfully.";

        header("Location:index.php");

        exit;



    }catch(PDOException $e){


        $_SESSION['error']=$e->getMessage();


    }


}





require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>



<main class="admin-content">


<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2>

<i class="fa-solid fa-user-pen"></i>

Edit User

</h2>


<p class="text-muted">

Update system user information

</p>


</div>




<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>


</div>







<?php if(isset($_SESSION['error'])): ?>


<div class="alert alert-danger">

<i class="fa-solid fa-triangle-exclamation"></i>

<?= $_SESSION['error']; ?>

</div>


<?php unset($_SESSION['error']); ?>


<?php endif; ?>








<div class="content-card">





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

value="<?= htmlspecialchars($user['phone'] ?? ''); ?>">


</div>








<div class="col-md-6 mb-3">


<label class="form-label">

New Password

</label>


<input

type="password"

name="password"

class="form-control"

placeholder="Leave empty to keep old password">


</div>








<div class="col-md-3 mb-3">


<label class="form-label">

Role

</label>



<select name="role"

class="form-select">



<option value="User"

<?= ($user['role']=="User") ? "selected" : ""; ?>>

User

</option>





<option value="Admin"

<?= ($user['role']=="Admin") ? "selected" : ""; ?>>

Admin

</option>



</select>


</div>








<div class="col-md-3 mb-3">


<label class="form-label">

Status

</label>



<select name="status"

class="form-select">



<option value="Pending"

<?= ($user['status']=="Pending") ? "selected" : ""; ?>>

Pending

</option>





<option value="Approved"

<?= ($user['status']=="Approved") ? "selected" : ""; ?>>

Approved

</option>



</select>


</div>





</div>






<div class="mt-4">


<button

type="submit"

name="update_user"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>


Update User


</button>




<a href="index.php"

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