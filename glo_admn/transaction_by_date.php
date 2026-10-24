<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM glo_admn WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);

// Redirect to class_debtors_list.php with class_uin
if (isset($_POST['class_uin']) && !empty($_POST['class_uin'])) {
    $class_uin = trim($_POST['class_uin']);
    header("Location: class_debtors_list.php?class_uin=" . urlencode($class_uin));
    exit();
}
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
    <title><?php echo $school_name;?> || Filter Transactions</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
  <style>
    :root { --premium-radius: 16px; --premium-shadow: 0 8px 30px rgba(0, 0, 0, .06); }
    .premium-card { background: #fff; border: 1px solid rgba(0, 0, 0, .06); border-radius: var(--premium-radius) !important; overflow: hidden; transition: all .3s ease; box-shadow: var(--premium-shadow); }
    .dark-mode .premium-card { background: var(--dark-body, #1e1e2d) !important; border: 1px solid rgba(255, 255, 255, .06) !important; box-shadow: 0 8px 30px rgba(0, 0, 0, .3) !important; }
    .premium-card-header { padding: 22px 28px !important; border-bottom: 1px solid rgba(0, 0, 0, .06); }
    .dark-mode .premium-card-header { border-bottom-color: rgba(255, 255, 255, .06) !important; }
    .form-control-premium { height: 48px !important; border-radius: 12px !important; font-weight: 500; border-width: 1.5px !important; }
    .form-control-premium:focus { border-color: #6259ca !important; box-shadow: 0 0 0 4px rgba(98, 89, 202, .12) !important; }
    .btn-premium { border-radius: 12px; padding: 11px 22px; font-weight: 600; box-shadow: 0 6px 18px rgba(98, 89, 202, .25); }
  </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php") ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <h1 class="page-title">Filter Transactions By Date</h1>
                    <div><ol class="breadcrumb"><li class="breadcrumb-item"><a href="javascript:void(0)">Finance</a></li><li class="breadcrumb-item active">Filter Transactions</li></ol></div>
                </div>

                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card premium-card">
                            <div class="card-header premium-card-header">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i> 
                                Select date to filter Transactions</h3>
                            </div>
                            <div class="card-body p-4">
                                <form method="post" action="transaction_by_date_list.php">
                                    <?php
                                    if (isset($_POST['filter'])) {
                                        $date_from = $_POST['date_from'];
                                        $date_to = $_POST['date_to'];

                                        // Redirect to transaction_by_date_list.php with date_from and date_to
                                        header("Location: transaction_by_date_list.php?date_from=" .
                                         urlencode($date_from) . "&date_to=" . urlencode($date_to));
                                        exit();
                                    }
                                    ?>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Date From <span class="text-danger">*</span></label>
                                        <input type="date" name="date_from" class="form-control form-control-premium" required>
                                    </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Date To <span class="text-danger">*</span></label>
                                            <input type="date" name="date_to" class="form-control form-control-premium" required>
                                        </div>
                                    <div class="d-grid">
                                        <button type="submit" name="filter" class="btn btn-primary btn-premium"><i class="fe fe-arrow-right me-1">

                                        </i> Filter Transactions</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <footer class="footer">
            <?php include("footer.php");?>
     </footer>
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
    <!-- All your JS back -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>
    <script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>
    <script src="assets/plugins/wysiwyag/jquery.richtext.js"></script>
    <script src="assets/plugins/wysiwyag/wysiwyag.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.ui.widget.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.fileupload.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.iframe-transport.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.fancy-fileupload.js"></script>
    <script src="assets/plugins/fancyuploder/fancy-uploader.js"></script>
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