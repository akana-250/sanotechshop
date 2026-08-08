<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../config/config.php";
require_once "../includes/functions.php";


/*
|--------------------------------------------------------------------------
| IF ALREADY LOGGED IN
|--------------------------------------------------------------------------
*/

if(isset($_SESSION['user_id'])){


    if(strtolower($_SESSION['role']) == "admin"){

        redirect("../admin/dashboard.php");

    }else{

        redirect("../user/dashboard.php");

    }

}



$message = "";



/*
|--------------------------------------------------------------------------
| LOGIN PROCESS
|--------------------------------------------------------------------------
*/

if(isset($_POST['login'])){


    $login = trim($_POST['login_input']);
    $password = $_POST['password'];



    if(empty($login) || empty($password)){


        $message = "Please enter username/email and password";


    }else{


        $stmt = $conn->prepare("
            SELECT *
            FROM users
            WHERE username = ?
            OR email = ?
            LIMIT 1
        ");


        $stmt->execute([
            $login,
            $login
        ]);


        $user = $stmt->fetch(PDO::FETCH_ASSOC);



        if($user){



            if(password_verify($password,$user['password'])){



                /*
                |--------------------------------------------------------------------------
                | CHECK ACCOUNT STATUS
                |--------------------------------------------------------------------------
                */


                if(strtolower($user['status']) != "approved"){


                    $message = "Your account is waiting for Admin approval";


                }else{


                    session_regenerate_id(true);



                    $_SESSION['user_id'] = $user['id'];

                    $_SESSION['fullname'] = $user['fullname'];

                    $_SESSION['username'] = $user['username'];

                    $_SESSION['role'] = $user['role'];



                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE LAST LOGIN
                    |--------------------------------------------------------------------------
                    */


                    $update = $conn->prepare("
                        UPDATE users
                        SET last_login = NOW()
                        WHERE id = ?
                    ");


                    $update->execute([
                        $user['id']
                    ]);



                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVITY LOG
                    |--------------------------------------------------------------------------
                    */


                    createActivityLog(
                        $conn,
                        $user['id'],
                        "User logged in"
                    );




                    /*
                    |--------------------------------------------------------------------------
                    | ROLE REDIRECT
                    |--------------------------------------------------------------------------
                    */


                    if(strtolower($user['role']) == "admin"){


                        redirect("../admin/dashboard.php");


                    }else{


                        redirect("../user/dashboard.php");


                    }


                }



            }else{


                $message = "Wrong password";


            }



        }else{


            $message = "User not found";


        }



    }



}



?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SANO TECH SHOP LOGIN</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">



<div class="container">


<div class="row justify-content-center align-items-center vh-100">


<div class="col-md-5">


<div class="card shadow-lg border-0 rounded-4">


<div class="card-body p-4">



<div class="text-center mb-4">


<img src="../assets/img/logo.png" width="90">


<h3 class="mt-3">

SANO TECH SHOP

</h3>


<p class="text-muted">

Login to continue

</p>


</div>




<?php if($message!=""): ?>


<div class="alert alert-danger">

<?= htmlspecialchars($message); ?>

</div>


<?php endif; ?>





<form method="POST">



<div class="mb-3">


<label class="form-label">

Username or Email

</label>


<input

type="text"

name="login_input"

class="form-control"

required

>


</div>





<div class="mb-3">


<label class="form-label">

Password

</label>


<input

type="password"

name="password"

class="form-control"

required

>


</div>





<button

type="submit"

name="login"

class="btn btn-primary w-100">

LOGIN

</button>




</form>





<div class="text-center mt-3">


<a href="register.php">

Create New Account

</a>


</div>





<hr>





<div class="d-grid">


<a href="../index.php" class="btn btn-outline-secondary">

← Back To Home

</a>


</div>




</div>


</div>


</div>


</div>


</div>



</body>

</html>