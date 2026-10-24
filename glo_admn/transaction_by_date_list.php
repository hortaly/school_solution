<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM glo_admn WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>
<?php
include_once("control.php");
// Now you can use anywhere:
$school_name = $SCHOOL['school_name'];
$short_name = $SCHOOL['short_name'];
$current_session = $SCHOOL['current_session'];
$admission_session = $SCHOOL['current_session'];
$current_term = $SCHOOL['current_term'];
$admission_term = $SCHOOL['current_term'];
$student_id = studentUIN($conn);
$staff_id = staffUIN($conn);
$parent_id = parentUIN($conn);
$transaction_id = transactionUIN($conn);
$fee_uin = feeUIN($conn);
$payment_uin = paymentUIN($conn);
$receipt_uin = receiptUIN($conn);
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $school_name;?> - Transaction Report</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
    <style>
        :root {
            --premium-radius: 16px;
            --premium-shadow: 0 8px 30px rgba(0, 0, 0, .06);
        }

        .premium-card {
            background: #fff;
            border-radius: var(--premium-radius) !important;
            border: 1px solid rgba(0, 0, 0, .06) !important;
            box-shadow: var(--premium-shadow);
            overflow: hidden;
        }

        .dark-mode .premium-card {
            background: var(--dark-body, #1e1e2d) !important;
            border: 1px solid rgba(255, 255, 255, .08) !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .3) !important;
        }

        .page-header-premium {
            background: linear-gradient(135deg, rgba(98, 89, 202, .10) 0%, rgba(255, 255, 255, 0) 100%);
            border: 1px solid rgba(98, 89, 202, .14);
            border-radius: 16px;
            padding: 18px 22px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .dark-mode .page-header-premium {
            background: linear-gradient(135deg, rgba(98, 89, 202, .15) 0%, rgba(30, 30, 45, 0) 100%) !important;
            border-color: rgba(255, 255, 255, .08) !important;
        }

        .greet-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #6259ca, #8a7cf5);
            color: #fff;
            font-size: 22px;
        }

        .card-header-premium {
            padding: 20px 26px;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .card-header-premium {
            border-bottom-color: rgba(255, 255, 255, .08) !important;
        }

        .dark-mode .table {
            color: rgba(255, 255, 255, .8) !important;
        }

        .dark-mode .table thead th {
            background: rgba(255, 255, 255, .03) !important;
            color: rgba(255, 255, 255, .7) !important;
            border-color: rgba(255, 255, 255, .08) !important;
        }

        .dark-mode .table td {
            border-color: rgba(255, 255, 255, .06) !important;
        }

        .badge-active {
            background: rgba(34, 192, 60, .12);
            color: #22c03c;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-none {
            background: rgba(255, 56, 56, .10);
            color: #ff5a5a;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-class {
            background: rgba(98, 89, 202, .12);
            color:green;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
        }

        .btn-premium {
            border-radius: 10px;
            padding: 7px 16px;
            font-weight: 600;
        }

        .dark-mode .dropdown-menu {
            background: #1e1e2d !important;
            border-color: rgba(255, 255, 255, .08) !important;
        }

        .dark-mode .dropdown-item {
            color: rgba(255, 255, 255, .8) !important;
        }

        .dark-mode .dropdown-item:hover {
            background: rgba(255, 255, 255, .06) !important;
        }
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header-premium">
                    <div class="greet-icon"><i class="fe fe-users"></i></div>
                    <div>

                    <?php
                    if ($_POST) {
                         $date_from = $_POST['date_from'];
                         $date_to = $_POST['date_to'];
                    }
                    ?>
                        <h1 class="page-title mb-0" style="font-weight:800; font-size:18px;">Transaction Report

                        </h1><small class="text-muted">All  transactions from <?php echo $date_from;?> to <?php echo $date_to;?> in <?php echo $school_name; ?></small>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header-premium">
                                <h3 class="card-title mb-0 fw-bold">All Transactions Record between <?php echo $date_from;?> to <?php echo $date_to;?> in <?php echo $school_name; ?></h3>
                                <span class="badge-class"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM students")); ?> Students</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                        <?php
                                        $sql = mysqli_query($conn, "
                                          SELECT p.*, s.student_name, s.student_uin 
                                          FROM payments p 
                                          JOIN students s ON p.student_uin = s.student_uin 
                                          WHERE DATE(p.created_at) BETWEEN '$date_from' AND '$date_to'
                                          ORDER BY p.created_at DESC
                                        ");
                                        ?>
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">Student Name</th>
                                                <th class="border-bottom-0">Student Uin</th>
                                                <th class="border-bottom-0">Payment Amount</th>
                                    
                                                <th class="border-bottom-0">Payment ID</th>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($sql)) { ?>
                                                <tr>
                                                    <td><b><?php echo $row['student_name'] ?></b></td>
                                                    <td><?php echo $row['student_uin'] ?></td>
                                                    <td>&#8358;<?php echo $row['amount_paid'] ?></td>
                                               
                                                    <td><?php echo $row['payment_id'] ?></td>
                                                    <td>
                                        <?php echo date('d M Y', strtotime($row['created_at'])) ?></td>
                                                    </td>
                                                   
                                                  <?php
                                                  if ($row['status'] == 'success') {
                                                        echo '<td><span class="badge badge-active">Success</span></td>';
                                                    } else {
                                                        echo '<td><span class="badge badge-none">Failed</span></td>';
                                                    }
                                                  ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


              


                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
     <footer class="footer">
          
            <?php include("footer.php");?>
         
            </footer>

    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <script src="assets/js/table-data.js"></script>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatable/js/jszip.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
    <script src="assets/js/table-data.js"></script>
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