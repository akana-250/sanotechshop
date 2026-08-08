<?php
session_start();

$page_title = "SANO TECH SHOP ERP";
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($page_title) ?></title>

    <meta name="description"
        content="SANO TECH SHOP ERP - Inventory, POS, Sales, Customers, Reports and Business Management System">

    <meta name="keywords"
        content="ERP, POS, Inventory, Sales, Rwanda, SANO TECH SHOP">

    <link rel="icon" href="assets/img/logo.png">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css"
        rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<!-- ===================================
TOP BAR
=================================== -->

<div class="top-bar">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6 text-center text-md-start">

                <span>

                    <i class="fa-solid fa-phone"></i>

                    +250 780 985 577

                </span>

                <span class="ms-3">

                    <i class="fa-solid fa-envelope"></i>

                    sanotech250@gmail.com

                </span>

            </div>

            <div class="col-md-6 text-center text-md-end">

                <span>

                    <i class="fa-solid fa-location-dot"></i>

                    Kigali, Rwanda

                </span>

            </div>

        </div>

    </div>

</div>

<!-- ===================================
NAVBAR
=================================== -->

<nav class="navbar navbar-expand-lg fixed-top">

    <div class="container">

        <a class="navbar-brand d-flex align-items-center"
            href="index.php">

            <img
                src="assets/img/logo.png"
                alt="Logo"
                width="55"
                height="55"
                class="me-2 rounded-circle">

            <div>

                <strong>SANO TECH SHOP</strong>

                <small class="d-block">
                    ERP Management System
                </small>

            </div>

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

            <i class="fa fa-bars text-white"></i>

        </button>

        <div class="collapse navbar-collapse"
            id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a class="nav-link active"
                        href="#home">

                        Home

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                        href="#modules">

                        Modules

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                        href="#services">

                        Services

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                        href="#contact">

                        Contact

                    </a>

                </li>

                <li class="nav-item ms-lg-3">

                    <a
                        href="auth/login.php"
                        class="btn btn-admin">

                        <i class="fa-solid fa-right-to-bracket"></i>

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- ===================================
HERO SECTION
=================================== -->

<section class="hero"
    id="home">

    <div class="container">

        <div class="row align-items-center min-vh-100">

            <div class="col-lg-6"
                data-aos="fade-right">

                <span class="hero-badge">

                    <i class="fa-solid fa-star"></i>

                    Professional ERP Software

                </span>

                <h1>

                    Manage Your Entire Business

                    <span>

                        With SANO TECH SHOP ERP

                    </span>

                </h1>

                <p>

                    Powerful ERP software designed for modern businesses.
                    Manage inventory, POS, customers, suppliers,
                    reports and sales from one professional platform.

                </p>

                <div class="hero-buttons">

                    <a
                        href="auth/login.php"
                        class="btn btn-primary btn-lg">

                        <i class="fa-solid fa-right-to-bracket"></i>

                        Get Started

                    </a>

                    <a
                        href="#modules"
                        class="btn btn-outline-light btn-lg">

                        Explore Features

                    </a>

                </div>

                <div class="hero-cards mt-5">

                    <div class="mini-card">

                        <i class="fa-solid fa-box"></i>

                        <div>

                            <h5>Inventory</h5>

                            <p>Smart Stock Control</p>

                        </div>

                    </div>

                    <div class="mini-card">

                        <i class="fa-solid fa-cart-shopping"></i>

                        <div>

                            <h5>POS</h5>

                            <p>Fast Sales System</p>

                        </div>

                    </div>

                    <div class="mini-card">

                        <i class="fa-solid fa-chart-line"></i>

                        <div>

                            <h5>Reports</h5>

                            <p>Business Analytics</p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-6 text-center"
                data-aos="fade-left">

                <div class="hero-device">

                    <img
                        src="assets/img/laptop.png"
                        class="laptop"
                        alt="Laptop">

                    <img
                        src="assets/img/dashboard-screen.png"
                        class="dashboard-screen"
                        alt="Dashboard">

                    <img
                        src="assets/img/phone.png"
                        class="phone"
                        alt="Phone">

                    <img
                        src="assets/img/pos-machine.png"
                        class="pos-machine"
                        alt="POS">

                    <img
                        src="assets/img/receipt-printer.png"
                        class="printer"
                        alt="Printer">

                </div>

            </div>

        </div>

    </div>

</section><!-- ==================================================
                    ERP MODULES
================================================== -->

