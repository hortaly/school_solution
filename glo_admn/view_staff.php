<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM glo_admn WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>  
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <title>Sash – Bootstrap 5 Admin & Dashboard Template</title>
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">

<?php include("menu.php");?>

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Profile</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Profile</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

           <?php
include("db_conn.php");
if(isset($_REQUEST['staff_uin'])){
    $staff_uin = $_REQUEST['staff_uin'];
    $sql = "SELECT staff.*, class.class AS class_name
            FROM staff
            LEFT JOIN class ON staff.class_assigned = class.class_uin
            WHERE staff.staff_uin = '$staff_uin'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
}
?>

            <!-- ROW-1 OPEN -->
            <div class="row" id="user-profile">
                <div class="col-lg-12">

                    <!-- Profile Cover Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class="wideget-user mb-2">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">
                                            <div class="panel profile-cover">
                                                <div class="profile-cover__action bg-img"></div>
                                                <div class="profile-cover__img">
                                                    <style>
                                                        .profile-img-1 img {
                                                            width: 120px;
                                                            height: 120px;
                                                            border-radius: 50%;
                                                            object-fit: cover;
                                                            object-position: center top;
                                                        }
                                                    </style>
                                                    <div class="profile-img-1">
                                                        <img src="../staff_log/staff_photo/<?php echo $row['staff_photo']; ?>" alt="img">
                                                    </div>
                                                    <div class="profile-img-content text-dark text-start">
                                                        <div class="text-dark">
                                                            <h3 class="h3 mb-2"><?php echo $row['staff_name']; ?></h3>
                                                            <h5 class="text-muted">Staff</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wards Count + About -->
                    <div class="row mt-5 align-items-stretch">

                        <div class="col-xl-6 d-flex">
                            <div class="card w-100">
                                <!-- <div class="card-header">
                                    <div class="card-title">Student</div>
                                </div> -->
                                <div class="card-body d-flex align-items-center">
                                
                                    <div class="media d-flex align-items-center">
                                        <div class="media-icon bg-danger bradius text-white me-3">
                                            <span><i class="fe fe-users fs-20"></i></span>
                                        </div>
                                        <div class="media-body">
                                            <span class="text-muted">Class Assigned to</span>
                                            <div class="fw-semibold fs-25">
                                                <?php echo $row['class_name'] ? $row['class_name'] : 'None'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="col-xl-6 d-flex">
                            <div class="card w-100">
                                <div class="card-header">
                                    <div class="card-title">About</div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-4 text-center text-primary">
                                            <span><i class="fe fe-phone fs-20"></i></span>
                                        </div>
                                        <div>
                                            <strong><?php echo $row['staff_number']; ?></strong>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-4 text-center text-primary">
                                            <span><i class="fe fe-mail fs-20"></i></span>
                                        </div>
                                        <div>
                                            <strong><?php echo $row['staff_email']; ?></strong>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-4 text-center text-primary">
                                            <span><i class="fe fe-mail fs-20"></i></span>
                                        </div>
                                        <div>
                                            <strong><?php echo $row['staff_dob']; ?></strong>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-4 text-center text-primary">
                                            <span><i class="fe fe-mail fs-20"></i></span>
                                        </div>
                                        <div>
                                            <strong><?php echo $row['staff_uin']; ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Wards Count + About END -->
                

                </div>
            </div>
            <!-- ROW-1 CLOSED -->

        </div>
        <!-- CONTAINER CLOSED -->

    </div>
</div>
<!--app-content closed-->

        <!-- Sidebar-right -->
        <div class="sidebar sidebar-right sidebar-animate">
            <div class="panel panel-primary card mb-0 shadow-none border-0">
                <div class="tab-menu-heading border-0 d-flex p-3">
                    <div class="card-title mb-0"><i class="fe fe-bell me-2"></i><span class=" pulse"></span>Notifications</div>
                    <div class="card-options ms-auto">
                        <a href="javascript:void(0);" class="sidebar-icon text-end float-end me-3 mb-1"
                            data-bs-toggle="sidebar-right" data-target=".sidebar-right"><i class="fe fe-x text-white"></i></a>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
                    <div class="tabs-menu border-bottom">
                        <ul class="nav panel-tabs">
                            <li class=""><a href="#sidebar-side1" class="active" data-bs-toggle="tab"><i class="fe fe-settings me-1"></i>Feeds</a></li>
                            <li><a href="#sidebar-side2" data-bs-toggle="tab"><i class="fe fe-message-circle me-1"></i> Chat</a></li>
                            <li><a href="#sidebar-side3" data-bs-toggle="tab"><i class="fe fe-anchor me-1"></i>Timeline</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="sidebar-side1">
                            <div class="p-3 fw-semibold ps-5">Feeds</div>
                            <div class="card-body pt-2">
                                <div class="browser-stats">
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span class="feeds avatar-circle brround bg-primary-transparent"><i class="fe fe-user text-primary"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">New user registered</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="sidebar-side2">
                        </div>
                        <div class="tab-pane" id="sidebar-side3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/Sidebar-right-->

        <!-- FOOTER -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!-- FOOTER CLOSED -->

    </div>

    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>
    <script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/js/themeColors.js"></script>
    <script src="assets/js/sticky.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/custom-swicher.js"></script>
    <script src="assets/switcher/js/switcher.js"></script>

</body>
</html>