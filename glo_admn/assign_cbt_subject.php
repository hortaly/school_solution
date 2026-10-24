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
    <title>Assign CBT Subjects to Class - Premium</title>
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
    /* DEFAULT = LIGHT - this shows first */
    .premium-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: var(--premium-radius) !important;
        overflow: hidden;
        transition: all .3s ease;
        box-shadow: var(--premium-shadow);
    }
    /* DARK only when body has dark-mode */
    .dark-mode .premium-card {
        background: var(--dark-body, #1e1e2d) !important;
        border: 1px solid rgba(255, 255, 255, .06) !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .3) !important;
    }
    .premium-card-header {
        padding: 22px 28px !important;
        border-bottom: 1px solid rgba(0, 0, 0, .06);
    }
    .dark-mode .premium-card-header {
        border-bottom-color: rgba(255, 255, 255, .06) !important;
    }
    .card-title-premium {
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    .card-title-premium .icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #6259ca 0%, #8a7cf5 100%);
        color: #fff;
    }
    .form-control-premium {
        height: 48px !important;
        border-radius: 12px !important;
        font-weight: 500;
        border-width: 1.5px !important;
    }
    .form-control-premium:focus {
        border-color: #6259ca !important;
        box-shadow: 0 0 0 4px rgba(98, 89, 202, .12) !important;
    }
    .subject-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }
    .subject-tile {
        position: relative;
        border: 1.5px solid rgba(0, 0, 0, .08);
        border-radius: 12px;
        padding: 14px 14px 14px 42px;
        cursor: pointer;
        transition: all .2s ease;
        background: #fcfcfd;
    }
    .dark-mode .subject-tile {
        background: rgba(255, 255, 255, .03);
        border-color: rgba(255, 255, 255, .08);
    }
    .subject-tile:hover {
        border-color: #6259ca;
        background: rgba(98, 89, 202, .04);
    }
    .subject-tile.is-checked {
        border-color: #6259ca;
        background: linear-gradient(135deg, rgba(98, 89, 202, .08) 0%, rgba(98, 89, 202, .04) 100%);
    }
    .dark-mode .subject-tile.is-checked {
        background: rgba(98, 89, 202, .15) !important;
    }
    .subject-tile input {
        position: absolute;
        left: 14px;
        top: 16px;
        width: 18px;
        height: 18px;
        accent-color: #6259ca;
    }
    .subject-tile .subj-name { font-weight: 600; font-size: 13.5px; }
    .subject-tile .subj-meta { font-size: 11px; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; }
    .subject-tile .dot { width: 6px; height: 6px; border-radius: 50%; background: #2dce89; display: inline-block; }
    .select-all-bar {
        background: rgba(98, 89, 202, .06);
        border: 1px dashed rgba(98, 89, 202, .25);
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dark-mode .select-all-bar {
        background: rgba(255, 255, 255, .04);
        border-color: rgba(255, 255, 255, .1);
    }
    .btn-premium {
        border-radius: 12px;
        padding: 11px 22px;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(98, 89, 202, .25);
    }
    .step-badge {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        background: #6259ca;
        color: #fff;
        padding: 4px 10px;
        border-radius: 100px;
    }
    .empty-state {
        padding: 48px 20px;
        text-align: center;
        border: 1.5px dashed rgba(0, 0, 0, .12);
        border-radius: 16px;
        background: rgba(0,0,0,.01);
    }
    .dark-mode .empty-state {
        border-color: rgba(255,255,255,.1);
        background: rgba(255,255,255,.02);
    }
</style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php") ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header page-header-premium d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1"><span class="step-badge">CBT</span><span class="text-muted" style="font-size:12px;">Class Configuration</span></div>
                        <h1 class="page-title mb-0">Assign CBT Subjects to Class</h1>
                        <p class="text-muted mt-1 mb-0" style="font-size:13px;">Select a class and sync many CBT subjects - Uncheck to remove</p>
                    </div>
                </div>
                <?php
                // FIX 1: POST first so you can pick another class after assign
                $selected_class_uin = '';
                if (isset($_POST['class_uin']) && !isset($_REQUEST['assign_subjects'])) {
                    $selected_class_uin = trim($_POST['class_uin']);
                } elseif (isset($_GET['class_uin'])) {
                    $selected_class_uin = trim($_GET['class_uin']);
                }

                if (isset($_REQUEST['assign_subjects'])) {
                    $class_uin = trim($_POST['class_uin']);
                    $selected_subjects = isset($_POST['subjects']) ? $_POST['subjects'] : array();
                    if (empty($class_uin)) {
                        echo "<script>alert('Please select a class'); location.href='assign_cbt_subject.php';</script>";
                    } else {
                        $all_subjects = mysqli_query($conn, "SELECT subject_uin, subject_name FROM cbt_subjects");
                        while ($subj_row = mysqli_fetch_assoc($all_subjects)) {
                            $subject_uin = $subj_row['subject_uin'];
                            $subject_name = $subj_row['subject_name'];
                            if (in_array($subject_uin, $selected_subjects)) {
                                $check = mysqli_query($conn, "SELECT * FROM cbt_class_subjects WHERE class_uin = '$class_uin' AND subject_uin = '$subject_uin'");
                                $check_row = mysqli_num_rows($check);
                                if ($check_row == 0) {
                                    // FIX 2: stable ID, not random every time
                                    $class_subject_uin = $class_uin . "/" . $subject_uin;
                                    $sql = "INSERT INTO cbt_class_subjects (class_subject_uin, class_uin, subject_uin, subject_name) VALUES('$class_subject_uin', '$class_uin', '$subject_uin', '$subject_name')";
                                    $result = mysqli_query($conn, $sql);
                                    if (!$result) {
                                        echo "<script>alert('Database Error: " . mysqli_error($conn) . "'); location.href='assign_cbt_subject.php?class_uin=$class_uin';</script>";
                                        exit();
                                    }
                                }
                            } else {
                                $delete = mysqli_query($conn, "DELETE FROM cbt_class_subjects WHERE class_uin = '$class_uin' AND subject_uin = '$subject_uin'");
                                if (!$delete) {
                                    echo "<script>alert('Database Error: " . mysqli_error($conn) . "'); location.href='assign_cbt_subject.php?class_uin=$class_uin';</script>";
                                    exit();
                                }
                            }
                        }
                        echo "<script>alert('CBT Subjects Updated Successfully'); location.href='assign_cbt_subject.php?class_uin=$class_uin';</script>";
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card premium-card">
                            <div class="card-header premium-card-header d-flex justify-content-between align-items-center">
                                <div class="card-title-premium">
                                    <span class="icon-box"><i class="fe fe-book-open"></i></span>
                                    <div>
                                        <div>Assign Many CBT Subjects</div>
                                        <div class="text-muted" style="font-weight:400;font-size:12px;">Your flow: add_cbt_subject.php → assign_cbt_subject.php → set CBT</div>
                                    </div>
                                </div>
                                <?php
                                if (!empty($selected_class_uin)) {
                                    $cnt_q = mysqli_query($conn, "SELECT * FROM cbt_class_subjects WHERE class_uin='$selected_class_uin'");
                                    $cnt = mysqli_num_rows($cnt_q);
                                    echo '<span class="badge bg-success-transparent text-success" style="border-radius:100px;padding:7px 12px;font-weight:700;">' . $cnt . ' Assigned</span>';
                                }
                                ?>
                            </div>
                            <div class="card-body p-4">
                                <!-- FIX 3: action points to file without ?class_uin to allow re-pick -->
                                <form action="assign_cbt_subject.php" method="post" id="classPickerForm">
                                    <div class="row align-items-center mb-4">
                                        <label class="col-md-3 form-label fw-semibold">Select Class <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="class_uin" id="class_uin" class="form-control form-control-premium" required onchange="this.form.submit()">
                                                <option value="">-- Choose a class --</option>
                                                <?php
                                                $classes = mysqli_query($conn, "SELECT class_uin, class FROM `class` ORDER BY class ASC");
                                                while ($class_row = mysqli_fetch_assoc($classes)) {
                                                    $selected = ($class_row['class_uin'] == $selected_class_uin) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $class_row['class_uin']; ?>" <?php echo $selected; ?>><?php echo $class_row['class']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <?php if (!empty($selected_class_uin)) { ?>
                                    <form action="" method="post" id="assignForm">
                                        <input type="hidden" name="class_uin" value="<?php echo $selected_class_uin; ?>">
                                        <div class="select-all-bar mb-4">
                                            <div class="form-check m-0"><input class="form-check-input" type="checkbox" id="select_all_subjects" onclick="toggleAllSubjects(this)"><label class="form-check-label fw-semibold ms-1" for="select_all_subjects">Select All CBT Subjects</label></div>
                                            <small class="text-muted">Uncheck to remove</small>
                                        </div>
                                        <label class="form-label fw-semibold mb-3">CBT Subjects Grid :</label>
                                        <div class="subject-grid">
                                            <?php
                                            $subjects = mysqli_query($conn, "SELECT subject_uin, subject_name FROM cbt_subjects ORDER BY subject_name ASC");
                                            while ($subj_row = mysqli_fetch_assoc($subjects)) {
                                                $already = mysqli_query($conn, "SELECT * FROM cbt_class_subjects WHERE class_uin = '$selected_class_uin' AND subject_uin = '{$subj_row['subject_uin']}'");
                                                $already_assigned = mysqli_num_rows($already);
                                            ?>
                                                <label class="subject-tile <?php if ($already_assigned > 0) {
                                                                                echo 'is-checked';
                                                                            } ?>">
                                                    <input class="form-check-input subject-checkbox" type="checkbox" name="subjects[]" value="<?php echo $subj_row['subject_uin']; ?>" <?php if ($already_assigned > 0) {
                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                        } ?> onchange="this.closest('.subject-tile').classList.toggle('is-checked', this.checked)">
                                                    <div class="subj-name"><?php echo $subj_row['subject_name']; ?></div>
                                                    <?php if ($already_assigned > 0) { ?><div class="subj-meta text-success"><span class="dot"></span> assigned</div><?php } else { ?><div class="subj-meta text-muted">Not assigned</div><?php } ?>
                                                </label>
                                            <?php } ?>
                                        </div>
                                        <div class="d-flex gap-2 mt-5 pt-3 border-top">
                                            <button type="submit" name="assign_subjects" class="btn btn-primary btn-premium" onclick="return confirm('Assign checked CBT subjects to this class?')"><i class="fe fe-save me-1"></i> Save & Sync</button>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="empty-state">
                                        <h6>No class selected</h6>
                                        <p class="text-muted" style="font-size:13px;">Choose a class above to see many CBT subjects grid</p>
                                    </div>
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
    <script>
        function toggleAllSubjects(source) {
            var checkboxes = document.querySelectorAll('.subject-checkbox');
            checkboxes.forEach(function(cb) {
                cb.checked = source.checked;
                cb.closest('.subject-tile').classList.toggle('is-checked', source.checked);
            });
        }
    </script>
</body>

</html>