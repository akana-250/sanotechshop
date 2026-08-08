<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');



if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";

require_once __DIR__ . "/../../includes/db.php";






try{



/*
|--------------------------------------------------------------------------
| GET SALES DATA
|--------------------------------------------------------------------------
*/


$stmt = $conn->query("

SELECT


sales.invoice_no,


customers.customer_name,


sales.total,


sales.paid,


sales.balance,


sales.payment_method,


sales.status,


sales.sale_date



FROM sales



LEFT JOIN customers


ON customers.id = sales.customer_id



ORDER BY sales.id DESC



");



$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);








}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();



header("Location: reports.php");

exit;


}







/*
|--------------------------------------------------------------------------
| DOWNLOAD CSV
|--------------------------------------------------------------------------
*/


header('Content-Type: text/csv; charset=utf-8');


header('Content-Disposition: attachment; filename=sales_report.csv');





$output = fopen("php://output","w");





fputcsv($output,[


"Invoice",

"Customer",

"Total",

"Paid",

"Balance",

"Payment Method",

"Status",

"Date"


]);


foreach($sales as $sale){



    fputcsv($output,[



        $sale['invoice_no'],


        $sale['customer_name'] ?? 'Walk In',


        number_format((float)$sale['total'],2,'.',''),


        number_format((float)$sale['paid'],2,'.',''),


        number_format((float)$sale['balance'],2,'.',''),


        $sale['payment_method'],


        $sale['status'],


        $sale['sale_date']



    ]);



}







fclose($output);



exit;


?>