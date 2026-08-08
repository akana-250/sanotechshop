<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/user_check.php";

$page_title = "Customers";

require_once __DIR__ . "/../../includes/user_header.php";

$stmt = $conn->query("
SELECT *
FROM customers
ORDER BY id DESC
");

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

    </div>