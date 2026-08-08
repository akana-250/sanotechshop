<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/user_check.php";
require_once __DIR__ . "/../includes/db.php";


$page_title = "My Profile";


require_once __DIR__ . "/../includes/user_header.php";



// Get current user

$user_id = $_SESSION['user_id'] ?? null;


$user = null;


if($user_id){

    $stmt = $conn->prepare("
        SELECT *
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$user_id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

}


?>



<div class="container-fluid">


    <div class="mb-4">


        <h2 class="fw-bold">

            <i class="bi bi-person-circle text-primary"></i>

            My Profile

        </h2>


        <p class="text-muted">

            View your account information

        </p>


    </div>





    <div class="row">


        <div class="col-lg-4">


            <div class="card shadow-sm border-0 rounded-4">


                <div class="card-body text-center">


                    <div class="mb-3">


                        <i class="bi bi-person-circle"
                           style="font-size:80px;color:#2563eb;">
                        </i>


                    </div>



                    <h4 class="fw-bold">

                        <?= htmlspecialchars($user['name'] ?? 'User'); ?>

                    </h4>


                    <p class="text-muted">

                        <?= htmlspecialchars($user['email'] ?? ''); ?>

                    </p>



                </div>


            </div>


        </div>





        <div class="col-lg-8">


            <div class="card shadow-sm border-0 rounded-4">


                <div class="card-body">


                    <h5 class="fw-bold mb-4">

                        Account Details

                    </h5>




                    <div class="row mb-3">


                        <div class="col-md-4 fw-bold">

                            Full Name

                        </div>


                        <div class="col-md-8">

                            <?= htmlspecialchars($user['name'] ?? ''); ?>

                        </div>


                    </div>




                    <div class="row mb-3">


                        <div class="col-md-4 fw-bold">

                            Email

                        </div>


                        <div class="col-md-8">

                            <?= htmlspecialchars($user['email'] ?? ''); ?>

                        </div>


                    </div>





                    <div class="row mb-3">


                        <div class="col-md-4 fw-bold">

                            Phone

                        </div>


                        <div class="col-md-8">

                            <?= htmlspecialchars($user['phone'] ?? 'Not available'); ?>

                        </div>


                    </div>





                    <div class="row mb-3">


                        <div class="col-md-4 fw-bold">

                            Role

                        </div>


                        <div class="col-md-8">


                            <span class="badge bg-primary">

                                <?= htmlspecialchars($user['role'] ?? 'User'); ?>

                            </span>


                        </div>


                    </div>




                    <a href="<?= BASE_URL ?>/user/dashboard.php"
                       class="btn btn-secondary mt-3">

                        <i class="bi bi-arrow-left"></i>

                        Back Dashboard

                    </a>


                </div>


            </div>


        </div>


    </div>


</div>




<?php require_once __DIR__ . "/../includes/footer.php"; ?>