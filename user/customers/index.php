<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/user_check.php";
require_once __DIR__ . "/../../includes/db.php";


$page_title = "Customers";


require_once __DIR__ . "/../../includes/user_header.php";


// Get customers

$stmt = $conn->prepare("
    SELECT *
    FROM customers
    ORDER BY id DESC
");

$stmt->execute();


$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h2 class="fw-bold">

                <i class="bi bi-people text-primary"></i>

                Customers

            </h2>


            <p class="text-muted">

                Manage customer information

            </p>


        </div>


        <div>

            <a href="add.php" class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                Add Customer

            </a>

        </div>


    </div>





    <div class="card shadow-sm border-0 rounded-4">


        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-hover align-middle">


                    <thead class="table-dark">


                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Phone</th>

                            <th>Email</th>

                            <th>Address</th>

                            <th>Action</th>

                        </tr>


                    </thead>



                    <tbody>


                    <?php if(count($customers)>0): ?>


                        <?php foreach($customers as $customer): ?>


                            <tr>


                                <td>
                                    <?= $customer['id']; ?>
                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $customer['name'] ?? 
                                        $customer['customer_name'] ?? 
                                        ''
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $customer['phone'] ?? ''
                                    ); ?>

                                </td>



                                <td>

                                    <?= htmlspecialchars(
                                        $customer['email'] ?? ''
                                    ); ?>

                                </td>



                                <td>

                                    <?= htmlspecialchars(
                                        $customer['address'] ?? ''
                                    ); ?>

                                </td>



                                <td>


                                    <a href="edit.php?id=<?= $customer['id']; ?>"
                                       class="btn btn-sm btn-warning">

                                        <i class="bi bi-pencil"></i>

                                    </a>



                                    <a href="delete.php?id=<?= $customer['id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this customer?');">

                                        <i class="bi bi-trash"></i>

                                    </a>


                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td colspan="6" class="text-center text-muted">

                                No customers found

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>



<?php require_once __DIR__ . "/../../includes/footer.php"; ?>