<section class="modules py-5" id="modules">

    <div class="container">

        <div class="section-title text-center mb-5" data-aos="fade-up">

            <span class="badge-title">
                OUR ERP MODULES
            </span>

            <h2>
                Everything You Need To Manage Your Business
            </h2>

            <p>
                Powerful and easy-to-use tools designed to simplify your daily operations.
            </p>

        </div>

        <div class="row g-4">

            <!-- Product -->

            <div class="col-lg-4 col-md-6">

                <div class="module-card h-100" data-aos="fade-up">

                    <div class="icon">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>

                    <h4>Product Management</h4>

                    <p>
                        Manage products, prices, barcodes,
                        categories and stock quantities.
                    </p>

                    <a href="#" class="read-more">
                        Learn More
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

            <!-- Sales -->

            <div class="col-lg-4 col-md-6">

                <div class="module-card h-100"
                     data-aos="fade-up"
                     data-aos-delay="100">

                    <div class="icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>

                    <h4>Sales & POS</h4>

                    <p>
                        Fast Point of Sale,
                        receipts, invoices,
                        payment processing and history.
                    </p>

                    <a href="#" class="read-more">
                        Learn More
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

            <!-- Customers -->

            <div class="col-lg-4 col-md-6">

                <div class="module-card h-100"
                     data-aos="fade-up"
                     data-aos-delay="200">

                    <div class="icon">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <h4>Customer Management</h4>

                    <p>
                        Save customer information,
                        purchase history,
                        balances and payments.
                    </p>

                    <a href="#" class="read-more">
                        Learn More
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

            <!-- Inventory -->

            <div class="col-lg-4 col-md-6">

                <div class="module-card h-100"
                     data-aos="fade-up">

                    <div class="icon">
                        <i class="fa-solid fa-warehouse"></i>
                    </div>

                    <h4>Inventory Control</h4>

                    <p>
                        Monitor stock movement,
                        receive low stock alerts
                        and inventory reports.
                    </p>

                    <a href="#" class="read-more">
                        Learn More
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

            <!-- Suppliers -->

            <div class="col-lg-4 col-md-6">

                <div class="module-card h-100"
                     data-aos="fade-up"
                     data-aos-delay="100">

                    <div class="icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>

                    <h4>Supplier Management</h4>

                    <p>
                        Manage supplier information,
                        purchase orders,
                        deliveries and payments.
                    </p>

                    <a href="#" class="read-more">
                        Learn More
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

            <!-- Reports -->

            <div class="col-lg-4 col-md-6">

                <div class="module-card h-100"
                     data-aos="fade-up"
                     data-aos-delay="200">

                    <div class="icon">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>

                    <h4>Reports & Analytics</h4>

                    <p>
                        Generate sales reports,
                        profit analysis,
                        stock reports and dashboards.
                    </p>

                    <a href="#" class="read-more">
                        Learn More
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==================================================
                    SERVICES
================================================== -->

<section class="services py-5" id="services">

    <div class="container">

        <div class="section-title text-center mb-5" data-aos="fade-up">

            <span class="badge-title">

                OUR SERVICES

            </span>

            <h2>

                Technology Solutions For Every Business

            </h2>

            <p>

                We provide reliable technology services
                to help businesses grow faster.

            </p>

        </div>

        <div class="row g-4">

            <!-- ERP -->

            <div class="col-lg-3 col-md-6">

                <div class="service-card h-100"
                     data-aos="zoom-in">

                    <div class="service-icon">

                        <i class="fa-solid fa-desktop"></i>

                    </div>

                    <h5>

                        ERP Software

                    </h5>

                    <p>

                        Complete business management
                        software for shops,
                        companies and organizations.

                    </p>

                </div>

            </div>

            <!-- CCTV -->

            <div class="col-lg-3 col-md-6">

                <div class="service-card h-100"
                     data-aos="zoom-in"
                     data-aos-delay="100">

                    <div class="service-icon">

                        <i class="fa-solid fa-camera"></i>

                    </div>

                    <h5>

                        CCTV Installation

                    </h5>

                    <p>

                        Professional security camera
                        installation,
                        maintenance and support.

                    </p>

                </div>

            </div>

            <!-- Networking -->

            <div class="col-lg-3 col-md-6">

                <div class="service-card h-100"
                     data-aos="zoom-in"
                     data-aos-delay="200">

                    <div class="service-icon">

                        <i class="fa-solid fa-network-wired"></i>

                    </div>

                    <h5>

                        Networking

                    </h5>

                    <p>

                        LAN, Wi-Fi,
                        routers, switches
                        and server configuration.

                    </p>

                </div>

            </div>

            <!-- Printing -->

            <div class="col-lg-3 col-md-6">

                <div class="service-card h-100"
                     data-aos="zoom-in"
                     data-aos-delay="300">

                    <div class="service-icon">

                        <i class="fa-solid fa-print"></i>

                    </div>

                    <h5>

                        Printing Services

                    </h5>

                    <p>

                        Graphic design,
                        printing,
                        branding and photocopy services.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section><!-- ==================================================
                WHY CHOOSE US
================================================== -->

