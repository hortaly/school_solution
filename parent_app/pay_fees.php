<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM parents WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>
<?php

include('db_conn.php');

$student_photo_path = "../student_log/student_photo/"; // same folder as the dashboard

// 1. Only the logged-in parent's own wards, each with their fees from the ledger
$parent_uin = mysqli_real_escape_string($conn, $session_uin);
$wards_query = mysqli_query($conn, "SELECT student_uin, student_name, student_class, student_photo
    FROM students WHERE parent_uin = '$parent_uin' ORDER BY student_name ASC") or die(mysqli_error($conn));

$wards = array();
while ($ward = mysqli_fetch_assoc($wards_query)) {
    $student_uin = mysqli_real_escape_string($conn, $ward['student_uin']);

    // Each fee charged (Debit) and how much has been paid on it (Credit)
    $ledger_query = mysqli_query($conn, "SELECT l.fee_id, l.amount, f.fee_name, f.fee_term, f.fee_session,
            (SELECT COALESCE(SUM(c.amount), 0) FROM ledger c
                WHERE c.student_uin = l.student_uin AND c.fee_id = l.fee_id AND c.entry_type = 'Credit') AS total_paid
        FROM ledger l
        JOIN fees f ON l.fee_id = f.fee_id
        WHERE l.student_uin = '$student_uin' AND l.entry_type = 'Debit'
        ORDER BY l.date DESC") or die(mysqli_error($conn));

    $ward['fees'] = array();
    $ward['total_charged'] = 0;
    $ward['total_paid'] = 0;
    while ($fee = mysqli_fetch_assoc($ledger_query)) {
        $fee['balance'] = $fee['amount'] - $fee['total_paid'];
        $ward['total_charged'] += $fee['amount'];
        $ward['total_paid'] += $fee['total_paid'];
        $ward['fees'][] = $fee;
    }
    $ward['total_balance'] = max($ward['total_charged'] - $ward['total_paid'], 0);

    $wards[] = $ward;
}

// 2. Which ward opens first (must be one of this parent's own children)
$selected_index = 0;
if (isset($_REQUEST['student_uin'])) {
    foreach ($wards as $ward_index => $ward) {
        if ($ward['student_uin'] == $_REQUEST['student_uin']) {
            $selected_index = $ward_index;
        }
    }
}
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pay Fees || Josh College</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
    <style>
        :root { --premium-radius: 18px; --premium-shadow: 0 10px 35px rgba(0, 0, 0, .06); }
        .premium-card { border-radius: var(--premium-radius) !important; border: 1px solid rgba(0, 0, 0, .06) !important; box-shadow: var(--premium-shadow); overflow: hidden; }
        .page-header-premium { background: linear-gradient(135deg, rgba(98, 89, 202, .10) 0%, rgba(255, 255, 255, 0) 100%); border: 1px solid rgba(98, 89, 202, .14); border-radius: 16px; padding: 18px 22px; margin-bottom: 24px; display: flex; align-items: center; gap: 14px; }
        .greet-icon { width: 46px; height: 46px; border-radius: 12px; display: grid; place-items: center; background: linear-gradient(135deg, #6259ca, #8a7cf5); color: #fff; font-size: 22px; }

        .ward-chip { display: flex; align-items: center; gap: 12px; padding: 10px 18px 10px 10px; border-radius: 14px; border: 1.5px solid rgba(0, 0, 0, .08); background: transparent; cursor: pointer; text-align: left; color: inherit; transition: border-color .2s, background .2s; }
        .dark-mode .ward-chip { border-color: rgba(255, 255, 255, .1); }
        .ward-chip:hover { border-color: rgba(98, 89, 202, .5); }
        .ward-chip.active { border-color: #6259ca; background: rgba(98, 89, 202, .08); }
        .ward-chip:focus-visible { outline: 3px solid rgba(98, 89, 202, .35); outline-offset: 2px; }
        .ward-chip .name { font-weight: 700; font-size: 14px; line-height: 1.2; display: block; }
        .ward-chip .cls { font-size: 12px; opacity: .65; }
        .initials { position: relative; overflow: hidden; width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; font-weight: 800; font-size: 15px; color: #fff; background: #6259ca; flex-shrink: 0; }
        .initials img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center top; }

        .action-card { border-radius: var(--premium-radius) !important; border: none !important; position: relative; overflow: hidden; }
        .action-card .card-body { padding: 22px !important; }
        .action-card .bg-pattern { position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; background: rgba(255, 255, 255, .15); border-radius: 50%; }
        .action-card .kpi-value { font-weight: 800; font-size: 24px; letter-spacing: -.3px; }
        .action-card .icon-wrap { width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 255, 255, .2); display: grid; place-items: center; }

        .table-premium thead th { font-size: 11px; letter-spacing: .8px; text-transform: uppercase; font-weight: 700; background: rgba(0, 0, 0, .02) !important; color: #6b7280; }
        .table-premium tbody td { vertical-align: middle; font-weight: 500; font-size: 13.5px; }
        .badge { border-radius: 100px; padding: 6px 12px; font-weight: 700; font-size: 11px; }
        .btn-premium-sm { border-radius: 10px; padding: 7px 14px; font-weight: 700; font-size: 12.5px; box-shadow: 0 4px 12px rgba(0, 0, 0, .08); }
        .progress-thin { height: 6px; border-radius: 100px; background: rgba(0, 0, 0, .06); }
        .ward-panel[hidden] { display: none; }
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header-premium">
                    <div class="greet-icon"><i class="fe fe-credit-card"></i></div>
                    <div>
                        <h1 class="page-title mb-0" style="font-weight:800; font-size:18px;">Pay Fees</h1>
                        <small class="text-muted">Pick a ward, then pay any fee in full or in parts.</small>
                    </div>
                    <div class="ms-auto"><a href="index.php" class="btn btn-light btn-sm" style="border-radius:10px;"><i class="fe fe-arrow-left me-1"></i> Dashboard</a></div>
                </div>

                <?php if (count($wards) == 0) { ?>
                    <div class="alert alert-warning" style="border-radius:14px;"><i class="fe fe-alert-triangle me-2"></i>No wards are linked to this account yet. Please contact the school office.</div>
                <?php } else { ?>

                <!-- WARD SWITCHER (switches instantly, no page reload) -->
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach ($wards as $ward_index => $ward) { ?>
                                <button type="button" class="ward-chip" data-uin="<?php echo htmlspecialchars($ward['student_uin']); ?>">
                                    <span class="initials"><?php echo strtoupper(substr($ward['student_name'], 0, 1)); ?>
                                        <?php if ($ward['student_photo']) { ?>
                                            <img src="<?php echo htmlspecialchars($student_photo_path . $ward['student_photo']); ?>" alt="" onerror="this.remove()">
                                        <?php } ?>
                                    </span>
                                    <span>
                                        <span class="name"><?php echo htmlspecialchars($ward['student_name']); ?></span>
                                        <span class="cls"><?php echo htmlspecialchars($ward['student_class']); ?></span>
                                    </span>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php foreach ($wards as $ward_index => $ward) { ?>
                <!-- ONE PANEL PER WARD -->
                <div class="ward-panel" <?php echo ($ward_index == $selected_index) ? '' : 'hidden'; ?>>

                    <!-- KPI CARDS -->
                    <div class="row">
                        <div class="col-sm-6 col-xl-4">
                            <div class="card bg-primary action-card box-primary-shadow"><div class="bg-pattern"></div>
                                <div class="card-body"><div class="d-flex align-items-center">
                                    <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Total charged</p><div class="kpi-value text-white">₦<?php echo number_format($ward['total_charged'], 2); ?></div></div>
                                    <div class="ms-auto icon-wrap"><i class="fe fe-file-text text-white fs-20"></i></div>
                                </div></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-4">
                            <div class="card bg-success action-card box-success-shadow"><div class="bg-pattern"></div>
                                <div class="card-body"><div class="d-flex align-items-center">
                                    <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Total paid</p><div class="kpi-value text-white">₦<?php echo number_format($ward['total_paid'], 2); ?></div></div>
                                    <div class="ms-auto icon-wrap"><i class="fe fe-check-circle text-white fs-20"></i></div>
                                </div></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <div class="card bg-danger action-card box-danger-shadow"><div class="bg-pattern"></div>
                                <div class="card-body"><div class="d-flex align-items-center">
                                    <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Outstanding balance</p><div class="kpi-value text-white">₦<?php echo number_format($ward['total_balance'], 2); ?></div></div>
                                    <div class="ms-auto icon-wrap"><i class="fe fe-alert-circle text-white fs-20"></i></div>
                                </div></div>
                            </div>
                        </div>
                    </div>

                    <!-- LEDGER -->
                    <div class="card premium-card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2" style="padding:18px 24px;">
                            <div class="card-title mb-0 fw-bold"><i class="fe fe-book-open me-2 text-primary"></i>Fees for <?php echo htmlspecialchars($ward['student_name']); ?></div>
                            <span class="badge bg-primary-transparent text-primary"><?php echo htmlspecialchars($ward['student_class']); ?></span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap mb-0 table-premium">
                                    <thead>
                                        <tr><th>#</th><th>Fee</th><th>Term</th><th>Session</th><th>Charged</th><th>Paid</th><th>Balance</th><th>Status</th><th>Action</th></tr>
                                    </thead>
                                    <tbody>
                                    <?php if (count($ward['fees']) > 0) { $fee_number = 1; foreach ($ward['fees'] as $fee) {
                                        $paid_percent = $fee['amount'] > 0 ? ($fee['total_paid'] / $fee['amount']) * 100 : 0;
                                        if ($fee['balance'] <= 0) { $status_badge = '<span class="badge bg-success-transparent text-success">Paid</span>'; }
                                        elseif ($fee['total_paid'] > 0) { $status_badge = '<span class="badge bg-warning-transparent text-warning">Part payment</span>'; }
                                        else { $status_badge = '<span class="badge bg-danger-transparent text-danger">Not paid</span>'; }
                                    ?>
                                        <tr>
                                            <td><span class="badge bg-light text-dark"><?php echo $fee_number; ?></span></td>
                                            <td style="font-weight:700;"><?php echo htmlspecialchars($fee['fee_name']); ?></td>
                                            <td><?php echo htmlspecialchars($fee['fee_term']); ?></td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($fee['fee_session']); ?></small></td>
                                            <td>₦<?php echo number_format($fee['amount'], 2); ?></td>
                                            <td class="text-success">₦<?php echo number_format($fee['total_paid'], 2); ?></td>
                                            <td>
                                                <span class="<?php echo $fee['balance'] > 0 ? 'text-danger' : 'text-success'; ?> fw-bold">₦<?php echo number_format($fee['balance'], 2); ?></span>
                                                <div class="progress-thin mt-1"><div class="progress-bar bg-success" style="width:<?php echo min($paid_percent, 100); ?>%"></div></div>
                                            </td>
                                            <td><?php echo $status_badge; ?></td>
                                            <td>
                                                <?php if ($fee['balance'] > 0) { ?>
                                                    <a href="make_payment.php?student_uin=<?php echo urlencode($ward['student_uin']); ?>&fee_id=<?php echo urlencode($fee['fee_id']); ?>" class="btn btn-sm btn-danger btn-premium-sm"><i class="fe fe-credit-card me-1"></i> Pay now</a>
                                                <?php } else { ?>
                                                    <a href="print_receipt.php?student_uin=<?php echo urlencode($ward['student_uin']); ?>&fee_id=<?php echo urlencode($fee['fee_id']); ?>" class="btn btn-sm btn-success-light btn-premium-sm"><i class="fe fe-printer me-1"></i> Receipt</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php $fee_number++; } } else { ?>
                                        <tr><td colspan="9" class="text-center text-muted py-4"><i class="fe fe-inbox fs-24 d-block mb-2"></i>No fees have been assigned to this ward yet</td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <?php } ?>

                <?php } ?>

            </div>
        </div>
    </div>

    <footer class="footer"><div class="container"><div class="row align-items-center flex-row-reverse"><div class="col-md-12 col-sm-12 text-center">Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights reserved.</div></div></div></footer>
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/sticky.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/js/themeColors.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/custom-swicher.js"></script>
    <script src="assets/switcher/js/switcher.js"></script>

    <script>
        // Switch wards instantly: every ward's fees are already on the page, so we just show one panel at a time
        var wardChips = document.querySelectorAll(".ward-chip");
        var wardPanels = document.querySelectorAll(".ward-panel");

        function showWard(wardIndex) {
            wardChips.forEach(function (chip, chipIndex) {
                chip.classList.toggle("active", chipIndex === wardIndex);
                chip.setAttribute("aria-pressed", chipIndex === wardIndex);
            });
            wardPanels.forEach(function (panel, panelIndex) {
                panel.hidden = (panelIndex !== wardIndex);
            });

            // Keep the address bar in step so a refresh or a shared link opens the same ward, without reloading
            if (window.history && history.replaceState) {
                var chosenUin = wardChips[wardIndex].getAttribute("data-uin");
                history.replaceState(null, "", window.location.pathname + "?student_uin=" + encodeURIComponent(chosenUin));
            }
        }

        wardChips.forEach(function (chip, chipIndex) {
            chip.addEventListener("click", function () { showWard(chipIndex); });
        });

        if (wardChips.length > 0) {
            showWard(<?php echo (int) $selected_index; ?>);
        }
    </script>
</body>

</html>