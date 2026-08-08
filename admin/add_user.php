<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once "../config/config.php";
require_once "../includes/admin_check.php";


$error = "";



if(isset($_POST['save'])){


    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];
    $status   = $_POST['status'];



    // ============================
    // CHECK EMPTY FIELDS
    // ============================

    if(
        empty($fullname) ||
        empty($username) ||
        empty($password)
    ){

        $error = "Please fill all required fields";


    }else{


        // ============================
        // CHECK DUPLICATE USERNAME OR EMAIL
        // ============================


        $check = $conn->prepare("
            SELECT id 
            FROM users
            WHERE username = ?
            OR email = ?
        ");


        $check->execute([
            $username,
            $email
        ]);



        if($check->fetch()){


            $error = "Username or Email already exists";


        }else{


            // ============================
            // PASSWORD HASH
            // ============================


            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );



            // ============================
            // INSERT USER
            // ============================


            $stmt = $conn->prepare("

                INSERT INTO users
                (
                    fullname,
                    username,
                    email,
                    phone,
                    password,
                    role,
                    status,
                    created_at
                )

                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    NOW()
                )

            ");



            $stmt->execute([

                $fullname,
                $username,
                $email,
                $phone,
                $hashed_password,
                $role,
                $status

            ]);



            header(
                "Location: users.php"
            );

            exit();


        }


    }


}



require_once "../includes/header.php";



?>


<div class="container-fluid">


<div class="card shadow">


<div class="card-header bg-primary text-white">

<h3>
Add New User
</h3>

</div>



<div class="card-body">



<?php if(!empty($error)): ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>




<form method="POST">



<div class="mb-3">

<label>
Full Name *
</label>

<input 
type="text"
name="fullname"
class="form-control"
required>

</div>




<div class="mb-3">

<label>
Username *
</label>

<input 
type="text"
name="username"
class="form-control"
required>

</div>




<div class="mb-3">

<label>
Email
</label>

<input 
type="email"
name="email"
class="form-control">

</div>




<div class="mb-3">

<label>
Phone
</label>

<input 
type="text"
name="phone"
class="form-control">

</div>




<div class="mb-3">

<label>
Password *
</label>

<input 
type="password"
name="password"
class="form-control"
required>

</div>




<div class="mb-3">

<label>
Role
</label>


<select 
name="role"
class="form-control">


<option value="User">
User
</option>


<option value="Admin">
Admin
</option>


</select>


</div>




<div class="mb-3">

<label>
Status
</label>


<select 
name="status"
class="form-control">


<option value="Approved">
Approved
</option>


<option value="Pending">
Pending
</option>


</select>


</div>




<button 
type="submit"
name="save"
class="btn btn-success">

Save User

</button>



<a href="users.php"
class="btn btn-secondary">

Back

</a>



</form>



</div>

</div>


</div>



<?php

require_once "../includes/footer.php";

?>