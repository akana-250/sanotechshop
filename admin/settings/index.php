<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";



$page_title = "System Settings";





/* ===========================
   GET SETTINGS
=========================== */


try{


$stmt = $conn->prepare("

SELECT *

FROM settings

LIMIT 1

");


$stmt->execute();


$settings = $stmt->fetch(PDO::FETCH_ASSOC);



}catch(PDOException $e){


$settings = [];

$_SESSION['error'] = $e->getMessage();


}








/* ===========================
   UPDATE SETTINGS
=========================== */


if(isset($_POST['update'])){


$company_name = trim($_POST['company_name']);

$phone = trim($_POST['phone']);

$email = trim($_POST['email']);

$address = trim($_POST['address']);

$currency = trim($_POST['currency']);





$logo = $settings['logo'] ?? null;






/* ===========================
   LOGO UPLOAD
=========================== */


if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){



$allowed = [

"image/jpeg",

"image/png",

"image/webp"

];



if(in_array($_FILES['logo']['type'],$allowed)){



$folder = __DIR__ . "/../../assets/img/";



if(!is_dir($folder)){


mkdir($folder,0777,true);


}



$file_name = time() . "_" . basename($_FILES['logo']['name']);



$upload = $folder . $file_name;



if(move_uploaded_file(

$_FILES['logo']['tmp_name'],

$upload

)){



$logo = $file_name;



}



}



}







try{



if($settings){



$update = $conn->prepare("

UPDATE settings SET

company_name=?,

phone=?,

email=?,

address=?,

logo=?,

currency=?

WHERE id=?

");




$update->execute([


$company_name,

$phone,

$email,

$address,

$logo,

$currency,

$settings['id']


]);





}else{



$insert = $conn->prepare("

INSERT INTO settings

(

company_name,

phone,

email,

address,

logo,

currency

)

VALUES

(?,?,?,?,?,?)

");





$insert->execute([


$company_name,

$phone,

$email,

$address,

$logo,

$currency


]);



}




$_SESSION['success'] = "Settings updated successfully";


header("Location:index.php");

exit;



}catch(PDOException $e){


$_SESSION['error'] = $e->getMessage();


}



}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>





<main class="admin-content">


<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2>

<i class="fa-solid fa-gear"></i>

Settings

</h2>


<p class="text-muted">

Manage system settings

</p>


</div>


</div>








<?php if(isset($_SESSION['success'])): ?>


<div class="alert alert-success">

<?= $_SESSION['success']; ?>

</div>


<?php unset($_SESSION['success']); ?>


<?php endif; ?>








<?php if(isset($_SESSION['error'])): ?>


<div class="alert alert-danger">

<?= $_SESSION['error']; ?>

</div>


<?php unset($_SESSION['error']); ?>


<?php endif; ?>









<div class="content-card">





<form method="POST" enctype="multipart/form-data">






<div class="row">







<div class="col-md-6 mb-3">


<label class="form-label">

Company Name

</label>


<input

type="text"

name="company_name"

class="form-control"

value="<?= htmlspecialchars($settings['company_name'] ?? ''); ?>"

required>


</div>







<div class="col-md-6 mb-3">


<label class="form-label">

Phone

</label>


<input

type="text"

name="phone"

class="form-control"

value="<?= htmlspecialchars($settings['phone'] ?? ''); ?>">


</div>








<div class="col-md-6 mb-3">


<label class="form-label">

Email

</label>


<input

type="email"

name="email"

class="form-control"

value="<?= htmlspecialchars($settings['email'] ?? ''); ?>">


</div>








<div class="col-md-6 mb-3">


<label class="form-label">

Currency

</label>



<select name="currency"

class="form-select">


<option value="RWF"
<?= (($settings['currency'] ?? '')=="RWF")?'selected':''; ?>>

RWF

</option>


<option value="USD"
<?= (($settings['currency'] ?? '')=="USD")?'selected':''; ?>>

USD

</option>


</select>


</div>








<div class="col-md-12 mb-3">


<label class="form-label">

Address

</label>



<textarea

name="address"

class="form-control"

rows="4"><?= htmlspecialchars($settings['address'] ?? ''); ?></textarea>


</div>









<div class="col-md-6 mb-3">


<label class="form-label">

Current Logo

</label>



<br>



<?php if(!empty($settings['logo'])): ?>


<img

src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($settings['logo']); ?>"

width="150"

class="img-thumbnail mb-3">


<?php endif; ?>





<input

type="file"

name="logo"

class="form-control"

accept="image/*">


</div>






</div>






<button

type="submit"

name="update"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>


Update Settings


</button>






</form>





</div>


</div>


</main>






<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>