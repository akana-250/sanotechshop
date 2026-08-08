<?php

$current_page = basename($_SERVER['PHP_SELF']);

?>


<aside class="admin-sidebar">


<!-- ===========================
     SIDEBAR BRAND
=========================== -->


<div class="sidebar-header">


<a href="<?= BASE_URL ?>/admin/dashboard.php"

class="sidebar-brand">


<img src="<?= BASE_URL ?>/assets/img/logo.png"

alt="SANO TECH SHOP"

class="sidebar-logo"

onerror="this.style.display='none'">


<span>

SANO TECH SHOP

</span>


</a>


</div>





<ul class="sidebar-menu">



<!-- ===========================
     DASHBOARD
=========================== -->


<li class="sidebar-item">


<a href="<?= BASE_URL ?>/admin/dashboard.php"

class="sidebar-link <?= $current_page == 'dashboard.php' ? 'active':''; ?>">


<i class="fa-solid fa-gauge"></i>


<span>

Dashboard

</span>


</a>


</li>







<!-- ===========================
     PRODUCTS MENU
=========================== -->


<li class="sidebar-item">


<a href="#productMenu"

class="sidebar-link collapsed"

data-bs-toggle="collapse">


<i class="fa-solid fa-box"></i>


<span>

Products

</span>


<i class="fa-solid fa-chevron-down ms-auto"></i>


</a>





<ul id="productMenu"

class="collapse sidebar-submenu">


<li>

<a href="<?= BASE_URL ?>/admin/products/index.php">

<i class="fa-solid fa-list"></i>

Products List

</a>

</li>




<li>

<a href="<?= BASE_URL ?>/admin/products/add.php">

<i class="fa-solid fa-plus"></i>

Add Product

</a>

</li>


</ul>


</li>







<!-- ===========================
     CATEGORIES
=========================== -->


<li class="sidebar-item">


<a href="<?= BASE_URL ?>/admin/categories/index.php"

class="sidebar-link">


<i class="fa-solid fa-layer-group"></i>


<span>

Categories

</span>


</a>


</li>

<!-- ===========================
     SUPPLIERS MENU
=========================== -->


<li class="sidebar-item">


<a href="<?= BASE_URL ?>/admin/suppliers/index.php"

class="sidebar-link">


<i class="fa-solid fa-truck"></i>


<span>

Suppliers

</span>


</a>


</li>








<!-- ===========================
     PURCHASES MENU
=========================== -->


<li class="sidebar-item">


<a href="#purchaseMenu"

class="sidebar-link collapsed"

data-bs-toggle="collapse">


<i class="fa-solid fa-cart-flatbed"></i>


<span>

Purchases

</span>


<i class="fa-solid fa-chevron-down ms-auto"></i>


</a>







<ul id="purchaseMenu"

class="collapse sidebar-submenu">



<li>


<a href="<?= BASE_URL ?>/admin/purchases/index.php">


<i class="fa-solid fa-list"></i>


Purchase List


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/purchases/add.php">


<i class="fa-solid fa-plus"></i>


New Purchase


</a>


</li>



</ul>



</li>









<!-- ===========================
     EXPENSES MENU
=========================== -->


<li class="sidebar-item">


<a href="<?= BASE_URL ?>/admin/expenses/index.php"

class="sidebar-link">


<i class="fa-solid fa-money-bill-wave"></i>


<span>

Expenses

</span>


</a>


</li>









<!-- ===========================
     SALES MENU
=========================== -->


<li class="sidebar-item">


<a href="#salesMenu"

class="sidebar-link collapsed"

data-bs-toggle="collapse">


<i class="fa-solid fa-cart-shopping"></i>


<span>

Sales

</span>


<i class="fa-solid fa-chevron-down ms-auto"></i>


</a>







<ul id="salesMenu"

class="collapse sidebar-submenu">



<li>


<a href="<?= BASE_URL ?>/admin/sales/index.php">


<i class="fa-solid fa-list"></i>


Sales List


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/add.php">


<i class="fa-solid fa-plus"></i>


New Sale


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/quotation.php">


<i class="fa-solid fa-file-contract"></i>


Quotations


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/payment.php">


<i class="fa-solid fa-money-bill"></i>


Payments


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/receipt.php">


<i class="fa-solid fa-receipt"></i>


Receipts


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/return.php">


<i class="fa-solid fa-rotate-left"></i>


Returns


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/reports.php">


<i class="fa-solid fa-chart-line"></i>


Reports


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/export_excel.php">


<i class="fa-solid fa-file-excel"></i>


Export Excel


</a>


</li>





<li>


<a href="<?= BASE_URL ?>/admin/sales/export_pdf.php">


<i class="fa-solid fa-file-pdf"></i>


Export PDF


</a>


</li>



</ul>



</li>

<!-- ===========================
     USERS MENU
=========================== -->


<li class="sidebar-item">


<a href="#usersMenu"

class="sidebar-link collapsed"

data-bs-toggle="collapse">


<i class="fa-solid fa-users"></i>


<span>

Users

</span>


<i class="fa-solid fa-chevron-down ms-auto"></i>


</a>







<ul id="usersMenu"

class="collapse sidebar-submenu">



<li>


<a href="<?= BASE_URL ?>/admin/users/index.php">


<i class="fa-solid fa-list"></i>


Users List


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/users/add_user.php">


<i class="fa-solid fa-user-plus"></i>


Add User


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/users/approve_user.php">


<i class="fa-solid fa-user-check"></i>


Approve Users


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/users/notification.php">


<i class="fa-solid fa-bell"></i>


Notifications


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/users/orders.php">


<i class="fa-solid fa-cart-shopping"></i>


Orders


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/users/profile.php">


<i class="fa-solid fa-id-card"></i>


Profile


</a>


</li>







<li>


<a href="<?= BASE_URL ?>/admin/users/quotations.php">


<i class="fa-solid fa-file-contract"></i>


Quotations


</a>


</li>



</ul>



</li>








<!-- ===========================
     SETTINGS
=========================== -->


<li class="sidebar-item">


<a href="<?= BASE_URL ?>/admin/settings/index.php"

class="sidebar-link">


<i class="fa-solid fa-gear"></i>


<span>

Settings

</span>


</a>


</li>








<!-- ===========================
     LOGOUT
=========================== -->


<li class="sidebar-item">


<a href="<?= BASE_URL ?>/logout.php"

class="sidebar-link text-danger"

onclick="return confirm('Are you sure you want to logout?');">


<i class="fa-solid fa-right-from-bracket"></i>


<span>

Logout

</span>


</a>


</li>







</ul>


</aside>