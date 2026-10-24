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
    <title>Assign Class Teacher - Premium</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
  <style>
    :root { --premium-radius:16px; --premium-shadow:0 8px 30px rgba(0,0,0,.06); }
    .premium-card { background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:var(--premium-radius)!important; overflow:hidden; transition:all .3s ease; box-shadow:var(--premium-shadow); }
    .dark-mode .premium-card { background:var(--dark-body,#1e1e2d)!important; border:1px solid rgba(255,255,255,.06)!important; box-shadow:0 8px 30px rgba(0,0,0,.3)!important; }
    .premium-card-header{ padding:22px 28px!important; border-bottom:1px solid rgba(0,0,0,.06); }
    .dark-mode .premium-card-header{ border-bottom-color:rgba(255,255,255,.06)!important; }
    .card-title-premium{ font-weight:700; font-size:16px; display:flex; align-items:center; gap:10px; margin:0; }
    .card-title-premium .icon-box{ width:36px; height:36px; border-radius:10px; display:grid; place-items:center; background:linear-gradient(135deg,#6259ca 0%,#8a7cf5 100%); color:#fff; }
    .form-control-premium{ height:48px!important; border-radius:12px!important; font-weight:500; border-width:1.5px!important; }
    .form-control-premium:focus{ border-color:#6259ca!important; box-shadow:0 0 0 4px rgba(98,89,202,.12)!important; }
    .dark-mode .form-control-premium{ background:rgba(255,255,255,.06)!important; border-color:rgba(255,255,255,.14)!important; color:#fff!important; }
    .dark-mode .form-control-premium option{ background:#1e1e2d!important; color:#fff!important; }
    .subject-grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; }
    .subject-tile{ position:relative; border:1.5px solid rgba(0,0,0,.08); border-radius:12px; padding:14px 14px 14px 42px; cursor:pointer; transition:all .2s ease; background:#fcfcfd; }
    .dark-mode .subject-tile{ background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.08); }
    .subject-tile:hover{ border-color:#6259ca; background:rgba(98,89,202,.04); }
    .subject-tile.is-checked{ border-color:#6259ca; background:linear-gradient(135deg, rgba(98,89,202,.08) 0%, rgba(98,89,202,.04) 100%); box-shadow:0 0 0 3px rgba(98,89,202,.10); }
    .dark-mode .subject-tile.is-checked{ background:rgba(98,89,202,.15)!important; }
    .subject-tile input{ position:absolute; left:14px; top:16px; width:18px; height:18px; accent-color:#6259ca; }
    .subject-tile .subj-name{ font-weight:600; font-size:13.5px; }
    .subject-tile .subj-meta{ font-size:11px; margin-top:4px; display:inline-flex; align-items:center; gap:4px; }
    .subject-tile .dot{ width:6px; height:6px; border-radius:50%; background:#2dce89; display:inline-block; }
    .select-all-bar{ background:rgba(98,89,202,.06); border:1px dashed rgba(98,89,202,.25); border-radius:12px; padding:12px 16px; display:flex; align-items:center; justify-content:space-between; }
    .dark-mode .select-all-bar{ background:rgba(255,255,255,.04); border-color:rgba(255,255,255,.1); }
    .btn-premium{ border-radius:12px; padding:11px 22px; font-weight:600; box-shadow:0 6px 18px rgba(98,89,202,.25); }
    .step-badge{ font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; background:#6259ca; color:#fff; padding:4px 10px; border-radius:100px; }
    .empty-state{ padding:48px 20px; text-align:center; border:1.5px dashed rgba(0,0,0,.12); border-radius:16px; background:rgba(0,0,0,.01); }
    .dark-mode .empty-state{ border-color:rgba(255,255,255,.1); background:rgba(255,255,255,.02); }
    .dark-mode .btn-light{ background:rgba(255,255,255,.08)!important; border-color:rgba(255,255,255,.12)!important; color:#fff!important; }
</style>
</head>
<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php") ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header page-header-premium d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1"><span class="step-badge">CLASS TEACHER</span><span class="text-muted" style="font-size:12px;">One to One Relation</span></div>
                        <h1 class="page-title mb-0">Assign Class Teacher to Class</h1>
                        <p class="text-muted mt-1 mb-0" style="font-size:13px;">Select a class and assign one class teacher - same grid as CBT subjects</p>
                    </div>
                </div>
                <?php
                $selected_class_uin = '';
                if (isset($_POST['class_uin']) && !isset($_REQUEST['assign_teacher'])) {
                    $selected_class_uin = trim($_POST['class_uin']);
                } elseif (isset($_GET['class_uin'])) {
                    $selected_class_uin = trim(urldecode($_GET['class_uin']));
                }

                if (isset($_REQUEST['assign_teacher'])) {
                    $class_uin = trim($_POST['class_uin']);
                    $staff_uin = trim($_POST['staff_uin']);

                    if (empty($class_uin) || empty($staff_uin)) {
                        echo "<script>alert('Please select class and teacher'); location.href='?class_uin=".urlencode($class_uin)."';</script>";
                    } else {
                        mysqli_query($conn, "START TRANSACTION");
                        $check = mysqli_query($conn, "SELECT class_assigned, staff_name FROM staff WHERE staff_uin='$staff_uin'");
                        $check_row = mysqli_fetch_assoc($check);
                        $old_class_uin = $check_row['class_assigned'];
                        $assigned_staff_name = $check_row['staff_name'];

                        if (!empty($old_class_uin)) {
                            mysqli_query($conn, "UPDATE class SET staff_name=NULL, staff_uin=NULL WHERE class_uin='$old_class_uin'");
                        }
                        $check_class = mysqli_query($conn, "SELECT staff_uin FROM class WHERE class_uin='$class_uin'");
                        $check_class_row = mysqli_fetch_assoc($check_class);
                        $existing_staff_on_class = $check_class_row['staff_uin'];

                        if (!empty($existing_staff_on_class) && $existing_staff_on_class != $staff_uin) {
                            mysqli_query($conn, "UPDATE staff SET class_assigned=NULL WHERE staff_uin='$existing_staff_on_class'");
                        }

                        $r1 = mysqli_query($conn, "UPDATE staff SET class_assigned='$class_uin' WHERE staff_uin='$staff_uin'");
                        $r2 = mysqli_query($conn, "UPDATE class SET staff_uin='$staff_uin', staff_name='$assigned_staff_name' WHERE class_uin='$class_uin'");

                        if ($r1 && $r2) {
                            mysqli_query($conn, "COMMIT");
                            echo "<script>alert('Class Teacher Assigned Successfully'); location.href='?class_uin=".urlencode($class_uin)."';</script>";
                        } else {
                            mysqli_query($conn, "ROLLBACK");
                            echo "<script>alert('Database Error: " . mysqli_error($conn) . "');</script>";
                        }
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header premium-card-header d-flex justify-content-between align-items-center">
                                <div class="card-title-premium">
                                    <span class="icon-box"><i class="fe fe-users"></i></span>
                                    <div><div>Assign Class Teacher</div><div class="text-muted" style="font-weight:400;font-size:12px;">One class = one teacher</div></div>
                                </div>
                                <?php
                                if (!empty($selected_class_uin)) {
                                    $cnt_q = mysqli_query($conn, "SELECT staff_name FROM class WHERE class_uin='$selected_class_uin'");
                                    $cnt_r = mysqli_fetch_assoc($cnt_q);
                                    $label = $cnt_r['staff_name'] ? $cnt_r['staff_name'] : 'No Teacher';
                                    echo '<span class="badge bg-success-transparent text-success" style="border-radius:100px;padding:7px 12px;font-weight:700;">' . $label . '</span>';
                                }
                                ?>
                            </div>
                            <div class="card-body p-4">
                                <form action="" method="post">
                                    <div class="row align-items-center mb-4">
                                        <label class="col-md-3 form-label fw-semibold">Select Class <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="class_uin" class="form-control form-control-premium" required onchange="this.form.submit()">
                                                <option value="">-- Choose a class --</option>
                                                <?php
                                                $classes = mysqli_query($conn, "SELECT class_uin, class, staff_name FROM `class` ORDER BY class ASC");
                                                while ($class_row = mysqli_fetch_assoc($classes)) {
                                                    $selected = ($class_row['class_uin'] == $selected_class_uin) ? 'selected' : '';
                                                    $occ = $class_row['staff_name'] ? " (Occupied: {$class_row['staff_name']})" : " (Free)";
                                                    echo "<option value='{$class_row['class_uin']}' $selected>{$class_row['class']}$occ</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <?php if (!empty($selected_class_uin)) { 
                                    $class_q = mysqli_query($conn, "SELECT * FROM class WHERE class_uin='$selected_class_uin'");
                                    $class_r = mysqli_fetch_assoc($class_q);
                                    $current_teacher_uin = $class_r['staff_uin'];
                                ?>
                                    <form action="" method="post">
                                        <input type="hidden" name="class_uin" value="<?php echo $selected_class_uin; ?>">
                                        <input type="hidden" name="staff_uin" id="selected_staff_uin" value="<?php echo $current_teacher_uin; ?>" required>
                                        <div class="select-all-bar mb-4">
                                            <div class="fw-semibold" style="font-size:13px;">Teachers for <?php echo $class_r['class']; ?></div>
                                            <small class="text-muted">Only one teacher - one to one</small>
                                        </div>
                                        <label class="form-label fw-semibold mb-3">Teachers Grid :</label>
                                        <div class="subject-grid">
                                            <?php
                                            $staff_q = mysqli_query($conn, "SELECT staff_uin, staff_name, class_assigned FROM staff WHERE staff_role='Teaching Staff' ORDER BY staff_name ASC");
                                            while ($st = mysqli_fetch_assoc($staff_q)) {
                                                $is_current = ($st['staff_uin'] == $current_teacher_uin);
                                            ?>
                                                <label class="subject-tile <?php if ($is_current) { echo 'is-checked'; } ?>" onclick="selectOneTeacher(this, '<?php echo $st['staff_uin']; ?>')">
                                                    <input type="checkbox" <?php if ($is_current) { echo 'checked'; } ?> onchange="selectOneTeacher(this.closest('.subject-tile'), '<?php echo $st['staff_uin']; ?>')">
                                                    <div class="subj-name"><?php echo $st['staff_name']; ?></div>
                                                    <div class="subj-meta text-muted"><?php echo $st['staff_uin']; ?></div>
                                                    <?php
                                                    if (!empty($st['class_assigned'])) {
                                                        $cn_q = mysqli_query($conn, "SELECT class FROM class WHERE class_uin='{$st['class_assigned']}'");
                                                        $cn_r = mysqli_fetch_assoc($cn_q);
                                                        $cn = $cn_r['class'] ?? $st['class_assigned'];
                                                        if ($is_current) {
                                                            echo "<div class='subj-meta text-success'><span class='dot'></span> Current - $cn</div>";
                                                        } else {
                                                            echo "<div class='subj-meta text-danger'><span class='dot' style='background:#ff3838;'></span> $cn</div>";
                                                        }
                                                    } else {
                                                        echo "<div class='subj-meta text-success'><span class='dot'></span> Free</div>";
                                                    }
                                                    ?>
                                                </label>
                                            <?php } ?>
                                        </div>
                                        <div class="d-flex gap-2 mt-5 pt-3 border-top">
                                            <button type="submit" name="assign_teacher" class="btn btn-primary btn-premium" onclick="return confirm('Assign this teacher as class teacher?')"><i class="fe fe-save me-1"></i> Save & Assign</button>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="empty-state"><h6>No class selected</h6><p class="text-muted" style="font-size:13px;">Choose a class above to see teachers grid</p></div>
                                <?php } ?>
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
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
    <script>
        function selectOneTeacher(el, staff_uin){
            document.querySelectorAll('.subject-tile').forEach(c => {
                c.classList.remove('is-checked');
                c.querySelector('input[type=checkbox]').checked = false;
            });
            el.classList.add('is-checked');
            el.querySelector('input[type=checkbox]').checked = true;
            document.getElementById('selected_staff_uin').value = staff_uin;
        }
    </script>
</body>
</html>