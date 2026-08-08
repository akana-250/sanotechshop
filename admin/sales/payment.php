<?php

declare(strict_types=1);

ob_start();


error_reporting(E_ALL);
ini_set('display_errors','1');



if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";

require_once __DIR__ . "/../../includes/db.php";




$page_title = "Payment Management";




$sale_id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($sale_id <= 0){


    header("Location: index.php");

    exit;


}






try{


/*
|--------------------------------------------------------------------------
| GET SALE
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT


sales.*,


customers.customer_name,

customers.phone


FROM sales


LEFT JOIN customers


ON customers.id = sales.customer_id


WHERE sales.id = ?



");



$stmt->execute([$sale_id]);



$sale = $stmt->fetch(PDO::FETCH_ASSOC);





if(!$sale){


    throw new Exception("Sale not found");


}





}catch(Exception $e){


    $_SESSION['error'] = $e->getMessage();


    header("Location: index.php");

    exit;


}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>


<main>


<div class="container-fluid"><?php

declare(strict_types=1);

ob_start();


error_reporting(E_ALL);
ini_set('display_errors','1');



if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";

require_once __DIR__ . "/../../includes/db.php";




$page_title = "Payment Management";




$sale_id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($sale_id <= 0){


    header("Location: index.php");

    exit;


}






try{


/*
|--------------------------------------------------------------------------
| GET SALE
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT


sales.*,


customers.customer_name,

customers.phone


FROM sales


LEFT JOIN customers


ON customers.id = sales.customer_id


WHERE sales.id = ?



");



$stmt->execute([$sale_id]);



$sale = $stmt->fetch(PDO::FETCH_ASSOC);





if(!$sale){


    throw new Exception("Sale not found");


}





}catch(Exception $e){


    $_SESSION['error'] = $e->getMessage();


    header("Location: index.php");

    exit;


}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>


<main>


<div class="container-fluid">



<div class="mt-4 d-flex justify-content-end gap-2">


<a href="index.php"

class="btn btn-secondary">


Cancel


</a>





<button type="submit"

class="btn btn-success">


<i class="fa-solid fa-check"></i>


Receive Payment


</button>



</div>





</form>



</div>


</div>





</div>


</main>






<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>