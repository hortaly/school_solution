<?php
include('session.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM parents WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>
<?php
ob_start();

include('db_conn.php');

$parentUin = mysqli_real_escape_string($conn, $session_uin);
$studentUin = mysqli_real_escape_string($conn, $_GET['student_uin'] ?? '');
$examUin = mysqli_real_escape_string($conn, $_GET['exam_uin'] ?? '');
$attemptUin = mysqli_real_escape_string($conn, $_GET['attempt_uin'] ?? '');

$studentQuery = mysqli_query($conn, "SELECT * FROM students WHERE student_uin='$studentUin' AND parent_uin='$parentUin' LIMIT 1");
$studentData = mysqli_fetch_assoc($studentQuery);
$examQuery = mysqli_query($conn, "SELECT * FROM cbt_exams WHERE exam_uin='$examUin' LIMIT 1");
$examData = mysqli_fetch_assoc($examQuery);

$MARKS_PER_QUESTION = 2;
$QUESTIONS_PER_SUBJECT = 50;

$scoreQuery = mysqli_query($conn, "SELECT q.correct_option, a.selected_option, q.subject_name FROM cbt_student_answers a JOIN cbt_questions q ON q.question_uin=a.question_uin WHERE a.attempt_uin='$attemptUin'");

$totalAnswered = 0;
$correct = 0;
$bySubjectAnswered = [];
$subjectsTouched = [];

while ($row = mysqli_fetch_assoc($scoreQuery)) {
    $totalAnswered++;
    $sub = $row['subject_name'];
    $subjectsTouched[$sub] = 1;
    if ($row['correct_option'] == $row['selected_option']) $correct++;
    if(!isset($bySubjectAnswered[$sub])){
        $bySubjectAnswered[$sub] = ['correct'=>0, 'answered'=>0];
    }
    $bySubjectAnswered[$sub]['answered']++;
    if ($row['correct_option'] == $row['selected_option']) {
        $bySubjectAnswered[$sub]['correct']++;
    }
}

$sessionKey = 'practice_subjects_' . ($_GET['attempt_uin'] ?? $attemptUin);
$selectedSubjects = $_SESSION[$sessionKey] ?? [];
$subjectCount = !empty($selectedSubjects) ? count($selectedSubjects) : (count($subjectsTouched) > 0 ? count($subjectsTouched) : 1);
$totalExpected = $subjectCount * $QUESTIONS_PER_SUBJECT;
if ($totalExpected <= 0) $totalExpected = $totalAnswered > 0 ? $totalAnswered : $QUESTIONS_PER_SUBJECT;

$totalMarksExpected = $totalExpected * $MARKS_PER_QUESTION;
$marksScored = $correct * $MARKS_PER_QUESTION;
$percentage = $totalExpected > 0 ? round($correct / $totalExpected * 100, 2) : 0;

$bySubject = [];
foreach ($bySubjectAnswered as $sub => $vals) {
    $bySubject[$sub]['total_q'] = $QUESTIONS_PER_SUBJECT;
    $bySubject[$sub]['total_marks'] = $QUESTIONS_PER_SUBJECT * $MARKS_PER_QUESTION;
    $bySubject[$sub]['correct'] = $vals['correct'];
    $bySubject[$sub]['correct_marks'] = $vals['correct'] * $MARKS_PER_QUESTION;
    $bySubject[$sub]['answered'] = $vals['answered'];
}
if (!empty($selectedSubjects)) {
    foreach ($selectedSubjects as $subUin) {
        $subUinSafe = mysqli_real_escape_string($conn, $subUin);
        $subQ = mysqli_query($conn, "SELECT subject_name FROM cbt_questions WHERE subject_uin='$subUinSafe' LIMIT 1");
        $subRow = $subQ ? mysqli_fetch_assoc($subQ) : null;
        $subName = $subRow['subject_name'] ?? $subUin;
        if (!isset($bySubject[$subName])) {
            $bySubject[$subName]['total_q'] = $QUESTIONS_PER_SUBJECT;
            $bySubject[$subName]['total_marks'] = $QUESTIONS_PER_SUBJECT * $MARKS_PER_QUESTION;
            $bySubject[$subName]['correct'] = 0;
            $bySubject[$subName]['correct_marks'] = 0;
            $bySubject[$subName]['answered'] = 0;
        }
    }
}

$now = date("Y-m-d H:i:s");
mysqli_query($conn, "UPDATE cbt_student_attempts SET score='$correct', end_time='$now', status='submitted' WHERE attempt_uin='$attemptUin'");

if($percentage >= 70) { $grade = "Excellent"; $gradeColor = "#22c55e"; }
elseif($percentage >= 50) { $grade = "Good"; $gradeColor = "#a78bfa"; }
elseif($percentage >= 40) { $grade = "Pass"; $gradeColor = "#fb923c"; }
else { $grade = "Needs Improvement"; $gradeColor = "#ef4444"; }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Result - <?php echo $studentData['student_name']; ?></title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <style>
        :root{
            --bg-light:#f6f7fb;
            --card-light:#ffffff;
            --border-light:rgba(0,0,0,.06);
            --text-muted-light:#6c757d;
        }
        body{ background:var(--bg-light); transition: background .3s; }

        .result-hero{
            background: linear-gradient(135deg,#5b50d6 0%,#7c6ef0 50%,#a78bfa 100%);
            border-radius:24px;
            padding:28px;
            color:#fff;
            position:relative;
            overflow:hidden;
            box-shadow:0 20px 50px rgba(98,89,202,.3);
        }
        .result-hero::before, .result-hero::after{
            content:''; position:absolute; border-radius:50%;
        }
        .result-hero::before{ top:-50px; right:-50px; width:200px; height:200px; background:rgba(255,255,255,.15); }
        .result-hero::after{ bottom:-80px; left:-30px; width:300px; height:300px; background:rgba(255,255,255,.08); }
        .score-circle{
            width:130px; height:130px; border-radius:50%;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(12px);
            border:3px solid rgba(255,255,255,.3);
            display:flex; flex-direction:column; align-items:center; justify-content:center; margin:0 auto;
            box-shadow:0 10px 30px rgba(0,0,0,.15);
        }
        .premium-card{
            border-radius:20px!important;
            border:1px solid var(--border-light)!important;
            box-shadow:0 8px 30px rgba(0,0,0,.06);
            background:var(--card-light);
            transition:.3s;
        }
        .premium-card:hover{ transform:translateY(-4px); box-shadow:0 15px 40px rgba(0,0,0,.1); }
        .subject-card{ border-left:4px solid #7c6ef0; }
        .progress-modern{ height:8px; border-radius:10px; background:#eef0f7; overflow:hidden; }
        .progress-bar-modern{ height:100%; border-radius:10px; background: linear-gradient(90deg,#6259ca,#8a7cf5); transition: width 1s ease; }
        .btn-premium{ border-radius:14px; padding:12px 24px; font-weight:700; letter-spacing:.3px; }
        .stat-box{
            background:#fff; border-radius:16px; padding:18px; text-align:center;
            border:1px solid var(--border-light);
            transition:.3s;
        }
        .stat-box h4{ font-weight:800; }

        /* ===== MODERN DARK MODE FIX ===== */
        .dark-mode body, body.dark-mode{ background:#0f0f14!important; }
        .dark-mode .main-content{ background:#0f0f14; }
        .dark-mode .result-hero{
            background: linear-gradient(135deg,#4a3fbf 0%,#6d5ee6 50%,#8b7cf8 100%);
            box-shadow:0 20px 60px rgba(98,89,202,.4);
            border:1px solid rgba(255,255,255,.08);
        }
        .dark-mode .premium-card{
            background: linear-gradient(180deg,#232337 0%,#1c1c2e 100%)!important;
            border-color: rgba(255,255,255,.07)!important;
            box-shadow:0 10px 40px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.05);
            color:#e4e4f0;
        }
        .dark-mode .premium-card:hover{
            box-shadow:0 16px 50px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.07);
            border-color: rgba(139,124,248,.2)!important;
        }
        .dark-mode .stat-box{
            background: linear-gradient(180deg,#26263d 0%,#1f1f33 100%)!important;
            border-color: rgba(255,255,255,.06)!important;
            color:#e4e4f0;
            box-shadow:0 8px 30px rgba(0,0,0,.35);
        }
        .dark-mode .stat-box small, .dark-mode .text-muted{ color:#9a9ab0!important; }
        .dark-mode .progress-modern{ background:#2a2a45; }
        .dark-mode .subject-card{ border-left-color:#8b7cf8; background: linear-gradient(180deg,#26263d 0%,#1f1f33 100%)!important; }
        .dark-mode h5, .dark-mode h6, .dark-mode strong{ color:#f0f0ff!important; }
        .dark-mode .footer{ background:#0f0f14; color:#7a7a95; border-top:1px solid rgba(255,255,255,.06); }
    </style>
</head>
<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="result-hero mb-4">
                    <div class="row align-items-center position-relative" style="z-index:2;">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px; backdrop-filter:blur(10px);">🎓</div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-white"><?php echo $studentData['student_name']; ?></h5>
                                    <small style="opacity:.9;"><?php echo $examData['exam_type']; ?> • <?php echo $subjectCount; ?> Subject<?php echo $subjectCount>1?'s':''; ?> • <?php echo $MARKS_PER_QUESTION; ?> marks / question</small>
                                </div>
                            </div>
                            <h1 class="fw-bold mt-3 mb-1" style="font-size:42px; letter-spacing:-1px;"><?php echo $marksScored; ?> <span style="opacity:.7; font-size:22px;">/ <?php echo $totalMarksExpected; ?></span></h1>
                            <p class="mb-3" style="opacity:.9;"><?php echo $correct; ?> correct out of <?php echo $totalExpected; ?> questions • Answered <?php echo $totalAnswered; ?> / <?php echo $totalExpected; ?></p>
                            <span class="badge" style="background:<?php echo $gradeColor; ?>; padding:8px 18px; border-radius:30px; font-size:13px; box-shadow:0 4px 15px rgba(0,0,0,.2);"><?php echo $grade; ?> • <?php echo $percentage; ?>%</span>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <div class="score-circle">
                                <span style="font-size:36px; font-weight:800;"><?php echo $percentage; ?>%</span>
                                <small style="opacity:.9; font-size:11px; letter-spacing:1.5px; font-weight:700;">SCORE</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-6 col-md-3 mb-3"><div class="stat-box"><h4 class="mb-0"><?php echo $totalExpected; ?></h4><small class="text-muted">Total Questions</small></div></div>
                    <div class="col-6 col-md-3 mb-3"><div class="stat-box"><h4 class="mb-0" style="color:#22c55e;"><?php echo $correct; ?></h4><small class="text-muted">Correct</small></div></div>
                    <div class="col-6 col-md-3 mb-3"><div class="stat-box"><h4 class="mb-0" style="color:#ef4444;"><?php echo $totalExpected - $correct; ?></h4><small class="text-muted">Wrong / Unanswered</small></div></div>
                    <div class="col-6 col-md-3 mb-3"><div class="stat-box"><h4 class="mb-0" style="color:#8b7cf8;"><?php echo $marksScored; ?></h4><small class="text-muted">Marks (<?php echo $totalMarksExpected; ?> max)</small></div></div>
                </div>

                <h5 class="fw-bold mb-3">Subject Breakdown</h5>
                <div class="row">
                <?php foreach ($bySubject as $sub => $vals) {
                    $subPer = $vals['total_q'] > 0 ? round($vals['correct'] / $vals['total_q'] * 100, 1) : 0;
                    $barColor = $subPer >= 70 ? "#22c55e" : ($subPer >= 50 ? "#8b7cf8" : ($subPer >= 40 ? "#fb923c" : "#ef4444"));
                    echo "
                    <div class='col-md-6 col-lg-3 mb-3'>
                        <div class='card premium-card subject-card h-100'>
                            <div class='card-body'>
                                <div class='d-flex justify-content-between align-items-center mb-3'>
                                    <h6 class='fw-bold mb-0'>$sub</h6>
                                    <span class='badge' style='background:{$barColor}; color:#fff; border-radius:20px; padding:5px 12px; font-weight:700;'>{$subPer}%</span>
                                </div>
                                <div class='d-flex justify-content-between mb-1'><small class='text-muted'>Questions</small><strong>{$vals['correct']} / {$vals['total_q']}</strong></div>
                                <div class='d-flex justify-content-between mb-3'><small class='text-muted'>Marks</small><strong style='color:#8b7cf8;'>{$vals['correct_marks']} / {$vals['total_marks']}</strong></div>
                                <div class='progress-modern mb-2'><div class='progress-bar-modern' style='width:{$subPer}%; background:{$barColor};'></div></div>
                                <small class='text-muted'>{$vals['answered']} answered • {$vals['total_q']} total</small>
                            </div>
                        </div>
                    </div>";
                } ?>
                </div>

                <div class="card premium-card mt-2">
                    <div class="card-body d-flex flex-wrap gap-2 justify-content-center">
                        <a href="cbt_available.php?student_uin=<?php echo urlencode($_GET['student_uin']); ?>" class="btn btn-primary btn-premium" style="background:linear-gradient(135deg,#6259ca,#8a7cf5); border:none;"><i class="fe fe-refresh-cw me-1"></i> Retake Practice</a>
                        <a href="cbt_review.php?attempt_uin=<?php echo urlencode($_GET['attempt_uin']); ?>&student_uin=<?php echo urlencode($_GET['student_uin']); ?>&exam_uin=<?php echo urlencode($_GET['exam_uin']); ?>" class="btn btn-outline-primary btn-premium"><i class="fe fe-eye me-1"></i> Review Answers</a>
                        <a href="index.php" class="btn btn-light btn-premium" style="box-shadow:none;">Dashboard</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer"><div class="container"><div class="row"><div class="col-md-12 text-center">Copyright © <span id="year"></span> Sash • <?php echo $marksScored; ?>/<?php echo $totalMarksExpected; ?> marks</div></div></div></footer>

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
</body>
</html>