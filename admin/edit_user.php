<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "../config/config.php";
require_once "../includes/admin_check.php";


$error = "";


// Check ID

if(!isset($_GET['id'])){

    header("Location: users.php");
    exit();

}


$id = $_GET['id'];



// Get User

$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE id = ?
");

$stmt->execute([$id]);

$user = $stmt->fetch();



if(!$user){

    header("Location: users.php");
    exit();

}




// Update User

if(isset($_POST['update'])){


    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = $_POST['role'];
    $status   = $_POST['status'];
    $password = trim($_POST['password']);



    // Check duplicate

    $check = $conn->prepare("
        SELECT id
        FROM users
        WHERE (username = ? OR email = ?)
        AND id != ?
    ");


    $check->execute([

        $username,
        $email,
        $id

    ]);



    if($check->fetch()){


        $error = "Username or Email already exists";


    }else{


        if(!empty($password)){


            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            $update = $conn->prepare("

                UPDATE users SET

                fullname=?,
                username=?,
                email=?,
                phone=?,
                password=?,
                role=?,
                status=?,
                updated_at=NOW()

                WHERE id=?

            ");



            $update->execute([

                $fullname,
                $username,
                $email,
                $phone,
                $hash,
                $role,
                $status,
                $id

            ]);



        }else{


            $update = $conn->prepare("

                UPDATE users SET

                fullname=?,
                username=?,
                email=?,
                phone=?,
                role=?,
                status=?,
                updated_at=NOW()

                WHERE id=?

            ");



            $update->execute([

                $fullname,
                $username,
                $email,
                $phone,
                $role,
                $status,
                $id

            ]);

        }



        header("Location: users.php");
        exit();


    }


}



require_once "../includes/header.php";


?>


<div class="container-fluid">


<div class="card shadow">


<div class="card-header bg-warning">

<h3>
Edit User
</h3>

</div>


<div class="card-body">



<?php if($error): ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>




<form method="POST">


<div class="mb-3">

<label>
Full Name
</label>

<input 
type="text"
name="fullname"
class="form-control"
value="<?= htmlspecialchars($user['fullname']); ?>"
required>

</div>



<div class="mb-3">

<label>
Username
</label>

<input 
type="text"
name="username"
class="form-control"
value="<?= htmlspecialchars($user['username']); ?>"
required>

</div>



<div class="mb-3">

<label>
Email
</label>

<input 
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user['email']); ?>">

</div>




<div class="mb-3">

<label>
Phone
</label>

<input 
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($user['phone']); ?>">

</div>



<div class="mb-3">

<label>
New Password
</label>

<input 
type="password"
name="password"
class="form-control">

<small>
Leave empty to keep old password
</small>

</div>



<div class="mb-3">

<label>
Role
</label>

<select name="role" class="form-control">


<option value="Admin"
<?= $user['role']=="Admin"?'selected':''; ?>>
Admin
</option>


<option value="User"
<?= $user['role']=="User"?'selected':''; ?>>
User
</option>


</select>

</div>



<div class="mb-3">

<label>
Status
</label>

<select name="status" class="form-control">


<option value="Approved"
<?= $user['status']=="Approved"?'selected':''; ?>>
Approved
</option>


<option value="Pending"
<?= $user['status']=="Pending"?'selected':''; ?>>
Pending
</option>


</select>


</div>




<button 
name="update"
class="btn btn-success">

Update User

</button>


<a href="users.php"
class="btn btn-secondary">

Back

</a>



</form>


</div>

</div>


</div>


<?php require_once "../includes/footer.php"; ?>