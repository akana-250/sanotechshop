<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../config/config.php";
require_once "../includes/user_check.php";



$user_id = $_SESSION['user_id'];




// ===============================
// MARK ALL AS READ
// ===============================

if(isset($_GET['read'])){


    $stmt = $conn->prepare("

        UPDATE notifications

        SET is_read = 1

        WHERE user_id = ?

    ");


    $stmt->execute([$user_id]);


    header("Location: notifications.php");

    exit();

}





// ===============================
// GET USER NOTIFICATIONS
// ===============================


$stmt = $conn->prepare("

SELECT *

FROM notifications

WHERE user_id = ?

ORDER BY id DESC

");


$stmt->execute([$user_id]);


$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);





// ===============================
// UNREAD COUNT
// ===============================


$countStmt = $conn->prepare("

SELECT COUNT(*) 

FROM notifications

WHERE user_id = ?

AND is_read = 0

");


$countStmt->execute([$user_id]);


$unread = $countStmt->fetchColumn();





// ===============================
// ACTIVITY LOG
// ===============================

try{


$log = $conn->prepare("

INSERT INTO activity_logs

(user_id,activity)

VALUES(?,?)

");


$log->execute([

$user_id,

"Viewed Notifications"

]);


}catch(Exception $e){}





$page_title="Notifications";



include "../includes/header.php";

include "../includes/user_sidebar.php";

?>





<div class="container-fluid py-4">





<div class="d-flex justify-content-between align-items-center mb-4">



<div>


<h2 class="fw-bold">


<i class="bi bi-bell"></i>

Notifications


<?php if($unread>0): ?>


<span class="badge bg-danger">

<?= $unread; ?>

</span>


<?php endif; ?>


</h2>



<p class="text-muted">

System alerts and updates


</p>


</div>




<a href="notifications.php?read=1"

class="btn btn-primary">


<i class="bi bi-check-all"></i>

Mark All Read


</a>




</div>








<div class="card shadow-sm">



<div class="card-body">





<?php if(count($notifications)>0): ?>




<?php foreach($notifications as $note): ?>



<div class="card mb-3 border-0 shadow-sm">



<div class="card-body d-flex justify-content-between">





<div>



<h5 class="fw-bold">


<?= htmlspecialchars($note['title']); ?>


</h5>




<p class="mb-1">


<?= htmlspecialchars($note['message']); ?>


</p>




<small class="text-muted">


<i class="bi bi-clock"></i>


<?= date(

"d M Y H:i",

strtotime($note['created_at'])

); ?>


</small>



</div>





<div>


<?php if($note['is_read']==0): ?>


<span class="badge bg-danger">

NEW

</span>



<?php else: ?>


<span class="badge bg-secondary">

READ

</span>



<?php endif; ?>



</div>




</div>



</div>




<?php endforeach; ?>





<?php else: ?>




<div class="text-center py-5">


<i class="bi bi-bell-slash display-3 text-muted"></i>



<h5 class="mt-3">

No Notifications


</h5>



<p class="text-muted">

You don't have notifications yet.

</p>



</div>





<?php endif; ?>





</div>


</div>





</div>





<?php include "../includes/footer.php"; ?>