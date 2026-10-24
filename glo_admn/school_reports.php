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
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <title> <?php echo $school_name; ?> || SCHOOL REPORTS </title>
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
        }

        .dark-mode .premium-card {
            background: var(--dark-body, #1e1e2d) !important;
            border-color: rgba(255, 255, 255, .07) !important;
        }

        .page-header-premium {
            background: linear-gradient(135deg, rgba(98, 89, 202, .08) 0%, rgba(255, 255, 255, 0) 100%);
            border: 1px solid rgba(98, 89, 202, .12);
            border-radius: 16px;
            padding: 18px 22px;
            margin-bottom: 24px;
        }

        .dark-mode .page-header-premium {
            background: rgba(255, 255, 255, .04);
            border-color: rgba(255, 255, 255, .08);
        }

        .page-header-premium .page-title {
            font-weight: 800;
            font-size: 18px;
            letter-spacing: -.3px;
            line-height: 1.4;
        }

        .greet-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #6259ca, #8a7cf5);
            color: #fff;
            font-size: 20px;
        }

        .action-card {
            border-radius: var(--premium-radius) !important;
            border: none !important;
            position: relative;
            overflow: hidden;
            transition: all .25s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, .15) !important;
        }

        .action-card .card-body {
            padding: 22px !important;
        }

        .action-card .bg-pattern {
            position: absolute;
            right: -20px;
            top: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .15);
            border-radius: 50%;
        }

        .action-card h3 {
            font-weight: 800;
            font-size: 18px;
            letter-spacing: -.3px;
        }

        .action-card .icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .20);
            display: grid;
            place-items: center;
            backdrop-filter: blur(6px);
        }

        .modal-content {
            border-radius: 20px !important;
            border: none !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .20);
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            padding: 18px 24px;
        }

        .dark-mode .modal-header {
            border-color: rgba(255, 255, 255, .06);
        }

        .modal-body {
            padding: 24px;
        }

        .form-control-premium {
            height: 46px !important;
            border-radius: 12px !important;
            border-width: 1.5px !important;
            font-weight: 500;
        }

        .form-control-premium:focus {
            border-color: #6259ca !important;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12) !important;
        }

        .col-form-label {
            font-weight: 600;
            font-size: 13px;
        }

        .btn-premium {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(98, 89, 202, .25);
        }

        .panel-tabs a {
            border-radius: 100px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 13px;
            transition: all .2s;
        }

        .panel-tabs a.active {
            background: #6259ca;
            color: #fff !important;
            box-shadow: 0 6px 14px rgba(98, 89, 202, .25);
        }

        .table-premium thead th {
            font-size: 11px;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 700;
            background: rgba(0, 0, 0, .02);
        }

        .dark-mode .table-premium thead th {
            background: rgba(255, 255, 255, .04);
        }

        .badge {
            border-radius: 100px;
            padding: 6px 12px;
            font-weight: 700;
        }

        .chart-wrap-fixed {
            position: relative;
            width: 100%;
            height: 300px;
            max-height: 300px;
        }

        .chart-wrap-fixed-sm {
            position: relative;
            width: 100%;
            height: 260px;
            max-height: 260px;
        }
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <?php
                date_default_timezone_set("Africa/Lagos");
                $hour = date("H");
                if ($hour >= 0 && $hour < 12) {
                    $greetings = "Good Morning, How Was Your Night";
                    $greetIcon = "fe fe-sunrise";
                } elseif ($hour >= 12 && $hour < 17) {
                    $greetings = "Good Afternoon, How is your day going";
                    $greetIcon = "fe fe-sun";
                } else {
                    $greetings = "Good Evening, don't sleep late";
                    $greetIcon = "fe fe-moon";
                }
                ?>
                <?php
                $sql = "SELECT SUM(amount) AS total_expected FROM ledger WHERE entry_type='Debit'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $total_expected = $row['total_expected'] ? $row['total_expected'] : 0;
                $sql_actual = "SELECT SUM(amount) AS total_actual FROM ledger WHERE entry_type='Credit'";
                $result_actual = mysqli_query($conn, $sql_actual);
                $row_actual = mysqli_fetch_assoc($result_actual);
                $total_actual = $row_actual['total_actual'] ? $row_actual['total_actual'] : 0;
                $sql_expected_current_session = "SELECT SUM(amount) AS total_expected_current_session FROM ledger WHERE entry_type='Debit' AND session='$current_session'";
                $result_expected_current_session = mysqli_query($conn, $sql_expected_current_session);
                $row_expected_current_session = mysqli_fetch_assoc($result_expected_current_session);
                $total_expected_current_session = $row_expected_current_session['total_expected_current_session'] ? $row_expected_current_session['total_expected_current_session'] : 0;
                $sql_expected_current_term = "SELECT SUM(amount) AS total_expected FROM ledger WHERE entry_type='Debit' AND session ='$current_session' AND term='$current_term'";
                $result_expected_current_term = mysqli_query($conn, $sql_expected_current_term);
                $row_expected_current_term = mysqli_fetch_assoc($result_expected_current_term);
                $total_expected_current_term = $row_expected_current_term['total_expected'] ? $row_expected_current_term['total_expected'] : 0;
                $sql_actual_current_session = "SELECT SUM(amount) AS total_actual_current_session FROM ledger WHERE entry_type='Credit' AND session='$current_session'";
                $result_actual_current_session = mysqli_query($conn, $sql_actual_current_session);
                $row_actual_current_session = mysqli_fetch_assoc($result_actual_current_session);
                $total_actual_current_session = $row_actual_current_session['total_actual_current_session'] ? $row_actual_current_session['total_actual_current_session'] : 0;
                $sql_actual_current_term = "SELECT SUM(amount) AS total_actual FROM ledger WHERE entry_type='Credit' AND session='$current_session' AND term='$current_term'";
                $result_actual_current_term = mysqli_query($conn, $sql_actual_current_term);
                $row_actual_current_term = mysqli_fetch_assoc($result_actual_current_term);
                $total_actual_current_term = $row_actual_current_term['total_actual'] ? $row_actual_current_term['total_actual'] : 0;
                $outstanding_current_session = $total_expected_current_session - $total_actual_current_session;
                $outstanding_current_term = $total_expected_current_term - $total_actual_current_term;
                $outstanding = $total_expected - $total_actual;
                $sql_parents = "SELECT COUNT(*) AS total_parents FROM parents";
                $result_parents = mysqli_query($conn, $sql_parents);
                $row_parents = mysqli_fetch_assoc($result_parents);
                $total_parents = $row_parents['total_parents'] ? $row_parents['total_parents'] : 0;
                $sql_students = "SELECT COUNT(*) AS total_students FROM students";
                $result_students = mysqli_query($conn, $sql_students);
                $row_students = mysqli_fetch_assoc($result_students);
                $total_students = $row_students['total_students'] ? $row_students['total_students'] : 0;
                $sql_staff = "SELECT COUNT(*) AS total_staff FROM staff";
                $result_staff = mysqli_query($conn, $sql_staff);
                $row_staff = mysqli_fetch_assoc($result_staff);
                $total_staff = $row_staff['total_staff'] ? $row_staff['total_staff'] : 0;
                $sql_teaching = "SELECT COUNT(*) AS total_teaching FROM staff WHERE staff_role='Teaching Staff'";
                $result_teaching = mysqli_query($conn, $sql_teaching);
                $row_teaching = mysqli_fetch_assoc($result_teaching);
                $total_teaching = $row_teaching['total_teaching'] ? $row_teaching['total_teaching'] : 0;
                $sql_nonteaching = "SELECT COUNT(*) AS total_nonteaching FROM staff WHERE staff_role='Non Teaching Staff'";
                $result_nonteaching = mysqli_query($conn, $sql_nonteaching);
                $row_nonteaching = mysqli_fetch_assoc($result_nonteaching);
                $total_nonteaching = $row_nonteaching['total_nonteaching'] ? $row_nonteaching['total_nonteaching'] : 0;
                $percent_actual = $total_expected > 0 ? round(($total_actual / $total_expected) * 100) : 0;
                $percent_outstanding = 100 - $percent_actual;
                $percent_actual_current_session = $total_expected_current_session > 0 ? round(($total_actual_current_session / $total_expected_current_session) * 100) : 0;
                $percent_outstanding_current_session = 100 - $percent_actual_current_session;
                $percent_actual_current_term = $total_expected_current_term > 0 ? round(($total_actual_current_term / $total_expected_current_term) * 100) : 0;
                $percent_outstanding_current_term = 100 - $percent_actual_current_term;
                $sql_today = "SELECT SUM(amount) AS today_income FROM ledger WHERE entry_type='Credit' AND DATE(date) = CURDATE()";
                $res_today = mysqli_query($conn, $sql_today);
                $today_income = 0;
                if ($res_today) {
                    $r_t = mysqli_fetch_assoc($res_today);
                    $today_income = $r_t['today_income'] ? $r_t['today_income'] : 0;
                }
                $sql_adm_rep = "SELECT admission_session, admission_term, student_class, COUNT(*) as cnt FROM students GROUP BY admission_session, 
                admission_term, student_class ORDER BY admission_session DESC";
                $res_adm_rep = mysqli_query($conn, $sql_adm_rep);

             

                // === ALL CHART PHP FOR ONE PLACE - FIXED ===
                $sql_rev_over = "SELECT s.student_class, SUM(l.amount) AS total_revenue FROM students s JOIN ledger l ON l.student_uin = s.student_uin WHERE l.entry_type = 'Credit' GROUP BY s.student_class ORDER BY total_revenue DESC";
                $result_rev_over = mysqli_query($conn, $sql_rev_over);
                $rev_classes_over = [];
                $rev_totals_over = [];
                while ($row_rev_over = mysqli_fetch_assoc($result_rev_over)) {
                    $rev_classes_over[] = $row_rev_over['student_class'];
                    $rev_totals_over[] = (float)$row_rev_over['total_revenue'];
                }

                $sql_rev_current = "SELECT s.student_class, SUM(l.amount) AS total_revenue FROM students s JOIN ledger l ON l.student_uin = s.student_uin WHERE l.entry_type = 'Credit' AND l.session='$current_session' AND l.term='$current_term' GROUP BY s.student_class ORDER BY total_revenue DESC";
                $result_rev_current = mysqli_query($conn, $sql_rev_current);
                $rev_classes_current = [];
                $rev_totals_current = [];
                while ($row_rev_current = mysqli_fetch_assoc($result_rev_current)) {
                    $rev_classes_current[] = $row_rev_current['student_class'];
                    $rev_totals_current[] = (float)$row_rev_current['total_revenue'];
                }

                $sql_c = "SELECT student_class AS class_name, COUNT(*) AS student_count FROM students GROUP BY student_class ORDER BY student_class ASC";
                $result_c = mysqli_query($conn, $sql_c);
                $class_names = [];
                $student_counts = [];
                while ($row_c = mysqli_fetch_assoc($result_c)) {
                    $class_names[] = $row_c['class_name'];
                    $student_counts[] = (int)$row_c['student_count'];
                }
                $sql_m = "SELECT COUNT(*) AS male_count FROM students WHERE student_gender='Male'";
                $male_result = mysqli_query($conn, $sql_m);
                $male_row = mysqli_fetch_assoc($male_result);
                $sql_f = "SELECT COUNT(*) AS female_count FROM students WHERE student_gender='Female'";
                $female_result = mysqli_query($conn, $sql_f);
                $female_row = mysqli_fetch_assoc($female_result);
                ?>
                <style>
                    .premium-v2 {
                        border-radius: 16px !important;
                        border: 1px solid rgba(0, 0, 0, .05) !important;
                        background: #ffffff !important;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, .03) !important;
                        transition: .2s;
                        overflow: hidden;
                    }

                    .premium-v2:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 8px 20px rgba(0, 0, 0, .06) !important;
                    }

                    .icon-v2 {
                        width: 38px;
                        height: 38px;
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 15px;
                        color: #fff;
                        flex-shrink: 0;
                    }

                    .icon-v2.pink {
                        background: linear-gradient(135deg, #ff6b9d, #ff3b7a);
                    }

                    .icon-v2.green {
                        background: linear-gradient(135deg, #34d399, #10b981);
                    }

                    .icon-v2.amber {
                        background: linear-gradient(135deg, #fbbf24, #f59e0b);
                    }

                    .icon-v2.violet {
                        background: linear-gradient(135deg, #a78bfa, #8b5cf6);
                    }

                    .icon-v2.blue {
                        background: linear-gradient(135deg, #60a5fa, #3b82f6);
                    }

                    .icon-v2.slate {
                        background: linear-gradient(135deg, #94a3b8, #64748b);
                    }

                    .icon-v2.teal {
                        background: linear-gradient(135deg, #2dd4bf, #14b8a6);
                    }

                    .icon-v2.orange {
                        background: linear-gradient(135deg, #fb923c, #f97316);
                    }

                    .progress-v2 {
                        height: 3px;
                        border-radius: 20px;
                        background: #f1f5f9;
                    }

                    .premium-v2 h2 {
                        font-size: 20px !important;
                        line-height: 1.2;
                        margin-bottom: 6px !important;
                        color: #1e293b !important;
                    }

                    .premium-v2 .label-muted {
                        font-size: 10px !important;
                        letter-spacing: .5px;
                        color: #64748b;
                        text-transform: uppercase;
                    }

                    body.dark-mode .premium-v2,
                    .dark-mode .premium-v2 {
                        background: #2e304e !important;
                        border-color: rgba(255, 255, 255, .06) !important;
                        box-shadow: none !important;
                    }

                    body.dark-mode .premium-v2 h2 {
                        color: #fff !important;
                    }

                    body.dark-mode .premium-v2 .label-muted {
                        color: #a0a3c0 !important;
                    }

                    body.dark-mode .progress-v2 {
                        background: rgba(255, 255, 255, .08) !important;
                    }
                </style>
                <div class="row g-2">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 pink"><i class="fa fa-money"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Total Expected Income</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($total_expected, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fff1f2;color:#e11d48;font-size:10px;">100%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#ff6b9d,#ff3b7a);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 green"><i class="fa fa-check"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Actual Income</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($total_actual, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:10px;"><?php echo $percent_actual; ?>%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $percent_actual; ?>%;background:linear-gradient(90deg,#34d399,#10b981);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 amber"><i class="fa fa-exclamation-triangle"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Outstanding Balance</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($outstanding, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fffbeb;color:#d97706;font-size:10px;"><?php echo $percent_outstanding; ?>%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $percent_outstanding; ?>%;background:linear-gradient(90deg,#fbbf24,#f59e0b);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 pink"><i class="fa fa-money"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Expected This Session</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($total_expected_current_session, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fff1f2;color:#e11d48;font-size:10px;">100%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#ff6b9d,#ff3b7a);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 green"><i class="fa fa-check"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Actual Income This Session</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($total_actual_current_session, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:10px;"><?php echo $percent_actual_current_session; ?>%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $percent_actual_current_session; ?>%;background:linear-gradient(90deg,#34d399,#10b981);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 amber"><i class="fa fa-exclamation-triangle"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Outstanding This Session</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($outstanding_current_session, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fffbeb;color:#d97706;font-size:10px;"><?php echo $percent_outstanding_current_session; ?>%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $percent_outstanding_current_session; ?>%;background:linear-gradient(90deg,#fbbf24,#f59e0b);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 pink"><i class="fa fa-money"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Expected This Term</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($total_expected_current_term, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fff1f2;color:#e11d48;font-size:10px;">100%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#ff6b9d,#ff3b7a);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 green"><i class="fa fa-check"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Actual Income This Term</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($total_actual_current_term, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#f0fdf4;color:#16a34a;font-size:10px;"><?php echo $percent_actual_current_term; ?>%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $percent_actual_current_term; ?>%;background:linear-gradient(90deg,#34d399,#10b981);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 amber"><i class="fa fa-exclamation-triangle"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Outstanding This Term</p>
                                            <h2 class="fw-bold mb-0">&#8358;<?php echo number_format($outstanding_current_term, 0); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fffbeb;color:#d97706;font-size:10px;"><?php echo $percent_outstanding_current_term; ?>%</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:<?php echo $percent_outstanding_current_term; ?>%;background:linear-gradient(90deg,#fbbf24,#f59e0b);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 violet"><i class="fa fa-users"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Parents</p>
                                            <h2 class="fw-bold mb-0"><?php echo number_format($total_parents); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#f5f3ff;color:#7c3aed;font-size:10px;">Total</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:85%;background:linear-gradient(90deg,#a78bfa,#8b5cf6);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 blue"><i class="fa fa-graduation-cap"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Students</p>
                                            <h2 class="fw-bold mb-0"><?php echo number_format($total_students); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#eff6ff;color:#2563eb;font-size:10px;">All</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:90%;background:linear-gradient(90deg,#60a5fa,#3b82f6);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 slate"><i class="fa fa-briefcase"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Staff</p>
                                            <h2 class="fw-bold mb-0"><?php echo number_format($total_staff); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#f8fafc;color:#475569;font-size:10px;">Team</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:100%;background:linear-gradient(90deg,#94a3b8,#64748b);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 teal"><i class="fa fa-book"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Teaching</p>
                                            <h2 class="fw-bold mb-0"><?php echo number_format($total_teaching); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#f0fdfa;color:#0d9488;font-size:10px;">Academic</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:70%;background:linear-gradient(90deg,#2dd4bf,#14b8a6);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="card premium-v2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-v2 orange"><i class="fa fa-cogs"></i></div>
                                        <div>
                                            <p class="label-muted mb-0">Non-Teaching</p>
                                            <h2 class="fw-bold mb-0"><?php echo number_format($total_nonteaching); ?></h2>
                                        </div>
                                    </div><span class="badge rounded-pill" style="background:#fff7ed;color:#ea580c;font-size:10px;">Support</span>
                                </div>
                                <div class="progress progress-v2 mt-2">
                                    <div class="progress-bar" style="width:55%;background:linear-gradient(90deg,#fb923c,#f97316);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card premium-card">
                            <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-credit-card me-2 text-primary"></i> Payment Reports - <?php echo $current_term; ?> <?php echo $current_session; ?> (Today: &#8358;<?php echo number_format($today_income); ?>)</h3><span class="badge bg-primary-transparent text-primary">Live</span>
                            </div>
                            <div class="card-body pt-0">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                        <ul class="nav panel-tabs product-sale mb-3 px-3 pt-3">
                                            <li><a href="#tab5" class="active" data-bs-toggle="tab">All Students</a></li>
                                            <li><a href="#tab6" data-bs-toggle="tab" class="text-dark">Balanced</a></li>
                                            <li><a href="#tab7" data-bs-toggle="tab" class="text-dark">Debtors</a></li>
                                            <li><a href="#tab8" data-bs-toggle="tab" class="text-dark">Overdue</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="table-responsive">
                                                <table id="data-table" class="table table-bordered text-nowrap mb-0 table-premium">
                                                    <thead>
                                                        <tr>
                                                            <th>Payment ID</th>
                                                            <th>Student</th>
                                                            <th>Class</th>
                                                            <th>Amount</th>
                                                            <th>Session</th>
                                                            <th>Term</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody><?php $q_all = "SELECT p.*, s.student_name, s.student_class FROM payments p LEFT JOIN students s ON s.student_uin = p.student_uin WHERE p.session='$current_session' AND p.term='$current_term' ORDER BY p.created_at DESC LIMIT 200";
                                                            $r_all = mysqli_query($conn, $q_all);
                                                            if ($r_all) {
                                                                while ($row_all = mysqli_fetch_assoc($r_all)) {
                                                                    echo "<tr><td>" . $row_all['payment_id'] . "</td><td>" . $row_all['student_name'] . " (" . $row_all['student_uin'] . ")</td><td>" . $row_all['student_class'] . "</td><td>&#8358;" . number_format($row_all['amount_paid']) . "</td><td>" . $row_all['session'] . "</td><td>" . $row_all['term'] . "</td><td>" . $row_all['created_at'] . "</td><td><span class='badge bg-success-transparent text-success'>Success</span></td></tr>";
                                                                }
                                                            } ?></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab6">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-premium">
                                                    <thead>
                                                        <tr>
                                                            <th>Student</th>
                                                            <th>Class</th>
                                                            <th>Expected</th>
                                                            <th>Paid</th>
                                                            <th>Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody><?php $q_bal = "SELECT s.student_name, s.student_uin, s.student_class, (SELECT IFNULL(SUM(amount),0) FROM ledger WHERE entry_type='Debit' AND student_uin=s.student_uin AND session='$current_session' AND term='$current_term') as expected, (SELECT IFNULL(SUM(amount),0) FROM ledger WHERE entry_type='Credit' AND student_uin=s.student_uin AND session='$current_session' AND term='$current_term') as paid FROM students s HAVING paid >= expected AND expected > 0 LIMIT 200";
                                                            $r_bal = mysqli_query($conn, $q_bal);
                                                            if ($r_bal) {
                                                                while ($b = mysqli_fetch_assoc($r_bal)) {
                                                                    $bal = $b['paid'] - $b['expected'];
                                                                    echo "<tr><td>" . $b['student_name'] . " (" . $b['student_uin'] . ")</td><td>" . $b['student_class'] . "</td><td>&#8358;" . number_format($b['expected']) . "</td><td>&#8358;" . number_format($b['paid']) . "</td><td><span class='badge bg-success'>&#8358;" . number_format($bal) . "</span></td></tr>";
                                                                }
                                                            } ?></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab7">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-premium">
                                                    <thead>
                                                        <tr>
                                                            <th>Student</th>
                                                            <th>Class</th>
                                                            <th>Expected</th>
                                                            <th>Paid</th>
                                                            <th>Owing</th>
                                                        </tr>
                                                    </thead>
         <tbody><?php $q_debt = "SELECT s.student_name, s.student_uin, s.student_class, 
         (SELECT IFNULL(SUM(amount),0) FROM ledger WHERE entry_type='Debit'
          AND student_uin=s.student_uin AND session='$current_session' AND term='$current_term')
           as expected, (SELECT IFNULL(SUM(amount),0) FROM ledger WHERE entry_type='Credit' AND 
           student_uin=s.student_uin AND session='$current_session' AND term='$current_term') as paid 
           FROM students s HAVING paid < expected LIMIT 200";
                                                            $r_debt = mysqli_query($conn, $q_debt);
                                                            if ($r_debt) {
                                                                while ($d = mysqli_fetch_assoc($r_debt)) {
                                                                    $owe = $d['expected'] - $d['paid'];
                                                                    echo "<tr><td>" . $d['student_name'] .
                                                                     " (" . $d['student_uin'] . ")</td><td>" .
                                                                      $d['student_class'] . "</td><td>&#8358;" .
                                                                       number_format($d['expected']) . "</td><td>&#8358;" 
                                                                       . number_format($d['paid']) . "</td><td class='text-danger fw-bold'>&#8358;" . number_format($owe) . "</td></tr>";
                                                                }
                                                            } ?></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab8">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-premium">
                                                    <thead>
                                                        <tr>
                                                            <th>Session</th>
                                                            <th>Term</th>
                                                            <th>Expected</th>
                                                            <th>Paid</th>
                                                            <th>Outstanding</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody><?php $q_over = "SELECT session, term, SUM(CASE WHEN entry_type='Debit' THEN amount ELSE 0 END) as exp, SUM(CASE WHEN entry_type='Credit' THEN amount ELSE 0 END) as paid FROM ledger GROUP BY session, term ORDER BY session DESC";
                                                            $r_over = mysqli_query($conn, $q_over);
                                                            if ($r_over) {
                                                                while ($o = mysqli_fetch_assoc($r_over)) {
                                                                    $outs = $o['exp'] - $o['paid'];
                                                                    if ($outs > 0) {
                                                                        echo "<tr><td>" . $o['session'] . "</td><td>" . $o['term'] . "</td><td>&#8358;" . number_format($o['exp']) . "</td><td>&#8358;" . number_format($o['paid']) . "</td><td class='text-danger fw-bold'>&#8358;" . number_format($outs) . "</td></tr>";
                                                                    }
                                                                }
                                                            } ?></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i>Admission Report -
                                    By Session & Term</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Session</th>
                                                <th>Term</th>
                                                <th>Class</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php if ($res_adm_rep) {
                                                    while ($adm = mysqli_fetch_assoc($res_adm_rep)) {
                                                        echo "<tr><td>" . $adm['admission_session'] . "</td><td>" . $adm['admission_term'] . "</td><td>" . $adm['student_class'] . "</td><td><span class='badge bg-primary'>" . $adm['cnt'] . "</span></td></tr>";
                                                    }
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-file-text me-2 text-primary"></i>Fee Type Income - This Term</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Fee Type</th>
                                                <th>Expected</th>
                                                <th>Actual</th>
                                                <th>Outstanding</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php $q_fee = "SELECT fee_id, SUM(CASE WHEN entry_type='Debit' THEN amount ELSE 0 END) as exp, SUM(CASE WHEN entry_type='Credit' THEN amount ELSE 0 END) as paid FROM ledger WHERE session='$current_session' AND term='$current_term' GROUP BY fee_id";
                                                $r_fee = mysqli_query($conn, $q_fee);
                                                if ($r_fee) {
                                                    while ($f = mysqli_fetch_assoc($r_fee)) {
                                                        $outs = $f['exp'] - $f['paid'];
                                                        echo "<tr><td>" . $f['fee_id'] . "</td><td>&#8358;" . number_format($f['exp']) . "</td><td>&#8358;" . number_format($f['paid']) . "</td><td>&#8358;" . number_format($outs) . "</td></tr>";
                                                    }
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
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i>Admission Report Of Parent -
                                    By Session & Term</h3>
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
                                        <?php
                                           $sqlpnt = "SELECT admission_session, admission_term, COUNT(*) as pnt FROM parents GROUP BY admission_session,
                                          admission_term ORDER BY admission_session DESC";
                                         $respnt = mysqli_query($conn, $sqlpnt);
                                        ?>
                                        <tbody><?php if ($respnt) {
                                                    while ($admpnt = mysqli_fetch_assoc($respnt)) {
                                                        echo "<tr>
                                                        <td>" . $admpnt['admission_session'] . "</td>
                                                        <td>". $admpnt['admission_term'] . "</td>
                                                       
                                                        <td><span class='badge bg-primary'>" . $admpnt['pnt'] . "</span></td>
                                                        </tr>";
                                                    }
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Teaching vs Non-teaching per session joined -->
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i>Admission Report of teaching and non-teaching staff-term and
                                 session</h3>
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
                                        <?php
                                           $sqlpnt = "SELECT session_joined, term_joined, COUNT(*) as tnt FROM staff GROUP BY session_joined,
                                          term_joined ORDER BY session_joined DESC";
                                         $respnt = mysqli_query($conn, $sqlpnt);
                                        ?>
                                        <tbody><?php if ($respnt) {
                                                    while ($admpnt = mysqli_fetch_assoc($respnt)) {
                                                        echo "<tr>
                                                        <td>" . $admpnt['session_joined'] . "</td>
                                                        <td>". $admpnt['term_joined'] . "</td>
                                                       
                                                        <td><span class='badge bg-primary'>" . $admpnt['tnt'] . "</span></td>
                                                        </tr>";
                                                    }
                                                } ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                   <!-- Parents with more than 1 child for school (family discount) -->
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i>
                            Parents With More Than One Wards</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Parent Name</th>
                                                <th>Parent Uin</th>
                                                <th>Status</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                    
                    <?php
$sql = "SELECT p.parent_name, p.parent_uin, p.status, COUNT(s.student_uin) as total_ward 
        FROM parents p 
        INNER JOIN students s ON s.parent_uin = p.parent_uin 
        GROUP BY p.parent_uin, p.parent_name  
        HAVING total_ward > 0
        ORDER BY total_ward DESC";

$query = mysqli_query($conn, $sql);
?>
<tbody>
<?php 
if ($query && mysqli_num_rows($query) > 0) {
    while ($admpnt = mysqli_fetch_assoc($query)) {
        echo "<tr>
        <td>" . $admpnt['parent_name'] . "</td>
        <td>". $admpnt['parent_uin'] . "</td>
        <td>". $admpnt['status'] . "</td>
        <td><span class='badge bg-primary'>" . $admpnt['total_ward'] . "</span></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>No parents with wards found</td></tr>";
}
?>
</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Parents with more than 1 child for school (family discount) -->
                   <!-- Parents with more than 0 child for school (family discount) -->
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i>
                            Parents With More Than No Wards</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table table-bordered table-premium">
                                        <thead>
                                            <tr>
                                                <th>Parent Name</th>
                                                <th>Parent Uin</th>
                                                <th>Status</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                    
                    <?php
$sql = "SELECT p.parent_name, p.parent_uin, p.status, COUNT(s.student_uin) as total_ward 
        FROM parents p 
        LEFT JOIN students s ON s.parent_uin = p.parent_uin 
        GROUP BY p.parent_uin, p.parent_name  
        HAVING total_ward = 0
        ORDER BY total_ward DESC";

$query = mysqli_query($conn, $sql);
?>
<tbody>
<?php 
if ($query && mysqli_num_rows($query) > 0) {
    while ($admpnt = mysqli_fetch_assoc($query)) {
        echo "<tr>
        <td>" . $admpnt['parent_name'] . "</td>
        <td>". $admpnt['parent_uin'] . "</td>
        <td>". $admpnt['status'] . "</td>
        <td><span class='badge bg-primary'>" . $admpnt['total_ward'] . "</span></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>No parents with wards found</td></tr>";
}
?>
</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Parents with more than 1 child for school (family discount) -->
                   
                </div>

                



                <div class="row">
                    <div class="col-lg-8">
                        <div class="card premium-card">
                            <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-bar-chart-2 me-2 text-primary"></i>Finance Overview of <?php echo $school_name; ?></h3><span class="badge bg-primary-transparent text-primary">SQL Live</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrap-fixed"><canvas id="financeChart"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card premium-card">
                            <div class="card-header" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-pie-chart me-2 text-warning"></i>School Population of <?php echo $school_name; ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrap-fixed-sm"><canvas id="popChart"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                <h3 class="card-title mb-0 fw-bold"><i class="fe fe-bar-chart-2 me-2 text-primary"></i>Finance Overview of <?php echo $school_name; ?> In <?php echo $current_session; ?> Academic Year</h3><span class="badge bg-primary-transparent text-primary">SQL Live</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrap-fixed"><canvas id="financeChart_current_session"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card premium-card">
                                <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                    <h3 class="card-title mb-0 fw-bold"><i class="fe fe-bar-chart-2 me-2 text-primary"></i>Finance Overview of <?php echo $school_name; ?> In <?php echo $current_term; ?> of <?php echo $current_session; ?> Academic Year</h3><span class="badge bg-primary-transparent text-primary">SQL Live</span>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrap-fixed"><canvas id="financeChart_current_term"></canvas></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card premium-card">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                        <h3 class="card-title mb-0 fw-bold"><i class="fe fe-bar-chart-2 me-2 text-primary"></i> Finance Overview of Classes in Over the years </h3>
                                        <div class="d-flex gap-2"><button id="prevFinance_over_Class" class="btn btn-sm btn-light" style="border-radius:10px;">‹ Prev</button><button id="nextFinance_over_Class" class="btn btn-sm btn-primary" style="border-radius:10px;">Next ›</button></div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-wrap-fixed"><canvas id="financeChart_over_class"></canvas></div><small id="finance_over_ClassPageInfo" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card premium-card">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                        <h3 class="card-title mb-0 fw-bold"><i class="fe fe-bar-chart-2 me-2 text-primary"></i> Finance
                                            Overview of Classes in <?php echo $school_name; ?> for <?php echo $current_term; ?> <?php echo $current_session; ?> Academic Year </h3>
                                        <div class="d-flex gap-2"><button id="prevFinanceClass" class="btn btn-sm btn-light" style="border-radius:10px;">‹ Prev</button><button id="nextFinanceClass" class="btn btn-sm btn-primary" style="border-radius:10px;">Next ›</button></div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-wrap-fixed"><canvas id="financeChart_class"></canvas></div><small id="financeClassPageInfo" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card premium-card">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                        <div>
                                            <h3 class="card-title mb-0 fw-bold"><i class="fe fe-bar-chart-2 me-2 text-primary"></i>
                                                Class and Student Distribution of <?php echo $school_name; ?></h3><small id="classPageInfo" class="text-muted"></small>
                                        </div>
                                        <div class="d-flex gap-2"><button id="prevClass" class="btn btn-sm btn-light" style="border-radius:10px;">‹ Prev</button><button id="nextClass" class="btn btn-sm btn-primary" style="border-radius:10px;">Next ›</button></div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-wrap-fixed"><canvas id="classChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card premium-card">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="padding:20px 24px;">
                                        <h3 class="card-title mb-0 fw-bold"><i class="fe fe-users me-2 text-primary"></i>Gender Distribution Among Students in <?php echo $school_name; ?></h3><span class="badge bg-primary-transparent text-primary">Students</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-wrap-fixed-sm"><canvas id="genderChart"></canvas></div>
                                        <div class="d-flex justify-content-center gap-3 mt-3"><span class="badge bg-primary-transparent text-primary">Male: <?php echo $male_row['male_count']; ?></span><span class="badge bg-success-transparent text-success">Female: <?php echo $female_row['female_count']; ?></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card premium-card">
                                    <div class="card-header" style="padding:20px 24px;">
                                        <h3 class="card-title mb-0 fw-bold"><i class="fe fe-pie-chart me-2 text-warning"></i>School Population</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-wrap-fixed-sm"><canvas id="popChart2"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer"><?php include("footer.php"); ?></footer>
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
            const financeCtx = document.getElementById('financeChart');
            if (financeCtx) {
                new Chart(financeCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Expected', 'Actual', 'Outstanding'],
                        datasets: [{
                            label: 'Amount',
                            data: [<?php echo $total_expected; ?>, <?php echo $total_actual; ?>, <?php echo $outstanding; ?>],
                            backgroundColor: ['#6259ca', '#2dce89', '#ff5b51'],
                            borderRadius: 12,
                            barThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1600,
                            easing: 'easeOutBounce',
                            delay: (c) => c.dataIndex * 200
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.04)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
            const financeCtx_current_session = document.getElementById('financeChart_current_session');
            if (financeCtx_current_session) {
                new Chart(financeCtx_current_session, {
                    type: 'bar',
                    data: {
                        labels: ['Expected', 'Actual', 'Outstanding'],
                        datasets: [{
                            label: 'Amount',
                            data: [<?php echo $total_expected_current_session; ?>, <?php echo $total_actual_current_session; ?>, <?php echo $outstanding_current_session; ?>],
                            backgroundColor: ['#6259ca', '#2dce89', '#ff5b51'],
                            borderRadius: 12,
                            barThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1600,
                            easing: 'easeOutBounce',
                            delay: (c) => c.dataIndex * 200
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.04)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
            const financeCtx_current_term = document.getElementById('financeChart_current_term');
            if (financeCtx_current_term) {
                new Chart(financeCtx_current_term, {
                    type: 'bar',
                    data: {
                        labels: ['Expected', 'Actual', 'Outstanding'],
                        datasets: [{
                            label: 'Amount',
                            data: [<?php echo $total_expected_current_term; ?>, <?php echo $total_actual_current_term; ?>, <?php echo $outstanding_current_term; ?>],
                            backgroundColor: ['#6259ca', '#2dce89', '#ff5b51'],
                            borderRadius: 12,
                            barThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1600,
                            easing: 'easeOutBounce',
                            delay: (c) => c.dataIndex * 200
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.04)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            const classCtx = document.getElementById('classChart');
            let classChartInstance = null;
            let currentPage = 0;
            const perPage = 6;
            const allClassNames = <?php echo json_encode($class_names); ?>;
            const allStudentCounts = <?php echo json_encode($student_counts); ?>;

            function renderClassPage(page) {
                const start = page * perPage;
                const end = start + perPage;
                const pageLabels = allClassNames.slice(start, end);
                const pageData = allStudentCounts.slice(start, end);
                if (classChartInstance) classChartInstance.destroy();
                classChartInstance = new Chart(classCtx, {
                    type: 'bar',
                    data: {
                        labels: pageLabels,
                        datasets: [{
                            label: 'Number of Students',
                            data: pageData,
                            backgroundColor: '#2dce89',
                            borderRadius: 12,
                            barThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1200,
                            easing: 'easeOutBounce',
                            delay: (c) => c.dataIndex * 120
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.05)'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                document.getElementById('classPageInfo').innerText = `Page ${page + 1} of ${Math.ceil(allClassNames.length / perPage) || 1} — ${allClassNames.length} classes total`;
                document.getElementById('prevClass').disabled = page === 0;
                document.getElementById('nextClass').disabled = end >= allClassNames.length;
            }
            if (classCtx) {
                renderClassPage(0);
                document.getElementById('nextClass').addEventListener('click', () => {
                    if ((currentPage + 1) * perPage < allClassNames.length) {
                        currentPage++;
                        renderClassPage(currentPage);
                    }
                });
                document.getElementById('prevClass').addEventListener('click', () => {
                    if (currentPage > 0) {
                        currentPage--;
                        renderClassPage(currentPage);
                    }
                });
            }

            // === FIXED - OVER YEARS ===
            const financeCtx_over_class = document.getElementById('financeChart_over_class');
            let finance_over_classChartInstance = null;
            let finance_over_currentPage = 0;
            const finance_over_perPage = 6;
            const financeOverClassNames = <?php echo json_encode($rev_classes_over); ?>;
            const financeOverClassTotals = <?php echo json_encode($rev_totals_over); ?>;

            function renderFinanceOverClassPage(page) {
                const start = page * finance_over_perPage;
                const end = start + finance_over_perPage;
                const pageLabels = financeOverClassNames.slice(start, end);
                const pageData = financeOverClassTotals.slice(start, end);
                if (finance_over_classChartInstance) finance_over_classChartInstance.destroy();
                finance_over_classChartInstance = new Chart(financeCtx_over_class, {
                    type: 'bar',
                    data: {
                        labels: pageLabels,
                        datasets: [{
                            label: 'Total Revenue',
                            data: pageData,
                            backgroundColor: '#2dce89',
                            borderRadius: 12,
                            barThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1200,
                            easing: 'easeOutBounce',
                            delay: (c) => c.dataIndex * 120
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.05)'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                document.getElementById('finance_over_ClassPageInfo').innerText = `Page ${page + 1} of ${Math.ceil(financeOverClassNames.length / finance_over_perPage) || 1} — ${financeOverClassNames.length} classes total`;
                document.getElementById('prevFinance_over_Class').disabled = page === 0;
                document.getElementById('nextFinance_over_Class').disabled = end >= financeOverClassNames.length;
            }
            if (financeCtx_over_class) {
                renderFinanceOverClassPage(0);
                document.getElementById('nextFinance_over_Class').addEventListener('click', () => {
                    if ((finance_over_currentPage + 1) * finance_over_perPage < financeOverClassNames.length) {
                        finance_over_currentPage++;
                        renderFinanceOverClassPage(finance_over_currentPage);
                    }
                });
                document.getElementById('prevFinance_over_Class').addEventListener('click', () => {
                    if (finance_over_currentPage > 0) {
                        finance_over_currentPage--;
                        renderFinanceOverClassPage(finance_over_currentPage);
                    }
                });
            }

            // === FIXED - CURRENT TERM - THIS WAS MISSING ===
            const financeCtx_class = document.getElementById('financeChart_class');
            let finance_classChartInstance = null;
            let finance_currentPage = 0;
            const finance_perPage = 6;
            const financeClassNames = <?php echo json_encode($rev_classes_current); ?>;
            const financeClassTotals = <?php echo json_encode($rev_totals_current); ?>;

            function renderFinanceClassPage(page) {
                const start = page * finance_perPage;
                const end = start + finance_perPage;
                const pageLabels = financeClassNames.slice(start, end);
                const pageData = financeClassTotals.slice(start, end);
                if (finance_classChartInstance) finance_classChartInstance.destroy();
                finance_classChartInstance = new Chart(financeCtx_class, {
                    type: 'bar',
                    data: {
                        labels: pageLabels,
                        datasets: [{
                            label: 'Total Revenue',
                            data: pageData,
                            backgroundColor: '#6259ca',
                            borderRadius: 12,
                            barThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1200,
                            easing: 'easeOutBounce',
                            delay: (c) => c.dataIndex * 120
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.05)'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                document.getElementById('financeClassPageInfo').innerText = `Page ${page + 1} of ${Math.ceil(financeClassNames.length / finance_perPage) || 1} — ${financeClassNames.length} classes total`;
                document.getElementById('prevFinanceClass').disabled = page === 0;
                document.getElementById('nextFinanceClass').disabled = end >= financeClassNames.length;
            }
            if (financeCtx_class) {
                renderFinanceClassPage(0);
                document.getElementById('nextFinanceClass').addEventListener('click', () => {
                    if ((finance_currentPage + 1) * finance_perPage < financeClassNames.length) {
                        finance_currentPage++;
                        renderFinanceClassPage(finance_currentPage);
                    }
                });
                document.getElementById('prevFinanceClass').addEventListener('click', () => {
                    if (finance_currentPage > 0) {
                        finance_currentPage--;
                        renderFinanceClassPage(finance_currentPage);
                    }
                });
            }

            const genderChart = document.getElementById('genderChart');
            if (genderChart) {
                new Chart(genderChart, {
                    type: 'doughnut',
                    data: {
                        labels: ['Male', 'Female'],
                        datasets: [{
                            data: [<?php echo (int)$male_row['male_count']; ?>, <?php echo (int)$female_row['female_count']; ?>],
                            backgroundColor: ['#6259ca', '#ff6b9d'],
                            borderWidth: 0,
                            hoverOffset: 12
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
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
                                    padding: 16
                                }
                            }
                        }
                    }
                });
            }
            const popCtx = document.getElementById('popChart');
            if (popCtx) {
                new Chart(popCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Students', 'Parents', 'Teaching', 'Non-Teaching'],
                        datasets: [{
                            data: [<?php echo $total_students; ?>, <?php echo $total_parents; ?>, <?php echo $total_teaching; ?>, <?php echo $total_nonteaching; ?>],
                            backgroundColor: ['#3b82f6', '#8b5cf6', '#14b8a6', '#f97316'],
                            borderWidth: 0,
                            hoverOffset: 18
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        animation: {
                            duration: 1800,
                            easing: 'easeOutBounce',
                            animateRotate: true,
                            animateScale: true
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 16
                                }
                            }
                        }
                    }
                });
            }
            const popCtx2 = document.getElementById('popChart2');
            if (popCtx2) {
                new Chart(popCtx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Students', 'Parents', 'Teaching', 'Non-Teaching'],
                        datasets: [{
                            data: [<?php echo $total_students; ?>, <?php echo $total_parents; ?>, <?php echo $total_teaching; ?>, <?php echo $total_nonteaching; ?>],
                            backgroundColor: ['#3b82f6', '#8b5cf6', '#14b8a6', '#f97316'],
                            borderWidth: 0,
                            hoverOffset: 18
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        animation: {
                            duration: 1800,
                            easing: 'easeOutBounce'
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 16
                                }
                                }
                            }
                        }
                    });
                }
                $(document).ready(function() {
                    $('#data-table').DataTable({
                        pageLength: 10
                });
            });
        </script>
</body>

</html>


<!-- 1. STUDENT REPORTS
New Admission Report - filter by session + term + class ..done
Students by Class - how many for JSS1, SS2 etc ..done
Gender Report - Male vs Female per class  ..done

Age Report - underage list
Withdrawn / Graduated / Suspended students

2. FINANCE REPORTS - na here money dey
Debtors List - who never pay complete (this one important for principal) ..done
Fully Paid List - who don clear ..done
Overdue / Excess payment ..done



Daily / Weekly / Monthly income report

Per Session + Term comparison - 2024/2025 vs 2025/2026

Expense report - where money go
3. PARENTS REPORT


Parents contact list - for bulk SMS
4. STAFF REPORT

Staff by department / subject
Staff salary report (if you add am later) ..later
5. ACADEMIC / CLASS REPORT
Class population trend - e dey increase or decrease? ..later
Subjects per class
Promotion report - who go move from JSS1 to JSS2 ..later
6. CBT / EXAM REPORT
Exam participation per class  ..later
Average score per subject  ..later
Average score per class  ..later --> 