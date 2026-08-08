<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/user_check.php";
require_once __DIR__ . "/../../includes/db.php";


$page_title = "Sales";


require_once __DIR__ . "/../../includes/user_header.php";



// Get sales

$stmt = $conn->prepare("
    SELECT *
    FROM sales
    ORDER BY id DESC
");


$stmt->execute();


$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="container-fluid">


    <div class="mb-4">


        <h2 class="fw-bold">

            <i class="bi bi-cart-check text-primary"></i>

            My Sales

        </h2>


        <p class="text-muted">

            View your sales and invoices

        </p>


    </div>




    <div class="card shadow-sm border-0 rounded-4">


        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-hover align-middle">


                    <thead class="table-dark">


                        <tr>

                            <th>ID</th>

                            <th>Customer</th>

                            <th>Total</th>

                            <th>Date</th>

                            <th>Status</th>

                        </tr>


                    </thead>



                    <tbody>


                    <?php if(count($sales)>0): ?>


                        <?php foreach($sales as $sale): ?>


                        <tr>


                            <td>
                                <?= $sale['id']; ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($sale['customer_name'] ?? ''); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($sale['total'] ?? ''); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($sale['created_at'] ?? ''); ?>
                            </td>


                            <td>

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            </td>


                        </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td colspan="5" class="text-center text-muted">

                                No sales found

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