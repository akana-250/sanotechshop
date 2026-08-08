<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/config.php";


$currentPage = basename($_SERVER['PHP_SELF']);
$currentFolder = basename(dirname($_SERVER['PHP_SELF']));


function isActive($page = '', $folder = '')
{
    global $currentPage, $currentFolder;


    if ($page !== '' && $currentPage === $page) {
        return 'active';
    }


    if ($folder !== '' && $currentFolder === $folder) {
        return 'active';
    }


    return '';
}

?>


<aside class="user-sidebar">


    <div class="sidebar-header">


        <a href="<?= BASE_URL ?>/user/dashboard.php" class="sidebar-brand">


            <img
                src="<?= BASE_URL ?>/assets/img/logo.png"
                alt="<?= SITE_NAME ?>"
                class="sidebar-logo"
                onerror="this.style.display='none';"
            >


            <div class="sidebar-brand-text">

                <h5><?= SITE_NAME ?></h5>

                <small>User Panel</small>

            </div>


        </a>


    </div>




    <nav class="sidebar-menu">


        <ul>


            <!-- Dashboard -->

            <li>

                <a href="<?= BASE_URL ?>/user/dashboard.php"
                   class="<?= isActive('dashboard.php'); ?>">


                    <i class="bi bi-speedometer2"></i>

                    <span>Dashboard</span>


                </a>

            </li>



            <!-- Profile -->

            <li>

                <a href="<?= BASE_URL ?>/user/profile.php"
                   class="<?= isActive('profile.php'); ?>">


                    <i class="bi bi-person-circle"></i>

                    <span>My Profile</span>


                </a>

            </li>




            <hr class="text-secondary">





            <!-- Products -->

            <li>

                <a href="<?= BASE_URL ?>/user/products/index.php"
                   class="<?= isActive('', 'products'); ?>">


                    <i class="bi bi-box-seam"></i>

                    <span>Products</span>


                </a>

            </li>




            <!-- Customers -->

            <li>

                <a href="<?= BASE_URL ?>/user/customers/index.php"
                   class="<?= isActive('', 'customers'); ?>">


                    <i class="bi bi-people"></i>

                    <span>Customers</span>


                </a>

            </li>




            <!-- Sales -->

            <li>

                <a href="<?= BASE_URL ?>/user/sales/index.php"
                   class="<?= isActive('', 'sales'); ?>">


                    <i class="bi bi-cart-check"></i>

                    <span>Sales</span>


                </a>

            </li>




            <!-- Purchases -->

            <li>

                <a href="<?= BASE_URL ?>/user/purchases/index.php"
                   class="<?= isActive('', 'purchases'); ?>">


                    <i class="bi bi-bag-check"></i>

                    <span>Purchases</span>


                </a>

            </li>




            <!-- Quotations -->

            <li>

                <a href="<?= BASE_URL ?>/user/quotations/index.php"
                   class="<?= isActive('', 'quotations'); ?>">


                    <i class="bi bi-file-earmark-text"></i>

                    <span>Quotations</span>


                </a>

            </li>




            <!-- Notifications -->

            <li>

                <a href="<?= BASE_URL ?>/user/notifications/index.php"
                   class="<?= isActive('', 'notifications'); ?>">


                    <i class="bi bi-bell"></i>

                    <span>Notifications</span>


                </a>

            </li>




            <!-- Settings -->

            <li>

                <a href="<?= BASE_URL ?>/user/settings/index.php"
                   class="<?= isActive('', 'settings'); ?>">


                    <i class="bi bi-gear"></i>

                    <span>Settings</span>


                </a>

            </li>




            <!-- Activity -->

            <li>

                <a href="<?= BASE_URL ?>/user/activity.php"
                   class="<?= isActive('activity.php'); ?>">


                    <i class="bi bi-clock-history"></i>

                    <span>Activity Log</span>


                </a>

            </li>





            <!-- Logout -->

            <li class="mt-4">


                <a href="<?= BASE_URL ?>/auth/logout.php"
                   class="text-danger"
                   onclick="return confirm('Are you sure you want to logout?');">


                    <i class="bi bi-box-arrow-right"></i>

                    <span>Logout</span>


                </a>


            </li>



        </ul>


    </nav>


</aside>