<section class="why-us py-5">

    <div class="container">

        <div class="section-title text-center mb-5" data-aos="fade-up">

            <span class="badge-title">
                WHY CHOOSE US
            </span>

            <h2>
                Trusted Technology Partner
            </h2>

            <p>
                We build reliable technology solutions that help businesses grow.
            </p>

        </div>

        <div class="row align-items-center">

            <div class="col-lg-6">

                <div class="why-box">

                    <div class="why-item" data-aos="fade-right">

                        <i class="fa-solid fa-shield-halved"></i>

                        <div>

                            <h5>Secure Platform</h5>

                            <p>
                                Modern security keeps your business information protected.
                            </p>

                        </div>

                    </div>

                    <div class="why-item" data-aos="fade-right" data-aos-delay="100">

                        <i class="fa-solid fa-bolt"></i>

                        <div>

                            <h5>Fast Performance</h5>

                            <p>
                                Optimized software delivers smooth and reliable performance.
                            </p>

                        </div>

                    </div>

                    <div class="why-item" data-aos="fade-right" data-aos-delay="200">

                        <i class="fa-solid fa-headset"></i>

                        <div>

                            <h5>Professional Support</h5>

                            <p>
                                Our support team is ready whenever you need assistance.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="stats-box">

                    <div class="stat" data-aos="zoom-in">

                        <h2>500+</h2>

                        <p>Products Managed</p>

                    </div>

                    <div class="stat" data-aos="zoom-in" data-aos-delay="100">

                        <h2>100+</h2>

                        <p>Happy Customers</p>

                    </div>

                    <div class="stat" data-aos="zoom-in" data-aos-delay="200">

                        <h2>24/7</h2>

                        <p>Support Available</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==================================================
                CONTACT
================================================== -->

<section class="contact py-5" id="contact">

    <div class="container">

        <div class="section-title text-center mb-5" data-aos="fade-up">

            <span class="badge-title">
                CONTACT US
            </span>

            <h2>
                Let's Build Your Business Together
            </h2>

            <p>
                Contact SANO TECH SHOP for ERP, CCTV, Networking and IT Solutions.
            </p>

        </div>

        <div class="contact-card" data-aos="zoom-in">

            <h4>
                Get In Touch
            </h4>

            <p>

                <i class="fa-solid fa-phone"></i>

                +250 780 985 577

            </p>

            <p>

                <i class="fa-solid fa-envelope"></i>

                sanotech250@gmail.com

            </p>

            <p>

                <i class="fa-solid fa-location-dot"></i>

                Kigali, Rwanda

            </p>

            <a href="https://wa.me/250780985577"

               target="_blank"

               class="btn btn-success btn-lg">

                <i class="fab fa-whatsapp"></i>

                Chat on WhatsApp

            </a>

        </div>

    </div>

</section>

<!-- ==================================================
                CALL TO ACTION
================================================== -->

<section class="cta py-5">

    <div class="container text-center">

        <h2>

            Ready To Transform Your Business?

        </h2>

        <p class="mb-4">

            Start using SANO TECH SHOP ERP today and simplify your business management.

        </p>

        <a href="auth/login.php"

           class="btn btn-light btn-lg">

            <i class="fa-solid fa-right-to-bracket"></i>

            Access ERP System

        </a>

    </div>

</section>

<!-- ==================================================
                FOOTER
================================================== -->

<footer class="footer">

    <div class="container">

        <div class="row">

            <div class="col-lg-4 mb-4">

                <h5>

                    SANO TECH SHOP

                </h5>

                <p>

                    Professional ERP Software, CCTV Installation,
                    Networking and IT Solutions.

                </p>

            </div>

            <div class="col-lg-4 mb-4">

                <h5>

                    Quick Links

                </h5>

                <ul class="list-unstyled">

                    <li><a href="#home">Home</a></li>

                    <li><a href="#modules">Modules</a></li>

                    <li><a href="#services">Services</a></li>

                    <li><a href="#contact">Contact</a></li>

                </ul>

            </div>

            <div class="col-lg-4 mb-4">

                <h5>

                    Follow Us

                </h5>

                <div class="social-links">

                    <a href="#"><i class="fab fa-facebook-f"></i></a>

                    <a href="#"><i class="fab fa-instagram"></i></a>

                    <a href="#"><i class="fab fa-linkedin"></i></a>

                    <a href="#"><i class="fab fa-whatsapp"></i></a>

                </div>

            </div>

        </div>

        <hr>

        <div class="text-center">

            <p class="mb-0">

                © <?= date('Y'); ?>

                SANO TECH SHOP ERP.

                All Rights Reserved.

            </p>

        </div>

    </div>

</footer>

<!-- ==================================================
                JAVASCRIPT
================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>

AOS.init({

    duration:1000,
    once:true,
    offset:100

});

</script>

<script src="assets/js/script.js"></script>

</body>
</html>