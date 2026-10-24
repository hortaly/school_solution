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

// Get all answers for this attempt
$reviewQuery = mysqli_query($conn, "
    SELECT q.*, a.selected_option 
    FROM cbt_student_answers a 
    JOIN cbt_questions q ON q.question_uin=a.question_uin 
    WHERE a.attempt_uin='$attemptUin' 
    ORDER BY q.subject_name, q.id
");

$allReview = [];
$bySubject = [];
$correctCount = 0;
while($row = mysqli_fetch_assoc($reviewQuery)){
    $isCorrect = $row['correct_option'] == $row['selected_option'];
    if($isCorrect) $correctCount++;
    $allReview[] = $row;
    $bySubject[$row['subject_name']][] = $row;
}
$totalAnswered = count($allReview);

// Also get subjects from session to show unanswered count
$sessionKey = 'practice_subjects_' . ($_GET['attempt_uin'] ?? $attemptUin);
$selectedSubjects = $_SESSION[$sessionKey] ?? [];
$subjectCount = !empty($selectedSubjects) ? count($selectedSubjects) : count($bySubject);
$totalExpected = $subjectCount * 50;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Review - <?php echo $studentData['student_name']; ?></title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <style>
        body{ background:#f6f7fb; }
        .premium-card{
            border-radius:20px!important;
            border:1px solid rgba(0,0,0,.05)!important;
            box-shadow:0 8px 30px rgba(0,0,0,.06);
            background:#fff;
            transition:.3s;
        }
        .question-card{
            border-radius:18px;
            padding:20px;
            background:#fff;
            border:1px solid rgba(0,0,0,.06);
            margin-bottom:16px;
            box-shadow:0 6px 20px rgba(0,0,0,.04);
        }
        .option-item{
            padding:12px 16px;
            border-radius:12px;
            border:1.5px solid rgba(0,0,0,.08);
            margin-bottom:8px;
            display:flex;
            gap:10px;
            background:#fff;
        }
        .option-item.correct{
            border-color:#22c55e;
            background:rgba(34,197,94,.08);
        }
        .option-item.wrong{
            border-color:#ef4444;
            background:rgba(239,68,68,.08);
        }
        .option-item.neutral{ background:#f8f9fb; }
        .subject-tab{
            cursor:pointer; padding:9px 18px; border-radius:30px;
            border:1px solid rgba(0,0,0,.1); margin-right:8px; margin-bottom:8px;
            display:inline-block; background:#fff; font-weight:600; font-size:13px;
            transition:.2s;
        }
        .subject-tab.active{ background:#6259ca!important; color:#fff!important; border-color:#6259ca!important; box-shadow:0 6px 15px rgba(98,89,202,.3); }
        .filter-btn{
            padding:8px 16px; border-radius:30px; border:1px solid rgba(0,0,0,.1);
            background:#fff; font-weight:600; font-size:13px; cursor:pointer;
        }
        .filter-btn.active{ background:#111; color:#fff; border-color:#111; }
        .btn-premium{ border-radius:14px; padding:11px 22px; font-weight:700; }
        .badge-correct{ background:#22c55e; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; }
        .badge-wrong{ background:#ef4444; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; }

        /* DARK MODE */
        .dark-mode body, body.dark-mode{ background:#0f0f14!important; }
        .dark-mode .premium-card, .dark-mode .question-card, .dark-mode .option-item.neutral, .dark-mode .subject-tab, .dark-mode .filter-btn{
            background: linear-gradient(180deg,#232337 0%,#1c1c2e 100%)!important;
            border-color: rgba(255,255,255,.07)!important;
            color:#e4e4f0!important;
            box-shadow:0 10px 40px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.05);
        }
        .dark-mode .option-item.correct{ background:rgba(34,197,94,.12)!important; border-color:rgba(34,197,94,.4)!important; }
        .dark-mode .option-item.wrong{ background:rgba(239,68,68,.12)!important; border-color:rgba(239,68,68,.4)!important; }
        .dark-mode .text-muted{ color:#9a9ab0!important; }
        .dark-mode h5, .dark-mode h6, .dark-mode strong{ color:#f0f0ff!important; }
    </style>
</head>
<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="card premium-card mb-4">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Review Answers - <?php echo $examData['exam_type']; ?></h5>
                            <small class="text-muted"><?php echo $studentData['student_name']; ?> • <?php echo $correctCount; ?> / <?php echo $totalAnswered; ?> correct • Answered <?php echo $totalAnswered; ?> / <?php echo $totalExpected; ?></small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="cbt_result.php?attempt_uin=<?php echo urlencode($_GET['attempt_uin']); ?>&student_uin=<?php echo urlencode($_GET['student_uin']); ?>&exam_uin=<?php echo urlencode($_GET['exam_uin']); ?>" class="btn btn-primary btn-premium">Back to Result</a>
                            <a href="cbt_available.php?student_uin=<?php echo urlencode($_GET['student_uin']); ?>" class="btn btn-light btn-premium" style="box-shadow:none;">Retake</a>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <div id="subjectTabs">
                        <span class="subject-tab active" data-subject="all">All Subjects (<?php echo $totalAnswered; ?>)</span>
                        <?php foreach($bySubject as $subName => $list){ echo "<span class='subject-tab' data-subject='".htmlspecialchars($subName)."'>$subName (".count($list).")</span>"; } ?>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-3">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="correct">✓ Correct (<?php echo $correctCount; ?>)</button>
                    <button class="filter-btn" data-filter="wrong">✗ Wrong (<?php echo $totalAnswered - $correctCount; ?>)</button>
                </div>

                <div id="reviewContainer">
                    <?php
                    $qNum = 0;
                    foreach($allReview as $q){
                        $qNum++;
                        $isCorrect = $q['correct_option'] == $q['selected_option'];
                        $status = $isCorrect ? 'correct' : 'wrong';
                        $badge = $isCorrect ? "<span class='badge-correct'>Correct</span>" : "<span class='badge-wrong'>Wrong</span>";
                        echo "
                        <div class='question-card' data-subject='".htmlspecialchars($q['subject_name'])."' data-status='$status'>
                            <div class='d-flex justify-content-between align-items-center mb-2'>
                                <small class='fw-bold' style='color:#6259ca;'>{$q['subject_name']} • Q$qNum</small>
                                $badge
                            </div>
                            <h6 class='fw-bold mb-3'>{$q['question_text']}</h6>
                            ".($q['question_image']?"<img src='../cbt_questions/{$q['question_image']}' style='max-width:100%;border-radius:12px;margin-bottom:12px;'>":"")."
                            <div>
                                <div class='option-item ".($q['correct_option']=='A'?'correct':($q['selected_option']=='A'?'wrong':'neutral'))."'>
                                    <strong>A.</strong> <span>{$q['option_a']}</span>
                                    ".($q['correct_option']=='A'?"<span style='margin-left:auto;'>✓ Answer</span>":"")."
                                    ".($q['selected_option']=='A' && $q['correct_option']!='A'?"<span style='margin-left:auto;'>✗ Your choice</span>":"")."
                                </div>
                                <div class='option-item ".($q['correct_option']=='B'?'correct':($q['selected_option']=='B'?'wrong':'neutral'))."'>
                                    <strong>B.</strong> <span>{$q['option_b']}</span>
                                    ".($q['correct_option']=='B'?"<span style='margin-left:auto;'>✓ Answer</span>":"")."
                                    ".($q['selected_option']=='B' && $q['correct_option']!='B'?"<span style='margin-left:auto;'>✗ Your choice</span>":"")."
                                </div>
                                <div class='option-item ".($q['correct_option']=='C'?'correct':($q['selected_option']=='C'?'wrong':'neutral'))."'>
                                    <strong>C.</strong> <span>{$q['option_c']}</span>
                                    ".($q['correct_option']=='C'?"<span style='margin-left:auto;'>✓ Answer</span>":"")."
                                    ".($q['selected_option']=='C' && $q['correct_option']!='C'?"<span style='margin-left:auto;'>✗ Your choice</span>":"")."
                                </div>
                                <div class='option-item ".($q['correct_option']=='D'?'correct':($q['selected_option']=='D'?'wrong':'neutral'))."'>
                                    <strong>D.</strong> <span>{$q['option_d']}</span>
                                    ".($q['correct_option']=='D'?"<span style='margin-left:auto;'>✓ Answer</span>":"")."
                                    ".($q['selected_option']=='D' && $q['correct_option']!='D'?"<span style='margin-left:auto;'>✗ Your choice</span>":"")."
                                </div>
                            </div>
                            <div class='mt-2 d-flex gap-2'>
                                <small class='text-muted'>You picked: <strong>{$q['selected_option']}</strong></small>
                                <small class='text-muted'>• Correct: <strong style='color:#22c55e;'>{$q['correct_option']}</strong></small>
                            </div>
                        </div>
                        ";
                    }
                    if(empty($allReview)){
                        echo "<div class='card premium-card'><div class='card-body text-center p-5 text-muted'>No answers found for this attempt. The student didn't save any.</div></div>";
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>

    <script>
        let activeSubject = 'all';
        let activeFilter = 'all';

        function applyFilter(){
            document.querySelectorAll('#reviewContainer .question-card').forEach(card=>{
                let sub = card.dataset.subject;
                let status = card.dataset.status;
                let showSub = (activeSubject === 'all' || sub === activeSubject);
                let showStatus = (activeFilter === 'all' || status === activeFilter);
                card.style.display = (showSub && showStatus) ? 'block' : 'none';
            });
        }

        document.querySelectorAll('.subject-tab').forEach(tab=>{
            tab.addEventListener('click', function(){
                document.querySelectorAll('.subject-tab').forEach(t=>t.classList.remove('active'));
                this.classList.add('active');
                activeSubject = this.dataset.subject;
                applyFilter();
            });
        });

        document.querySelectorAll('.filter-btn').forEach(btn=>{
            btn.addEventListener('click', function(){
                document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.dataset.filter;
                applyFilter();
            });
        });
    </script>

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