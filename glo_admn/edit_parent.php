<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM glo_admn WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>
  <?php
  if (isset($_REQUEST['parent_uin'])) {
    $sql = "SELECT * FROM parents WHERE parent_uin = '$_REQUEST[parent_uin]'";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_array($result);

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
                            <h1 class="page-title">Edit Parent Details</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Parent</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->
                         <form action="" method="post" enctype="multipart/form-data">
                          
                             <?php 
include("db_conn.php");
error_reporting(E_ALL);



if (isset($_REQUEST['edit_parent'])) {

    $parent_fullname = trim($_POST['parent_name']);
    $parent_uin = trim($_POST['parent_uin']);
    $parent_email = trim($_POST['parent_email']);
    $parent_number = trim($_POST['parent_number']);
   $parent_surname = strtoupper(trim($_POST['parent_surname']));

   
        $sql1 = "UPDATE parents
        SET parent_name = '$parent_fullname',
            parent_email = '$parent_email',
            parent_number = '$parent_number',
            `password` = PASSWORD('$parent_surname'),
            parent_uin = '$parent_uin',
            parent_surname = '$parent_surname',
           `status`= 'active' WHERE parent_uin = '$_REQUEST[parent_uin]'
        ";
        $sql2 = "UPDATE students 
        SET parent_email = '$parent_email',
            parent_number = '$parent_number',
            parent_name = '$parent_fullname'
            WHERE parent_uin = '$_REQUEST[parent_uin]'
        ";

            $result1 = mysqli_query($conn, $sql1);
            $result2 = mysqli_query($conn, $sql2);

        

            if($result1 && $result2){

                echo "<script>
                alert('Success');
                window.location.href='all_parent.php';
                </script>";

            }else{

                echo "<script>alert('Database Error')</script>";

            }

        }


?>
                             <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Edit Parent Details</div>
                                    </div>
                                
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Parent Uin:</label>
                                            <div class="col-md-9">
                                                 <input type="text" class="form-control" id="class"
                               name="parent_uin" required value="<?php echo $rows['parent_uin'];?>" readonly>
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4"   >
                                            <label class="col-md-3 form-label">Parent Surname:</label>
                                            <div class="col-md-9">
                                                 <input type="text" class="form-control" id="class"
                               name="parent_surname" value="<?php echo $rows['parent_surname'];?>" >
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Parent Name:</label>
                                            <div class="col-md-9">
                                                 <input type="text" class="form-control" id="class"
                               name="parent_name" value="<?php echo $rows['parent_name'];?>">
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Parent Email:</label>
                                            <div class="col-md-9">
                                                 <input type="email" class="form-control" id="class"
                               name="parent_email" value="<?php echo $rows['parent_email'];?>">
                     
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Parent Contact:</label>
                                            <div class="col-md-9">
                                                 <input type="number" class="form-control" id="class"
                               name="parent_number" value="<?php echo $rows['parent_number'];?>">
                     
                                            </div>
                                        </div>
                                   
                                     
                                       
                                      

                                     

                                   
                                    </div>
                                    <div class="card-footer">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-9">
                                                <button name="edit_parent" class="btn btn-primary" 
                                                onclick="return confirm('Are you sure you want to edit this parent details?')
                                                ">Edit Record</button>
                                       
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