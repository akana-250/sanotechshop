<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();

}


require_once __DIR__ . "/../config/config.php";



$page_title = $page_title ?? "Admin Panel";





/* ===========================
   GET SYSTEM SETTINGS
=========================== */


try{


$stmt = $conn->prepare("

SELECT *

FROM settings

LIMIT 1

");


$stmt->execute();


$system_settings = $stmt->fetch(PDO::FETCH_ASSOC);



}catch(PDOException $e){


$system_settings = [];


}






$company_name = $system_settings['company_name'] 
?? SITE_NAME;





if(!empty($system_settings['logo'])){


$company_logo = BASE_URL . "/assets/img/" . $system_settings['logo'];


}else{


$company_logo = BASE_URL . "/assets/img/logo.png";


}



?>





<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">





<title>

<?= htmlspecialchars($page_title); ?>

|

<?= htmlspecialchars($company_name); ?>

</title>





<!-- Bootstrap -->

<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"

rel="stylesheet">





<!-- Bootstrap Icons -->

<link

href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"

rel="stylesheet">





<!-- Font Awesome -->

<link

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"

rel="stylesheet">





<!-- Admin CSS -->

<link

href="<?= BASE_URL; ?>/assets/css/admin.css"

rel="stylesheet">



</head>





<body>


<div class="admin-wrapper">
<!-- ===========================
     TOP NAVBAR
=========================== -->


<header class="admin-topbar">





<div class="topbar-left">





<button

class="sidebar-toggle"

id="sidebarToggle">


<i class="fa-solid fa-bars"></i>


</button>







<a href="<?= BASE_URL; ?>/admin/dashboard.php"

class="admin-brand">





<img

src="<?= htmlspecialchars($company_logo); ?>"

alt="<?= htmlspecialchars($company_name); ?>"

class="admin-logo">






<span>

<?= htmlspecialchars($company_name); ?>

</span>





</a>





</div>









<div class="topbar-right">








<!-- NOTIFICATIONS -->


<div class="topbar-item">


<a href="<?= BASE_URL; ?>/admin/users/notification.php"

class="text-decoration-none text-dark">


<i class="fa-solid fa-bell"></i>



<span class="notification-badge">

0

</span>



</a>


</div>









<!-- USER MENU -->


<div class="user-menu">





<img

src="<?= BASE_URL; ?>/assets/img/user.png"

class="user-avatar"

alt="User">







<div class="user-info">





<strong>


<?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin'); ?>


</strong>





<small>


<?= htmlspecialchars($_SESSION['role'] ?? 'Administrator'); ?>


</small>





</div>





</div>








</div>






</header>
<!-- ===========================
     ADMIN BODY START
=========================== -->


<div class="admin-body">