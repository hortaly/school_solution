<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        <!-- app-Header -->
        <div class="app-header header sticky">
            <div class="container-fluid main-container">
                <div class="d-flex">
                    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>

                    <a class="logo-horizontal" href="index.php">
                        <img src="assets/images/brand/logo-white.png" class="header-brand-img desktop-logo" alt="logo">
                        <img src="assets/images/brand/logo-dark.png" class="header-brand-img light-logo1" alt="logo">
                    </a>

                    <div class="d-flex order-lg-2 ms-auto header-right-icons">
                        <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                            aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                        </button>
                        <div class="navbar navbar-collapse responsive-navbar p-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2">

                                    <!-- Theme layout -->
                                    <div class="d-flex">
                                        <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                            <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                            <span class="light-layout"><i class="fe fe-sun"></i></span>
                                        </a>
                                    </div>

                                    <!-- Notifications (empty until you wire real ones) -->
                                    <div class="dropdown d-flex notifications">
                                        <a class="nav-link icon" data-bs-toggle="dropdown"><i class="fe fe-bell"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading border-bottom">
                                                <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">Notifications</h6>
                                            </div>
                                            <div class="notifications-menu">
                                                <p class="text-center text-muted p-4 mb-0">No new notifications</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profile -->
                                    <div class="dropdown d-flex profile-1">
                                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                            <img src="../parents_log/parent_photo/<?php echo $session_photo; ?>" alt="profile-user"
                                                class="avatar profile-user brround cover-image">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading">
                                                <div class="text-center">
                                                    <h5 class="text-dark mb-0 fs-14 fw-semibold"><?php echo $session_fullname; ?></h5>
                                                    <h6 class="text-dark mb-0 fs-10 fw-semibold"><?php echo $session_uin; ?></h6><br>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider m-0"></div>
                                            <a class="dropdown-item" href="parent_profile.php">
                                                <i class="dropdown-icon fe fe-user"></i> My Profile
                                            </a>
                                            <a class="dropdown-item" href="sign_out.php" onclick="return confirm('Are you sure you want to logout?')">
                                                <i class="dropdown-icon fe fe-log-out"></i> Sign out
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /app-Header -->

        <!--APP-SIDEBAR-->
        <div class="sticky">
            <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
            <div class="app-sidebar">
                <div class="side-header">
                    <a class="header-brand1" href="index.php">
                        <img src="assets/images/brand/logo-white.png" class="header-brand-img desktop-logo" alt="logo">
                        <img src="assets/images/brand/icon-white.png" class="header-brand-img toggle-logo" alt="logo">
                        <img src="assets/images/brand/icon-dark.png" class="header-brand-img light-logo" alt="logo">
                        <img src="assets/images/brand/logo-dark.png" class="header-brand-img light-logo1" alt="logo">
                    </a>
                </div>
                <div class="main-sidemenu">
                    <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                        </svg></div>

                    <ul class="side-menu">

                        <li class="sub-category"><h3>Main</h3></li>

                        <li class="slide">
                            <a class="side-menu__item has-link" data-bs-toggle="slide" href="index.php">
                                <i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>

                        <li class="slide">
                            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                                <i class="side-menu__icon fe fe-credit-card"></i><span class="side-menu__label">Fees</span><i class="angle fe fe-chevron-right"></i>
                            </a>
                            <ul class="slide-menu">
                                <li class="side-menu-label1"><a href="javascript:void(0)">Fees</a></li>
                                <li><a href="pay_fees.php" class="slide-item">Pay Fees</a></li>
                                <li><a href="payment_history.php" class="slide-item">Payment History</a></li>
                            </ul>
                        </li>

                        <li class="slide">
                            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                                <i class="side-menu__icon fe fe-book-open"></i><span class="side-menu__label">Results</span><i class="angle fe fe-chevron-right"></i>
                            </a>
                            <ul class="slide-menu">
                                <li class="side-menu-label1"><a href="javascript:void(0)">Results</a></li>
                                <li><a href="view_results.php" class="slide-item">Ward Results</a></li>
                            </ul>
                        </li>

                        <li class="sub-category"><h3>Account</h3></li>

                        <li class="slide">
                            <a class="side-menu__item has-link" data-bs-toggle="slide" href="parent_profile.php">
                                <i class="side-menu__icon fe fe-user"></i><span class="side-menu__label">My Profile</span>
                            </a>
                        </li>

                    </ul>

                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                        </svg></div>
                </div>
            </div>
        </div>
        <!--/APP-SIDEBAR-->