<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM glo_admn WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_REQUEST['staff_uin'])) {
    $sql = "SELECT * FROM staff WHERE staff_uin = '$_REQUEST[staff_uin]'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $staff_uin = $row['staff_uin'];
}
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">

    <!-- TITLE -->
    <title>Sash – Bootstrap 5 Admin & Dashboard Template</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
     <link href="assets/css/style.css" rel="stylesheet">

	<!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php
    include("menu.php")?>


            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Add Class</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Class</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->
                         <form action="" method="post">
                            <?php
                            include("db_conn.php");
               if (isset($_REQUEST['assign_class'])) {

    $staff_uin= trim($_POST['staff_uin']);
    $new_class_uin= trim($_POST['class_uin']);

    mysqli_query($conn, "START TRANSACTION");

        // 1. Find this staff's OLD class (if any)
        $check = mysqli_query($conn, "SELECT class_assigned, staff_name FROM staff WHERE staff_uin = '$staff_uin'");
        $check_row = mysqli_fetch_assoc($check);
        $old_class_uin = $check_row['class_assigned'];
        $assigned_staff_name = $check_row['staff_name'];

        // 2. Clear the staff's old class (if they had one)
        if(!empty($old_class_uin)){
            $sql_clear = "UPDATE class SET staff_name = NULL, staff_uin = NULL WHERE class_uin = '$old_class_uin'";
            $result_clear = mysqli_query($conn, $sql_clear);
        }

        // 3. Find if the NEW class already has a DIFFERENT staff member on it
        $check_class = mysqli_query($conn, "SELECT staff_uin FROM class WHERE class_uin = '$new_class_uin'");
        $check_class_row = mysqli_fetch_assoc($check_class);
        $existing_staff_on_class = $check_class_row['staff_uin'];

        // 4. If that class had a different staff member, clear THEIR class_assigned
        if(!empty($existing_staff_on_class) && $existing_staff_on_class != $staff_uin){
            $sql_clear_old_staff = "UPDATE staff SET class_assigned = NULL WHERE staff_uin = '$existing_staff_on_class'";
            $result_clear_old_staff = mysqli_query($conn, $sql_clear_old_staff);
        }

        // 5. Assign the new class both ways
        $sql1 = "UPDATE staff
        SET class_assigned = '$new_class_uin'
        WHERE staff_uin = '$staff_uin'
        ";
        $sql2 = "UPDATE class
        SET staff_uin = '$staff_uin',
            staff_name = '$assigned_staff_name'
        WHERE class_uin = '$new_class_uin'
        ";

        $result1 = mysqli_query($conn, $sql1);
        $result2 = mysqli_query($conn, $sql2);



        if($result1 && $result2){

            mysqli_query($conn, "COMMIT");

            echo "<script>
            alert('Class assigned successfully');
            window.location.href='all_staff.php';
            </script>";

        }else{

            mysqli_query($conn, "ROLLBACK");
            echo "<script>alert('Database Error')</script>";

        }

    }

    // Work out the readable class name for display
    $current_class_name = 'None';
    if (isset($_REQUEST['staff_uin']) && !isset($_REQUEST['assign_class']) && !empty($row['class_assigned'])) {
        $class_lookup = mysqli_query($conn, "SELECT class FROM class WHERE class_uin = '{$row['class_assigned']}'");
        $class_lookup_row = mysqli_fetch_assoc($class_lookup);
        if ($class_lookup_row) {
            $current_class_name = $class_lookup_row['class'];
        }
    }

                            ?>
                             <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Add New Class</div>
                                    </div>
                                    <div class="card-body">
                            
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Staff Name :</label>
                                            <div class="col-md-9">
                                                 <input type="text" class="form-control" id="class "
                               name="staff_name" readonly value="<?php echo $row['staff_name']?>">
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Current Class :</label>
                                            <div class="col-md-9">
                                                 <input type="text" class="form-control" id="class "
                               name="class_assigned" readonly value="<?php echo $current_class_name; ?>">
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Staff UIN :</label>
                                            <div class="col-md-9">
                                                 <input type="text" class="form-control" id="class "
                               name="staff_uin" readonly value="<?php echo $row['staff_uin']?>">
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Select Class:</label>
                                            <div class="col-md-9">
                                             <select name="class_uin" class="form-control" required>
                                        <option value="">-- Select Class --</option>
                                        <?php
                                        $classes = mysqli_query($conn, "SELECT class_uin, class, staff_name FROM class");
                                        while ($class_row = mysqli_fetch_assoc($classes)):
                                        ?>
                                            <option value="<?php echo $class_row['class_uin']; ?>">
                                                <?php echo $class_row['class']; ?>
                                                <?php echo $class_row['staff_name'] ? ' (occupied)' : ' (unassigned)'; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                     
                                            </div>
                                        </div>
                                     
                                       
                                      

                                     

                                   
                                    </div>
                                    <div class="card-footer">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-9">
                                                <button name="assign_class" class="btn btn-primary" 
                                                onclick="return confirm('Are you sure you want to assign this class to this person?')
                                                ">Assign Class</button>
                                       
                                            </div>
                                        </div>
                                        <!--End Row-->
                         </form>

                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /ROW-1 CLOSED -->
                    </div>
                    <!-- CONTAINER CLOSED -->
                </div>
            </div>
            <!--app-content closed-->
        </div>


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

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- INPUT MASK JS-->
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL WYSIWYG Editor JS -->
    <script src="assets/plugins/wysiwyag/jquery.richtext.js "></script>
    <script src="assets/plugins/wysiwyag/wysiwyag.js "></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="assets/plugins/fancyuploder/jquery.ui.widget.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.fileupload.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.iframe-transport.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.fancy-fileupload.js"></script>
    <script src="assets/plugins/fancyuploder/fancy-uploader.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

</body>

</html>