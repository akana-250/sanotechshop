<?php

session_start();

require_once "../config/config.php";

$message = "";


if(isset($_POST['register'])){


    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];



    if(empty($fullname) || empty($username) || empty($email) || empty($password)){


        $message = "Please fill required fields";


    }else{


        $check = $conn->prepare(
            "SELECT id FROM users 
             WHERE username=? OR email=?"
        );


        $check->execute([
            $username,
            $email
        ]);



        if($check->fetch()){


            $message = "Username or Email already exists";


        }else{


            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );



            $stmt = $conn->prepare(
                "INSERT INTO users
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
                )"
            );



            $stmt->execute([
                $fullname,
                $username,
                $email,
                $phone,
                $hash,
                "User",
                "Pending"
            ]);



            $message = "Account created successfully. Wait for Admin approval.";

        }


    }


}

?>


<!DOCTYPE html>
<html>

<head>

<title>
Register - SANO TECH SHOP
</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


<div class="container mt-5">


<div class="row justify-content-center">


<div class="col-md-5">


<div class="card shadow">


<div class="card-body">


<h3 class="text-center mb-4">
Create Account
</h3>



<?php if($message!=""): ?>

<div class="alert alert-info">
<?= $message ?>
</div>

<?php endif; ?>



<form method="POST">


<div class="mb-3">

<label>Full Name</label>

<input type="text"
name="fullname"
class="form-control"
required>

</div>



<div class="mb-3">

<label>Username</label>

<input type="text"
name="username"
class="form-control"
required>

</div>



<div class="mb-3">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
required>

</div>



<div class="mb-3">

<label>Phone Number</label>

<input type="text"
name="phone"
class="form-control">

</div>



<div class="mb-3">

<label>Password</label>

<input type="password"
name="password"
class="form-control"
required>

</div>



<button type="submit"
name="register"
class="btn btn-success w-100">

Register

</button>


</form>



<hr>


<div class="text-center">

<a href="login.php">
Already have account? Login
</a>

</div>


</div>

</div>

</div>

</div>

</div>


</body>

</html>