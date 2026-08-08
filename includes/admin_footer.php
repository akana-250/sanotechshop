<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();

}

?>



</div> <!-- END admin-body -->



</div> <!-- END admin-wrapper -->



<!-- ===========================
     FOOTER
=========================== -->


<footer class="admin-footer">



<div class="container-fluid">



<div class="text-center">


<p class="mb-0">


&copy;

<?= date('Y'); ?>


<?= htmlspecialchars(SITE_NAME); ?>


.

All Rights Reserved.



</p>


</div>



</div>



</footer>




<!-- Bootstrap JS -->

<script

src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">

</script>





<!-- Admin JS -->

<script

src="<?= BASE_URL; ?>/assets/js/admin.js">

</script>





</body>


</html>