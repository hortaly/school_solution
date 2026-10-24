<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM glo_admn WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
include_once("control.php");
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

date_default_timezone_set("Africa/Lagos");
$total_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM staff WHERE status='Active'"))['c'] ?? 0;

$male_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM staff WHERE status='Active' AND staff_gender='Male'"))['c']
 ?? 0;

$female_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM staff WHERE status='Active' AND staff_gender='Female'"))
['c'] ?? 0;

$teaching = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM staff WHERE status='Active' AND staff_role='Teaching Staff'"))
['c'] ?? 0;

$non_teaching = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM staff WHERE status='Active' AND 
staff_role='Non Teaching Staff'"))['c'] ?? 0;


$staff_by_dept = mysqli_query($conn, "SELECT d.dept_name, COUNT(s.id) as cnt FROM staff s
 LEFT JOIN departments d ON d.id = s.department_id WHERE s.status='Active' GROUP BY s.department_id ORDER BY cnt DESC");


$gender_per_dept = mysqli_query($conn, "SELECT d.dept_name, SUM(s.staff_gender='Male') as male_cnt, SUM(s.staff_gender='Female')
as female_cnt, COUNT(s.id) as total FROM staff s LEFT JOIN departments d ON d.id=s.department_id WHERE s.status='Active' GROUP BY
 s.department_id ORDER BY total DESC");

$staff_by_qual = mysqli_query($conn, "SELECT q.qualification_name, COUNT(s.id) as cnt FROM staff s LEFT JOIN qualifications q
 ON q.id=s.qualification_id WHERE s.status='Active' GROUP BY s.qualification_id ORDER BY cnt DESC");


$status_report = mysqli_query($conn, "SELECT status, COUNT(*) as cnt FROM staff GROUP BY status");
$age_query = mysqli_query($conn, "SELECT TIMESTAMPDIFF(YEAR, staff_dob, CURDATE()) as age FROM staff 
WHERE status='Active' AND staff_dob IS NOT NULL AND staff_dob!='0000-00-00' AND staff_dob!=''");

$age_under_30 = 0;
$age_30_40 = 0;
$age_40_50 = 0;
$age_50_plus = 0;
while ($a = mysqli_fetch_assoc($age_query)) {
    $age = (int)$a['age'];
    if ($age < 30) $age_under_30++;
    elseif ($age < 40) $age_30_40++;
    elseif ($age < 50) $age_40_50++;
    else $age_50_plus++;
}
$birthday_month = mysqli_query($conn, "SELECT staff_name, staff_role, staff_dob, DAY(staff_dob)
as day FROM staff WHERE status='Active' AND MONTH(staff_dob)=MONTH(CURDATE()) ORDER BY DAY(staff_dob) ASC");

// FIXED BIRTHDAY THIS WEEK - birthday in next 7 days
$birthday_week = mysqli_query($conn, "SELECT staff_name, staff_role, staff_dob, DAY(staff_dob) 
as day FROM staff WHERE status='Active' AND staff_dob IS NOT NULL AND staff_dob!='0000-00-00' AND
 DAYOFYEAR(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(staff_dob), '-', DAY(staff_dob)), '%Y-%m-%d')) 
 BETWEEN DAYOFYEAR(CURDATE()) AND DAYOFYEAR(CURDATE())+7 ORDER BY MONTH(staff_dob), DAY(staff_dob) ASC");

 
$session_report = mysqli_query($conn, "SELECT session_joined, term_joined, COUNT(*) as cnt FROM staff GROUP BY session_joined, term_joined ORDER BY session_joined DESC");
$dept_names = [];
$dept_counts = [];
$chart_q = mysqli_query($conn, "SELECT d.dept_name, COUNT(s.id) as cnt FROM staff s LEFT JOIN departments d ON d.id=s.department_id WHERE s.status='Active' GROUP BY s.department_id");
while ($r = mysqli_fetch_assoc($chart_q)) {
    $dept_names[] = $r['dept_name'] ?? 'No Dept';
    $dept_counts[] = (int)$r['cnt'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $school_name; ?> || STAFF REPORTS</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
    <style>
        :root {
            --premium-radius: 18px;
            --premium-shadow: 0 10px 35px rgba(0, 0, 0, .06);
        }

        .premium-card {
            border-radius: var(--premium-radius) !important;
            border: 1px solid rgba(0, 0, 0, .06) !important;
            box-shadow: var(--premium-shadow);
            overflow: hidden;
            transition: .2s;
        }

        .premium-v2 {
            border-radius: 16px !important;
            border: 1px solid rgba(0, 0, 0, .05) !important;
            background: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .03) !important;
            transition: .25s;
            overflow: hidden;
        }

        .premium-v2:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .08) !important;
        }

        .icon-v2 {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .chart-wrap-fixed {
            position: relative;
            width: 100%;
            height: 300px;
            max-height: 300px;
        }

        .label-muted {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .6px;
        }

        .table-premium thead th {
            font-size: 11px;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 700;
            background: rgba(0, 0, 0, .02);
        }

        .progress-v2 {
            height: 3px;
            border-radius: 20px;
            background: #f1f5f9;
        }

        .page-header-premium {
            background: linear-gradient(135deg, rgba(98, 89, 202, .08) 0%, rgba(255, 255, 255, 0) 100%);
            border: 1px solid rgba(98, 89, 202, .12);
            border-radius: 16px;
            padding: 18px 22px;
        }

        .dark-mode .premium-card,
        body.dark-mode .premium-card {
            background: #2a2a4a !important;
            border-color: rgba(255, 255, 255, .08) !important;
            box-shadow: none !important;
        }

        .dark-mode .premium-v2,
        body.dark-mode .premium-v2 {
            background: #2a2a4a !important;
            border-color: rgba(255, 255, 255, .06) !important;
            box-shadow: none !important;
        }

        .dark-mode .premium-v2 h2,
        body.dark-mode .premium-v2 h2 {
            color: #fff !important;
        }

        .dark-mode .label-muted,
        body.dark-mode .label-muted {
            color: #a0a3c0 !important;
        }

        .dark-mode .table-premium thead th,
        body.dark-mode .table-premium thead th {
            background: rgba(255, 255, 255, .05) !important;
            color: #a0a3c0 !important;
        }

        .dark-mode .table,
        body.dark-mode .table {
            color: #c5c7d6;
        }

        .dark-mode .table-bordered,
        body.dark-mode .table-bordered {
            border-color: rgba(255, 255, 255, .08) !important;
        }

        .dark-mode .progress-v2,
        body.dark-mode .progress-v2 {
            background: rgba(255, 255, 255, .08) !important;
        }

        .dark-mode .page-header-premium,
        body.dark-mode .page-header-premium {
            background: rgba(255, 255, 255, .04) !important;
            border-color: rgba(255, 255, 255, .08) !important;
        }

        .dark-mode .page-header-premium small,
        body.dark-mode .page-header-premium small {
            color: #a0a3c0 !important;
        }

        .icon-v2.slate {
            background: linear-gradient(135deg, #94a3b8, #64748b);
        }

        .icon-v2.blue {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
        }

        .icon-v2.pink {
            background: linear-gradient(135deg, #ff6b9d, #ff3b7a);
        }

        .icon-v2.teal {
            background: linear-gradient(135deg, #2dd4bf, #14b8a6);
        }

        .icon-v2.amber {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }

        .icon-v2.green {
            background: linear-gradient(135deg, #34d399, #10b981);
        }
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header-premium mb-3">
                    <h1 class="page-title mb-0" style="font-weight:800; font-size:18px;">Staff Reports</h1>
                    <small class="text-muted">All staff reports in <?php echo $school_name; ?></small>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-v2 slate"><i class="fa fa-briefcase"></i></div>
                                    <div>
                                        <p class="label-muted mb-1">Total Active Staff</p>
                                        <h2 class="fw-bold mb-0"><?php echo $total_staff; ?></h2>
                                    </div>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#94a3b8,#64748b);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-v2 blue"><i class="fa fa-mars"></i></div>
                                    <div>
                                        <p class="label-muted mb-1">Male Staff</p>
                                        <h2 class="fw-bold mb-0"><?php echo $male_staff; ?></h2>
                                    </div>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $total_staff > 0 ? ($male_staff / $total_staff * 100) : 0; ?>%;background:linear-gradient(90deg,#60a5fa,#3b82f6);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-v2 pink"><i class="fa fa-venus"></i></div>
                                    <div>
                                        <p class="label-muted mb-1">Female Staff</p>
                                        <h2 class="fw-bold mb-0"><?php echo $female_staff; ?></h2>
                                    </div>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $total_staff > 0 ? ($female_staff / $total_staff * 100) : 0; ?>%;background:linear-gradient(90deg,#ff6b9d,#ff3b7a);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-v2 teal"><i class="fa fa-chalkboard-teacher"></i></div>
                                    <div>
                                        <p class="label-muted mb-1">Teaching / Non-Teaching</p>
                                        <h2 class="fw-bold mb-0"><?php echo $teaching; ?> / <?php echo $non_teaching; ?></h2>
                                    </div>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $total_staff > 0 ? ($teaching / $total_staff * 100) : 0; ?>%;background:linear-gradient(90deg,#2dd4bf,#14b8a6);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Gender Per Department</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Department</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php while ($g = mysqli_fetch_assoc($gender_per_dept)) {
                                                    echo "<tr><td>" . ($g['dept_name'] ?: 'No Dept') . "</td><td>" . $g['male_cnt'] . "</td><td>" . $g['female_cnt'] . "</td><td><span class='badge bg-primary'>" . $g['total'] . "</span></td></tr>";
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Department Distribution</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrap-fixed"><canvas id="deptChart"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Staff By Department</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Department</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php mysqli_data_seek($staff_by_dept, 0);
                                                while ($d = mysqli_fetch_assoc($staff_by_dept)) {
                                                    echo "<tr><td>" . ($d['dept_name'] ?: 'No Dept') . "</td><td><span class='badge bg-primary'>" . $d['cnt'] . "</span></td></tr>";
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Staff By Qualification</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Qualification</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php while ($q = mysqli_fetch_assoc($staff_by_qual)) {
                                                    echo "<tr><td>" . ($q['qualification_name'] ?: 'Not Set') . "</td><td><span class='badge bg-light text-dark'>" . $q['cnt'] . "</span></td></tr>";
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Status & Age Group</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-premium">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php while ($s = mysqli_fetch_assoc($status_report)) {
                                                echo "<tr><td>" . $s['status'] . "</td><td><span class='badge bg-primary'>" . $s['cnt'] . "</span></td></tr>";
                                            } ?></tbody>
                                </table>
                                <hr>
                                <p class="mb-2 fw-bold">Age Report:</p>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="card premium-v2 mb-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="icon-v2 slate"><i class="fa fa-user"></i></div>
                                                    <div>
                                                        <p class="label-muted mb-0">Under 30</p>
                                                        <h3 class="fw-bold mb-0"><?php echo $age_under_30; ?></h3>
                                                    </div>
                                                </div>
                                                <div class="progress progress-v2 mt-2">
                                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#94a3b8,#64748b);"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card premium-v2 mb-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="icon-v2 blue"><i class="fa fa-user"></i></div>
                                                    <div>
                                                        <p class="label-muted mb-0">30 - 40</p>
                                                        <h3 class="fw-bold mb-0"><?php echo $age_30_40; ?></h3>
                                                    </div>
                                                </div>
                                                <div class="progress progress-v2 mt-2">
                                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#60a5fa,#3b82f6);"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card premium-v2 mb-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="icon-v2 amber"><i class="fa fa-user"></i></div>
                                                    <div>
                                                        <p class="label-muted mb-0">40 - 50</p>
                                                        <h3 class="fw-bold mb-0"><?php echo $age_40_50; ?></h3>
                                                    </div>
                                                </div>
                                                <div class="progress progress-v2 mt-2">
                                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#fbbf24,#f59e0b);"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card premium-v2 mb-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="icon-v2 green"><i class="fa fa-user"></i></div>
                                                    <div>
                                                        <p class="label-muted mb-0">50+</p>
                                                        <h3 class="fw-bold mb-0"><?php echo $age_50_plus; ?></h3>
                                                    </div>
                                                </div>
                                                <div class="progress progress-v2 mt-2">
                                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#34d399,#10b981);"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Admission Report - Session & Term</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Session</th>
                                                <th>Term</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php while ($sr = mysqli_fetch_assoc($session_report)) {
                                                    echo "<tr><td>" . $sr['session_joined'] . "</td><td>" . $sr['term_joined'] . "</td><td><span class='badge bg-primary'>" . $sr['cnt'] . "</span></td></tr>";
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Birthday - This Month</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Day</th>
                                                <th>DOB</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php while ($bm = mysqli_fetch_assoc($birthday_month)) {
                                                    echo "<tr><td>" . $bm['staff_name'] . "</td><td>" . $bm['day'] . "</td><td>" . $bm['staff_dob'] . "</td></tr>";
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold">Birthday - This Week (Next 7 Days)</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Role</th>
                                                <th>DOB</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php $has = 0;
                                                while ($bw = mysqli_fetch_assoc($birthday_week)) {
                                                    $has = 1;
                                                    echo "<tr><td>" . $bw['staff_name'] . "</td><td>" . $bw['staff_role'] . "</td><td>" . $bw['staff_dob'] . "</td></tr>";
                                                }
                                                if (!$has) echo "<tr><td colspan='3' class='text-center text-muted'>No birthday in next 7 days</td></tr>"; ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <footer class="footer"><?php include("footer.php"); ?></footer>
    </div>
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.sparkline.min.js"></script>
    <script src="assets/js/sticky.js"></script>
    <script src="assets/js/circle-progress.min.js"></script>
    <script src="assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script src="assets/plugins/peitychart/peitychart.init.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>
    <script src="assets/plugins/chart/Chart.bundle.js"></script>
    <script src="assets/plugins/chart/utils.js"></script>
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="assets/js/apexcharts.js"></script>
    <script src="assets/plugins/apexchart/irregular-data-series.js"></script>
    <script src="assets/plugins/flot/jquery.flot.js"></script>
    <script src="assets/plugins/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/plugins/flot/chart.flot.sampledata.js"></script>
    <script src="assets/plugins/flot/dashboard.sampledata.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>
    <script src="assets/js/index1.js"></script>
    <script src="assets/js/themeColors.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/custom-swicher.js"></script>
    <script src="assets/switcher/js/switcher.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const deptCtx = document.getElementById('deptChart');
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($dept_names); ?>,
                datasets: [{
                    data: <?php echo json_encode($dept_counts); ?>,
                    backgroundColor: ['#6259ca', '#ff6b9d', '#2dce89', '#f59e0b', '#60a5fa', '#a78bfa', '#14b8a6', '#fb7185', '#34d399', '#fbbf24'],
                    borderWidth: 0,
                    hoverOffset: 16
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                animation: {
                    duration: 1600,
                    easing: 'easeOutBounce',
                    animateRotate: true,
                    animateScale: true
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: {
                                size: 11,
                                weight: 600
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>