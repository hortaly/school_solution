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

// Folder where student photos are saved. Change this if yours lives somewhere else.
$student_photo_path = "../student_log/student_photo/";

// All children linked to the logged-in parent, plus their class teacher
$parent_uin = mysqli_real_escape_string($conn, $session_uin);
$wards_query = mysqli_query($conn, "SELECT students.student_uin, students.student_name, students.student_gender,
        students.student_photo, students.student_class, class.staff_name AS teacher
    FROM students
    LEFT JOIN class ON class.class = students.student_class
    WHERE students.parent_uin = '$parent_uin'
    ORDER BY students.student_name ASC") or die(mysqli_error($conn));

$wards = array();
while ($s = mysqli_fetch_assoc($wards_query)) {
    $ledger_uin = mysqli_real_escape_string($conn, $s['student_uin']);

    // Total charged (Debit) and total paid (Credit) for this ward
    $totals_query = mysqli_query($conn, "SELECT
            COALESCE(SUM(CASE WHEN entry_type = 'Debit' THEN amount END), 0) AS total_charged,
            COALESCE(SUM(CASE WHEN entry_type = 'Credit' THEN amount END), 0) AS total_paid
        FROM ledger WHERE student_uin = '$ledger_uin'") or die(mysqli_error($conn));
    $totals = mysqli_fetch_assoc($totals_query);

    // Every payment (Credit) this ward has made, newest first
    $pay_query = mysqli_query($conn, "SELECT l.amount, l.date, l.fee_id, f.fee_name, f.fee_term
        FROM ledger l
        JOIN fees f ON l.fee_id = f.fee_id
        WHERE l.student_uin = '$ledger_uin' AND l.entry_type = 'Credit'
        ORDER BY l.date DESC") or die(mysqli_error($conn));
    $payments = array();
    while ($p = mysqli_fetch_assoc($pay_query)) {
        $ts = strtotime($p['date']);
        $payments[] = array(
            'date'   => $ts ? date("d M Y", $ts) : $p['date'],
            'fee'    => $p['fee_name'],
            'term'   => $p['fee_term'],
            'amount' => (float) $p['amount'],
            'fee_id' => $p['fee_id']
        );
    }

    // Does this ward's class have a CBT exam available right now?
    $class_lookup_query = mysqli_query($conn, "SELECT class_uin FROM class WHERE class = '{$s['student_class']}'");
    $class_lookup_row = mysqli_fetch_assoc($class_lookup_query);
    $has_cbt = false;
    if ($class_lookup_row) {
        $exam_check_query = mysqli_query($conn, "SELECT exam.exam_uin FROM cbt_exams exam
            JOIN cbt_exam_classes exam_class ON exam_class.exam_uin = exam.exam_uin
            WHERE exam_class.class_uin = '{$class_lookup_row['class_uin']}' AND exam.status = 'active'
            LIMIT 1");
        $has_cbt = mysqli_num_rows($exam_check_query) > 0;
    }

    $wards[] = array(
        'uin'      => $s['student_uin'],
        'name'     => $s['student_name'],
        'cls'      => $s['student_class'],
        'gender'   => $s['student_gender'],
        'teacher'  => $s['teacher'] ? $s['teacher'] : 'Not assigned yet',
        'has_cbt'  => $has_cbt,
        'photo'    => $s['student_photo'] ? $student_photo_path . $s['student_photo'] : '',
        'total'    => (float) $totals['total_charged'],
        'paid'     => (float) $totals['total_paid'],
        'payments' => $payments
    );
}

date_default_timezone_set("Africa/Lagos");
$hour = date("H");
if ($hour < 12) {
    $greetings = "Good morning";
    $greetIcon = "fe fe-sunrise";
} elseif ($hour < 17) {
    $greetings = "Good afternoon";
    $greetIcon = "fe fe-sun";
} else {
    $greetings = "Good evening";
    $greetIcon = "fe fe-moon";
}
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Parent Dashboard || Josh College</title>
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
        .dark-mode .premium-card { border-color: rgba(255, 255, 255, .07) !important; }

        .page-header-premium { background: linear-gradient(135deg, rgba(98, 89, 202, .08) 0%, rgba(255, 255, 255, 0) 100%); border: 1px solid rgba(98, 89, 202, .12); border-radius: 16px; padding: 18px 22px; margin-bottom: 24px; }
        .dark-mode .page-header-premium { background: rgba(255, 255, 255, .04); border-color: rgba(255, 255, 255, .08); }
        .page-header-premium .page-title { font-weight: 800; font-size: 18px; letter-spacing: -.3px; line-height: 1.4; }
        .greet-icon { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; background: linear-gradient(135deg, #6259ca, #8a7cf5); color: #fff; font-size: 20px; }

        /* Ward switcher */
        .ward-chip { display: flex; align-items: center; gap: 12px; padding: 10px 18px 10px 10px; border-radius: 14px; border: 1.5px solid rgba(0, 0, 0, .08); background: transparent; cursor: pointer; text-align: left; transition: border-color .2s, background .2s; }
        .dark-mode .ward-chip { border-color: rgba(255, 255, 255, .1); color: inherit; }
        .ward-chip:hover { border-color: rgba(98, 89, 202, .5); }
        .ward-chip.active { border-color: #6259ca; background: rgba(98, 89, 202, .08); }
        .ward-chip:focus-visible { outline: 3px solid rgba(98, 89, 202, .35); outline-offset: 2px; }
        .ward-chip .name { font-weight: 700; font-size: 14px; line-height: 1.2; }
        .ward-chip .cls { font-size: 12px; opacity: .65; }
        .initials { position: relative; overflow: hidden; width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; font-weight: 800; font-size: 15px; color: #fff; background: #6259ca; flex-shrink: 0; }
        .initials img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center top; }
        .initials.lg { width: 84px; height: 84px; font-size: 28px; }

        /* KPI cards */
        .action-card { border-radius: var(--premium-radius) !important; border: none !important; position: relative; overflow: hidden; }
        .action-card .card-body { padding: 22px !important; }
        .action-card .bg-pattern { position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; background: rgba(255, 255, 255, .15); border-radius: 50%; }
        .action-card .kpi-value { font-weight: 800; font-size: 24px; letter-spacing: -.3px; }
        .action-card .icon-wrap { width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 255, 255, .2); display: grid; place-items: center; }

        .table-premium thead th { font-size: 11px; letter-spacing: .8px; text-transform: uppercase; font-weight: 700; background: rgba(0, 0, 0, .02); }
        .dark-mode .table-premium thead th { background: rgba(255, 255, 255, .04); }
        .badge { border-radius: 100px; padding: 6px 12px; font-weight: 700; }
        .btn-premium { border-radius: 12px; padding: 10px 20px; font-weight: 600; box-shadow: 0 6px 18px rgba(98, 89, 202, .25); }
        .profile-line { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed rgba(0, 0, 0, .1); font-size: 14px; }
        .profile-line:last-child { border-bottom: 0; }
        .dark-mode .profile-line { border-color: rgba(255, 255, 255, .1); }
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- GREETING -->
                <div class="page-header-premium d-flex align-items-center gap-3">
                    <div class="greet-icon"><i class="<?php echo $greetIcon; ?>"></i></div>
                    <div>
                        <h1 class="page-title mb-0"><?php echo $greetings; ?>, <?php echo $session_fullname; ?></h1>
                        <small class="text-muted" style="font-size:12.5px;">Pick a ward below to see their class and fees.</small>
                    </div>
                    <div class="ms-auto d-none d-md-block"><span class="text-muted" style="font-size:12px;"><?php echo date("l, d M Y"); ?></span></div>
                </div>

                <!-- WARD SWITCHER -->
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3" id="wardSwitcher"></div>
                    </div>
                </div>

                <!-- KPI CARDS -->
                <div class="row">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-primary action-card box-primary-shadow"><div class="bg-pattern"></div>
                            <div class="card-body"><div class="d-flex align-items-center">
                                <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Total fees charged</p><div class="kpi-value text-white" id="kpiTotal">-</div></div>
                                <div class="ms-auto icon-wrap"><i class="fe fe-file-text text-white fs-20"></i></div>
                            </div></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-success action-card box-success-shadow"><div class="bg-pattern"></div>
                            <div class="card-body"><div class="d-flex align-items-center">
                                <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Amount paid</p><div class="kpi-value text-white" id="kpiPaid">-</div></div>
                                <div class="ms-auto icon-wrap"><i class="fe fe-check-circle text-white fs-20"></i></div>
                            </div></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-danger action-card box-danger-shadow"><div class="bg-pattern"></div>
                            <div class="card-body"><div class="d-flex align-items-center">
                                <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Outstanding balance</p><div class="kpi-value text-white" id="kpiBalance">-</div></div>
                                <div class="ms-auto icon-wrap"><i class="fe fe-alert-circle text-white fs-20"></i></div>
                            </div></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card bg-warning action-card box-warning-shadow"><div class="bg-pattern"></div>
                            <div class="card-body"><div class="d-flex align-items-center">
                                <div class="text-white"><p class="mb-1 op-8" style="font-size:12px;">Payments made</p><div class="kpi-value text-white" id="kpiCount">-</div></div>
                                <div class="ms-auto icon-wrap"><i class="fe fe-credit-card text-white fs-20"></i></div>
                            </div></div>
                        </div>
                    </div>
                </div>

                <!-- PROFILE + PROGRESS -->
                <div class="row align-items-stretch">
                    <div class="col-xl-5 d-flex">
                        <div class="card premium-card w-100">
                            <div class="card-header"><h3 class="card-title fw-bold mb-0"><i class="fe fe-user me-2 text-primary"></i>Ward profile</h3></div>
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="initials lg" id="pInitials"></div>
                                    <div>
                                        <h4 class="mb-1 fw-bold" id="pName"></h4>
                                        <span class="badge bg-primary-transparent text-primary" id="pClass"></span>
                                    </div>
                                </div>
                                <div class="profile-line"><span class="text-muted">Student UIN</span><strong id="pUin"></strong></div>
                                <div class="profile-line"><span class="text-muted">Gender</span><strong id="pGender"></strong></div>
                                <div class="profile-line"><span class="text-muted">Class teacher</span><strong id="pTeacher"></strong></div>
                                <a href="#" id="cbtLink" class="btn btn-premium w-100 mt-3"><i class="fe fe-edit-3 me-2"></i><span id="cbtLinkText">Take CBT</span></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 d-flex">
                        <div class="card premium-card w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title fw-bold mb-0"><i class="fe fe-pie-chart me-2 text-primary"></i>Fee progress</h3>
                                <span class="badge" id="feeBadge"></span>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Paid so far</span><strong id="feePercent"></strong>
                                </div>
                                <div class="progress mb-4" style="height:12px; border-radius:100px;">
                                    <div class="progress-bar bg-success" id="feeBar" role="progressbar" style="width:0%"></div>
                                </div>
                                <p class="text-muted mb-4" id="feeNote"></p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="pay_fees.php" id="payLink" class="btn btn-primary btn-premium"><i class="fe fe-credit-card me-2"></i>Pay fees</a>
                                    <a href="#" class="btn btn-light btn-premium" style="box-shadow:none;"><i class="fe fe-printer me-2"></i>Print term receipt</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PAYMENT HISTORY -->
                <div class="card premium-card">
                    <div class="card-header"><h3 class="card-title fw-bold mb-0"><i class="fe fe-list me-2 text-primary"></i>Payment history</h3></div>
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap mb-0 table-premium">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Fee</th>
                                        <th>Term</th>
                                        <th>Amount</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody id="payRows"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights reserved.</div>
            </div>
        </div>
    </footer>
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
        // The parent's real wards, from the students table
        var wards = <?php echo json_encode($wards, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

        function naira(n) { return "₦" + Number(n).toLocaleString("en-NG", { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
        function esc(t) { var d = document.createElement("div"); d.textContent = (t === null || t === undefined) ? "" : t; return d.innerHTML; }
        function initials(name) { return (name || "?").split(" ").map(function (w) { return w[0]; }).slice(0, 2).join("").toUpperCase(); }
        function setText(id, value) { document.getElementById(id).textContent = value; }

        // Shows the student's photo, and keeps the initials underneath if the photo is missing or fails to load
        function fillAvatar(el, w) {
            el.textContent = initials(w.name);
            if (w.photo) {
                var img = document.createElement("img");
                img.src = w.photo;
                img.alt = w.name;
                img.onerror = function () { img.remove(); };
                el.appendChild(img);
            }
        }

        function showWard(i) {
            var w = wards[i];
            var hasFees = w.total !== null;
            var balance = hasFees ? Math.max(w.total - w.paid, 0) : 0;
            var noCharges = hasFees && w.total === 0;
            var pct = hasFees && w.total ? Math.round(w.paid / w.total * 100) : 0;

            document.querySelectorAll(".ward-chip").forEach(function (chip, idx) {
                chip.classList.toggle("active", idx === i);
                chip.setAttribute("aria-pressed", idx === i);
            });

            setText("kpiTotal", hasFees ? naira(w.total) : "-");
            setText("kpiPaid", hasFees ? naira(w.paid) : "-");
            setText("kpiBalance", hasFees ? naira(balance) : "-");
            setText("kpiCount", hasFees ? w.payments.length : "-");

            fillAvatar(document.getElementById("pInitials"), w);
            setText("pName", w.name);
            setText("pClass", w.cls);
            setText("pUin", w.uin);
            setText("pGender", w.gender);
            setText("pTeacher", w.teacher);
            document.getElementById("payLink").href = "pay_fees.php?student_uin=" + encodeURIComponent(w.uin);

            var cbtButton = document.getElementById("cbtLink");
            if (w.has_cbt) {
                cbtButton.href = "cbt_available.php?student_uin=" + encodeURIComponent(w.uin);
                cbtButton.className = "btn btn-success btn-premium w-100 mt-3";
                document.getElementById("cbtLinkText").textContent = "Take CBT";
            } else {
                cbtButton.href = "javascript:void(0)";
                cbtButton.className = "btn btn-light btn-premium w-100 mt-3 disabled";
                document.getElementById("cbtLinkText").textContent = "No CBT available";
            }

            setText("feePercent", hasFees ? pct + "%" : "-");
            document.getElementById("feeBar").style.width = pct + "%";

            var badge = document.getElementById("feeBadge");
            if (!hasFees || noCharges) { badge.className = "badge bg-light text-muted"; badge.textContent = "No fees yet"; }
            else if (balance === 0) { badge.className = "badge bg-success-transparent text-success"; badge.textContent = "Paid in full"; }
            else if (w.paid > 0) { badge.className = "badge bg-warning-transparent text-warning"; badge.textContent = "Part paid"; }
            else { badge.className = "badge bg-danger-transparent text-danger"; badge.textContent = "Not paid"; }

            setText("feeNote", !hasFees || noCharges
                ? "No fees have been charged to this ward yet."
                : balance === 0 ? "All fees are settled."
                : naira(balance) + " left to pay. You can pay in parts.");

            var rows = document.getElementById("payRows");
            rows.innerHTML = "";
            if (!hasFees || w.payments.length === 0) {
                rows.innerHTML = '<tr><td colspan="5" class="text-center text-muted p-4">No payments to show yet.</td></tr>';
            }
            if (hasFees) {
                w.payments.forEach(function (p) {
                    rows.innerHTML += "<tr><td>" + esc(p.date) + "</td><td class='fw-semibold'>" + esc(p.fee) + "</td><td>" + esc(p.term) + "</td><td class='fw-semibold text-success'>" + naira(p.amount) +
                        "</td><td><a href='print_receipt.php?student_uin=" + encodeURIComponent(w.uin) + "&fee_id=" + encodeURIComponent(p.fee_id) + "' class='btn btn-sm btn-outline-primary'><i class='fe fe-printer me-1'></i>Print</a></td></tr>";
                });
            }
        }

        (function buildSwitcher() {
            var box = document.getElementById("wardSwitcher");

            if (wards.length === 0) {
                box.innerHTML = '<p class="text-muted mb-0">No wards are linked to this account yet. Please contact the school office.</p>';
                return;
            }

            wards.forEach(function (w, i) {
                var b = document.createElement("button");
                b.type = "button";
                b.className = "ward-chip";

                var av = document.createElement("span");
                av.className = "initials";
                fillAvatar(av, w);

                var text = document.createElement("span");
                var nm = document.createElement("span");
                nm.className = "name d-block";
                nm.textContent = w.name;
                var cl = document.createElement("span");
                cl.className = "cls";
                cl.textContent = w.cls;
                text.appendChild(nm);
                text.appendChild(cl);

                b.appendChild(av);
                b.appendChild(text);
                b.addEventListener("click", function () { showWard(i); });
                box.appendChild(b);
            });
            showWard(0);
        })();
    </script>
</body>

</html>