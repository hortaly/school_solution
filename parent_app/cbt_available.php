<?php

include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM parents WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>
<?php
ob_start();
include('session.php');
include('db_conn.php');

$parentUin = mysqli_real_escape_string($conn, $session_uin);
$studentUinRaw = isset($_GET['student_uin'])? trim($_GET['student_uin']) : '';
$examUinRaw = isset($_GET['exam_uin'])? trim($_GET['exam_uin']) : '';
$attemptUinRaw = isset($_GET['attempt_uin'])? trim($_GET['attempt_uin']) : '';

$studentUin = mysqli_real_escape_string($conn, $studentUinRaw);
$examUin = mysqli_real_escape_string($conn, $examUinRaw);
$attemptUin = mysqli_real_escape_string($conn, $attemptUinRaw);

if (empty($studentUin)) { die("No student selected"); }
$verifyQuery = mysqli_query($conn, "SELECT * FROM students WHERE student_uin='$studentUin' AND parent_uin='$parentUin' LIMIT 1");
if (!$verifyQuery || mysqli_num_rows($verifyQuery) == 0) { die("Unauthorized"); }
$studentData = mysqli_fetch_assoc($verifyQuery);

$className = $studentData['student_class'];
$classNameSafe = mysqli_real_escape_string($conn, $className);
$classQuery = mysqli_query($conn, "SELECT class_uin FROM class WHERE class='$classNameSafe' LIMIT 1");
$classData = $classQuery? mysqli_fetch_assoc($classQuery) : null;
$classUin = $classData['class_uin']?? '';
$classUinSafe = mysqli_real_escape_string($conn, $classUin);

$examData = null;
if (!empty($examUin)) {
    $examQuery = mysqli_query($conn, "SELECT * FROM cbt_exams WHERE exam_uin='$examUin' AND status='active' LIMIT 1");
    $examData = $examQuery? mysqli_fetch_assoc($examQuery) : null;
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <title>CBT - <?php echo $studentData['student_name'];?> || Josh College</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <style>
        :root { --premium-radius: 18px; --premium-shadow: 0 10px 35px rgba(0,0,0,.06); }
     .premium-card { border-radius: var(--premium-radius)!important; border: 1px solid rgba(0,0,0,.06)!important; box-shadow: var(--premium-shadow); overflow: hidden; background: #fff; }
     .dark-mode.premium-card { background: #1e1e2d!important; border-color: rgba(255,255,255,.1)!important; color: #e1e1e1; }
     .btn-premium { border-radius: 12px; padding: 11px 22px; font-weight: 600; box-shadow: 0 6px 18px rgba(98,89,202,.25); }
     .question-card { border: 1.5px solid rgba(0,0,0,.08); border-radius: 16px; padding: 22px; background: #fff; box-shadow: var(--premium-shadow); color: #212529; }
     .dark-mode.question-card { background: #2a2a3c!important; border-color: rgba(255,255,255,.12)!important; color: #e9ecef!important; }
     .question-number { width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; margin: 4px; font-weight: 700; cursor: pointer; border: 1.5px solid rgba(0,0,0,.12); }
     .question-number.answered { background: #198754; color: #fff; border-color: #198754; }
     .question-number.not-answered { background: #fff; color: #dc3545; border-color: #f5c2c7; }
     .dark-mode.question-number.not-answered { background: #1e1e2d; color: #ff6b6b; border-color: rgba(255,107,107,.3); }
     .question-number.active { outline: 2px solid #6259ca; outline-offset: 2px; }
     .subject-tab { cursor: pointer; padding: 10px 18px; border-radius: 10px; border: 1px solid rgba(0,0,0,.1); margin-right: 8px; margin-bottom: 8px; display: inline-block; background: #fff; }
     .dark-mode.subject-tab { background: #2a2a3c; border-color: rgba(255,255,255,.1); color: #e1e1e1; }
     .subject-tab.active { background: #6259ca!important; color: #fff!important; border-color: #6259ca!important; }
     .option-label { border: 1px solid rgba(0,0,0,.12); background: #fff; color: #212529; cursor:pointer; }
     .option-label:has(input:checked){ border-color:#6259ca; background:#f3f1ff; }
     .timer-box { background: linear-gradient(135deg, #6259ca, #8a7cf5); color: #fff; border-radius: 12px; padding: 12px 18px; display: flex; justify-content: space-between; align-items: center; }
     .timer-box.danger { background: linear-gradient(135deg, #dc3545, #ff6b6b); animation: pulse 1s infinite; }
      @keyframes pulse { 0%{transform:scale(1)} 50%{transform:scale(1.02)} 100%{transform:scale(1)} }
     .save-indicator{ font-size:12px; color:#a7ffbe; display:none; }
     .save-indicator.show{ display:inline-block; }
    </style>
</head>
<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php");?>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <?php
                if (!empty($examUin) &&!empty($attemptUin)) {
                    $selectedSubjectUins = $_SESSION['practice_subjects_'. $attemptUinRaw]?? [];
                    $allQuestions = [];
                    foreach ($selectedSubjectUins as $subjectUinItem) {
                        $subjectUinItemSafe = mysqli_real_escape_string($conn, $subjectUinItem);
                        $questionsQuery = mysqli_query($conn, "SELECT q.*, a.selected_option as saved_answer FROM cbt_questions q LEFT JOIN cbt_student_answers a ON a.question_uin=q.question_uin AND a.attempt_uin='$attemptUin' WHERE q.exam_uin='$examUin' AND q.subject_uin='$subjectUinItemSafe' ORDER BY RAND() LIMIT 50");
                        while ($questionRow = mysqli_fetch_assoc($questionsQuery)) {
                            $allQuestions[] = $questionRow;
                        }
                    }
                    $questionsBySubject = [];
                    foreach ($allQuestions as $questionRow) {
                        $questionsBySubject[$questionRow['subject_name']][] = $questionRow;
                    }
                    $subjectList = array_keys($questionsBySubject);
                    $durationMinutes = intval($examData['duration_minutes']?? 60);

                    $attemptQuery = mysqli_query($conn, "SELECT start_time FROM cbt_student_attempts WHERE attempt_uin='$attemptUin' LIMIT 1");
                    $attemptRow = $attemptQuery? mysqli_fetch_assoc($attemptQuery) : null;
                    $startTimestamp = $attemptRow && $attemptRow['start_time']? strtotime($attemptRow['start_time']) : time();
                    $elapsedSeconds = time() - $startTimestamp;
                    $totalDurationSeconds = $durationMinutes * 60;
                    $remainingSeconds = $totalDurationSeconds - $elapsedSeconds;
                    if ($remainingSeconds < 0) $remainingSeconds = 0;

                    function formatToHMS($seconds) {
                        $h = floor($seconds / 3600);
                        $m = floor(($seconds % 3600) / 60);
                        $s = $seconds % 60;
                        if ($h > 0) return sprintf("%dh %02dm %02ds", $h, $m, $s);
                        return sprintf("%02dm %02ds", $m, $s);
                    }
                    $initialDisplay = formatToHMS($remainingSeconds);
             ?>
                    <div class="timer-box mb-3" id="timerBox">
                        <div>
                            <h5 class="mb-0 fw-bold"><?php echo $examData['exam_type'];?> - Practice</h5><small><?php echo $studentData['student_name'];?> | <?php echo implode(", ", $subjectList);?></small>
                        </div>
                        <div class="text-end">
                            <span class="fs-12">Time Left</span>
                            <div id="timerDisplay" class="fw-bold" style="font-size:22px;"><?php echo $initialDisplay;?></div>
                            <small id="saveIndicator" class="save-indicator">✓ Saved</small>
                        </div>
                    </div>

                    <div class="mb-3"><?php foreach ($subjectList as $idx => $subjectName) {?><div class="subject-tab <?php echo $idx == 0? 'active' : '';?>" data-subject="<?php echo htmlspecialchars($subjectName);?>"><?php echo $subjectName;?></div><?php }?></div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div id="questionContainer"></div>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-light btn-premium" id="prevButton" style="box-shadow:none;">Back</button>
                                <button class="btn btn-primary btn-premium" id="saveButton">Save</button>
                                <button class="btn btn-outline-primary btn-premium" id="nextButton" style="box-shadow:none;">Next</button>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card premium-card" style="position:sticky;top:90px;">
                                <div class="card-body">
                                    <h6 class="fw-bold">Question Navigation</h6><small class="text-muted">Green = saved, Red = not saved</small>
                                    <div id="questionNumbers" class="mt-2"></div>
                                    <hr><button class="btn btn-success btn-premium w-100" id="submitButton">Submit</button><a href="cbt_available.php?student_uin=<?php echo urlencode($studentUinRaw);?>" class="btn btn-light btn-premium w-100 mt-2" style="box-shadow:none;">Exit</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        let questionsBySubject = <?php echo json_encode($questionsBySubject);?>;
                        let subjectList = <?php echo json_encode($subjectList);?>;
                        let currentSubject = subjectList[0];
                        let currentIndex = 0;
                        let attemptUin = "<?php echo $attemptUinRaw;?>";
                        let serverRemaining = <?php echo $remainingSeconds;?>;
                        let storageKey = "cbt_timer_" + attemptUin;
                        let localRemaining = localStorage.getItem(storageKey);
                        if(localRemaining!== null){
                            localRemaining = parseInt(localRemaining);
                            if(localRemaining < serverRemaining && localRemaining > 0){
                                serverRemaining = localRemaining;
                            }
                        }
                        let durationSeconds = serverRemaining;
                        let timerInterval;
                        let isAutoSubmitting = false;

                        function formatHMS(totalSeconds){
                            let h = Math.floor(totalSeconds / 3600);
                            let m = Math.floor((totalSeconds % 3600) / 60);
                            let s = totalSeconds % 60;
                            if(h > 0) return `${h}h ${String(m).padStart(2,'0')}m ${String(s).padStart(2,'0')}s`;
                            return `${String(m).padStart(2,'0')}m ${String(s).padStart(2,'0')}s`;
                        }

                        function renderQuestion() {
                            let questionList = questionsBySubject[currentSubject];
                            let questionData = questionList[currentIndex];
                            if (!questionData) return;
                            let html = `<div class="question-card"><div class="d-flex justify-content-between"><small>${currentSubject} - Q ${currentIndex+1} of ${questionList.length}</small><small>${questionData.question_uin}</small></div><h5 class="mt-3">${questionData.question_text}</h5>${questionData.question_image? `<img src="../cbt_questions/${questionData.question_image}" style="max-width:100%;border-radius:10px;margin:10px 0;">` : ''}<div class="mt-3"><label class="option-label d-flex gap-2 p-3 rounded mb-2"><input type="radio" name="selectedOption" value="A" ${questionData.saved_answer=='A'?'checked':''}> A. ${questionData.option_a}</label><label class="option-label d-flex gap-2 p-3 rounded mb-2"><input type="radio" name="selectedOption" value="B" ${questionData.saved_answer=='B'?'checked':''}> B. ${questionData.option_b}</label><label class="option-label d-flex gap-2 p-3 rounded mb-2"><input type="radio" name="selectedOption" value="C" ${questionData.saved_answer=='C'?'checked':''}> C. ${questionData.option_c}</label><label class="option-label d-flex gap-2 p-3 rounded mb-2"><input type="radio" name="selectedOption" value="D" ${questionData.saved_answer=='D'?'checked':''}> D. ${questionData.option_d}</label></div></div>`;
                            document.getElementById('questionContainer').innerHTML = html;
                            renderNumbers();
                        }
                        function renderNumbers() {
                            let questionList = questionsBySubject[currentSubject];
                            let html = '';
                            questionList.forEach((q, i) => {
                                let cssClass = q.saved_answer? 'answered' : 'not-answered';
                                if (i == currentIndex) cssClass += ' active';
                                html += `<div class="question-number ${cssClass}" onclick="jumpToQuestion(${i})">${i+1}</div>`;
                            });
                            document.getElementById('questionNumbers').innerHTML = html;
                        }
                        function saveCurrentAnswer(callback) {
                            let questionList = questionsBySubject[currentSubject];
                            let questionData = questionList[currentIndex];
                            let selectedInput = document.querySelector('input[name="selectedOption"]:checked');
                            if (!selectedInput) {
                                if(callback) callback(false);
                                else {
                                    let ind = document.getElementById('saveIndicator');
                                    ind.textContent = "Pick an option";
                                    ind.style.color = "#ff6b6b";
                                    ind.classList.add('show');
                                    setTimeout(()=> { ind.classList.remove('show'); ind.textContent="✓ Saved"; ind.style.color="#a7ffbe"; }, 1500);
                                }
                                return false;
                            }
                            let selectedValue = selectedInput.value;
                            questionData.saved_answer = selectedValue;
                            questionList[currentIndex] = questionData;
                            fetch('cbt_save_answer.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: `attempt_uin=${encodeURIComponent(attemptUin)}&question_uin=${encodeURIComponent(questionData.question_uin)}&selected_option=${encodeURIComponent(selectedValue)}`
                            }).then(() => {
                                renderNumbers();
                                let ind = document.getElementById('saveIndicator');
                                ind.textContent = "✓ Saved";
                                ind.style.color = "#a7ffbe";
                                ind.classList.add('show');
                                setTimeout(()=> ind.classList.remove('show'), 1200);
                                if(callback) callback(true);
                            }).catch(()=>{
                                // Even if save fails, still proceed to submit on auto-submit
                                if(callback) callback(true);
                            });
                            return true;
                        }
                        // === 100% SAFE AUTO-SUBMIT ===
                        function safeAutoSubmit(){
                            if(isAutoSubmitting) return;
                            isAutoSubmitting = true;
                            clearInterval(timerInterval);
                            document.getElementById('timerDisplay').textContent = "Submitting...";
                            document.getElementById('timerBox').classList.add('danger');

                            // Try to save current unsaved selection first
                            let selectedInput = document.querySelector('input[name="selectedOption"]:checked');
                            let questionList = questionsBySubject[currentSubject];
                            let questionData = questionList? questionList[currentIndex] : null;

                            if(selectedInput && questionData && questionData.saved_answer!== selectedInput.value){
                                // Has unsaved answer - save it before redirect
                                fetch('cbt_save_answer.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: `attempt_uin=${encodeURIComponent(attemptUin)}&question_uin=${encodeURIComponent(questionData.question_uin)}&selected_option=${encodeURIComponent(selectedInput.value)}`,
                                    keepalive: true // important: keeps request alive even during page unload
                                }).finally(()=>{
                                    doRedirect();
                                });
                            } else {
                                doRedirect();
                            }
                        }
                        function doRedirect(){
                            localStorage.removeItem(storageKey);
                            // Use beacon as final backup - guaranteed to send
                            let data = new FormData();
                            data.append('attempt_uin', attemptUin);
                            // navigator.sendBeacon('cbt_auto_submit.php', data); // optional if you create this file
                            window.location.href = `cbt_result.php?attempt_uin=${encodeURIComponent(attemptUin)}&student_uin=<?php echo urlencode($studentUinRaw);?>&exam_uin=<?php echo urlencode($examUinRaw);?>&auto=1`;
                        }

                        window.jumpToQuestion = function(index) {
                            currentIndex = index;
                            renderQuestion();
                        }
                        document.getElementById('saveButton').addEventListener('click', function() { saveCurrentAnswer(); });
                        document.getElementById('nextButton').addEventListener('click', function() {
                            let list = questionsBySubject[currentSubject];
                            if (currentIndex < list.length - 1) {
                                currentIndex++;
                                renderQuestion();
                            } else {
                                let currSubIdx = subjectList.indexOf(currentSubject);
                                if(currSubIdx < subjectList.length - 1){
                                    document.querySelectorAll('.subject-tab')[currSubIdx+1].click();
                                }
                            }
                        });
                        document.getElementById('prevButton').addEventListener('click', function() {
                            if (currentIndex > 0) { currentIndex--; renderQuestion(); }
                        });
                        document.querySelectorAll('.subject-tab').forEach(tab => {
                            tab.addEventListener('click', function() {
                                document.querySelectorAll('.subject-tab').forEach(t => t.classList.remove('active'));
                                this.classList.add('active');
                                currentSubject = this.dataset.subject;
                                currentIndex = 0;
                                renderQuestion();
                            });
                        });
                        function startTimer() {
                            localStorage.setItem(storageKey, durationSeconds);
                            document.getElementById('timerDisplay').textContent = formatHMS(durationSeconds);
                            timerInterval = setInterval(() => {
                                durationSeconds--;
                                localStorage.setItem(storageKey, durationSeconds);
                                document.getElementById('timerDisplay').textContent = formatHMS(durationSeconds);
                                if (durationSeconds <= 300) {
                                    document.getElementById('timerBox').classList.add('danger');
                                }
                                if (durationSeconds <= 0) {
                                    safeAutoSubmit(); // <-- SAFE VERSION
                                }
                            }, 1000);
                        }
                        if(durationSeconds <= 0){
                            safeAutoSubmit();
                        } else {
                            startTimer();
                        }
                        document.getElementById('submitButton').addEventListener('click', function() {
                            if (confirm('Submit? You can retake anytime')) {
                                // Also safe submit on manual submit
                                let selectedInput = document.querySelector('input[name="selectedOption"]:checked');
                                if(selectedInput){
                                    saveCurrentAnswer(()=> {
                                        localStorage.removeItem(storageKey);
                                        window.location.href = `cbt_result.php?attempt_uin=${encodeURIComponent(attemptUin)}&student_uin=<?php echo urlencode($studentUinRaw);?>&exam_uin=<?php echo urlencode($examUinRaw);?>`;
                                    });
                                } else {
                                    localStorage.removeItem(storageKey);
                                    window.location.href = `cbt_result.php?attempt_uin=${encodeURIComponent(attemptUin)}&student_uin=<?php echo urlencode($studentUinRaw);?>&exam_uin=<?php echo urlencode($examUinRaw);?>`;
                                }
                            }
                        });
                        renderQuestion();
                    </script>

                <?php
                } elseif (!empty($examUin) && empty($attemptUin)) {
                    $availableSubjectsQuery = mysqli_query($conn, "SELECT DISTINCT subject_name, subject_uin FROM cbt_questions WHERE exam_uin='$examUin' ORDER BY subject_name");
                    $availableSubjects = [];
                    while ($subjectRow = mysqli_fetch_assoc($availableSubjectsQuery)) {
                        $availableSubjects[] = $subjectRow;
                    }
                    if (isset($_POST['startPractice'])) {
                        $selectedSubjects = $_POST['subjects']?? [];
                        if (empty($selectedSubjects)) {
                            $errorMessage = "Pick at least one subject";
                        } elseif (count($selectedSubjects) > 4) {
                            $errorMessage = "Maximum 4 subjects allowed - you picked ". count($selectedSubjects);
                        } else {
                            $newAttemptUin = "ATT/". time(). "/". $studentUin. "/". rand(100, 999);
                            $currentTime = date("Y-m-d H:i:s");
                            mysqli_query($conn, "INSERT INTO cbt_student_attempts (attempt_uin, student_uin, exam_uin, score, start_time, status) VALUES('$newAttemptUin','$studentUin','$examUin',0,'$currentTime','practice')");
                            $_SESSION['practice_subjects_'. $newAttemptUin] = $selectedSubjects;
                            $redirectUrl = "cbt_available.php?student_uin=". urlencode($studentUinRaw). "&exam_uin=". urlencode($examUinRaw). "&attempt_uin=". urlencode($newAttemptUin);
                            echo "<script>window.location.href='$redirectUrl';</script>";
                            exit();
                        }
                    }
             ?>
                    <div class="card premium-card">
                        <div class="card-body">
                            <h4 class="fw-bold">Select Subjects - <?php echo $examData['exam_type'];?></h4>
                            <p class="text-muted">Student: <?php echo $studentData['student_name'];?> | Duration: <?php echo $examData['duration_minutes'];?> mins (<?php echo round($examData['duration_minutes']/60,1);?> hrs)</p>
                            <div class="alert alert-info">Pick max 4 subjects (50 random questions per subject) - JAMB style</div>
                            <form method="post" id="subjectForm">
                                <?php foreach ($availableSubjects as $subjectItem) {?>
                                    <label class="d-flex gap-2 p-3 border rounded mb-2 subject-option"><input type="checkbox" name="subjects[]" value="<?php echo $subjectItem['subject_uin'];?>" class="subject-check"> <strong><?php echo $subjectItem['subject_name'];?></strong></label>
                                <?php }?>
                                <?php if (isset($errorMessage)) { echo "<div class='alert alert-danger mt-2'>$errorMessage</div>"; }?>
                                <div class="mt-2"><small id="subjectCount" class="text-muted">0 / 4 selected</small></div>
                                <button type="submit" name="startPractice" class="btn btn-primary btn-premium w-100 mt-3">Start Practice (<?php echo $examData['duration_minutes'];?> mins / <?php echo round($examData['duration_minutes']/60,2);?> hrs)</button>
                                <a href="cbt_available.php?student_uin=<?php echo urlencode($studentUinRaw);?>" class="btn btn-light btn-premium w-100 mt-2" style="box-shadow:none;">Back</a>
                            </form>
                        </div>
                    </div>
                    <script>
                        let checks = document.querySelectorAll('.subject-check');
                        let countEl = document.getElementById('subjectCount');
                        checks.forEach(c => {
                            c.addEventListener('change', () => {
                                let sel = document.querySelectorAll('.subject-check:checked').length;
                                countEl.textContent = sel + ' / 4 selected';
                                if (sel > 4) {
                                    alert('Max 4 subjects');
                                    c.checked = false;
                                    countEl.textContent = document.querySelectorAll('.subject-check:checked').length + ' / 4 selected';
                                }
                            });
                        });
                    </script>
                <?php
                } else {
                    $examList = [];
                    if ($classUin) {
                        $examListQuery = mysqli_query($conn, "SELECT e.* FROM cbt_exams e JOIN cbt_exam_classes ec ON ec.exam_uin=e.exam_uin WHERE ec.class_uin='$classUinSafe' AND e.status='active' ORDER BY e.id DESC");
                        while ($examRow = mysqli_fetch_assoc($examListQuery)) {
                            $examList[] = $examRow;
                        }
                    }
             ?>
                    <div class="card premium-card mb-4">
                        <div class="card-body">
                            <h3 class="fw-bold mb-1">CBT Practice for <?php echo $studentData['student_name'];?> - <?php echo $studentData['student_class'];?></h3><small class="text-muted">Class UIN: <?php echo $classUin;?> | Practice Mode - Unlimited Retake</small>
                        </div>
                    </div>
                    <?php if (empty($examList)) {?><div class="card premium-card"><div class="card-body text-center p-5 text-muted">No active CBT for <?php echo $studentData['student_class'];?></div></div>
                    <?php } else {?>
                        <div class="row"><?php foreach ($examList as $examRow) {?><div class="col-md-4">
                                    <div class="card premium-card">
                                        <div class="card-body">
                                            <h5 class="fw-bold"><?php echo $examRow['exam_type'];?></h5><small class="text-muted d-block">Duration: <?php echo $examRow['duration_minutes'];?> mins (<?php echo round($examRow['duration_minutes']/60,2);?>h)</small><small class="text-muted d-block mb-3"><?php echo $examRow['exam_uin'];?></small><a href="cbt_available.php?student_uin=<?php echo urlencode($studentUinRaw);?>&exam_uin=<?php echo urlencode($examRow['exam_uin']);?>" class="btn btn-primary btn-premium w-100">Select Subjects</a>
                                        </div>
                                    </div>
                                </div><?php }?></div>
                    <?php }?>
                <?php }?>
            </div>
        </div>
    </div>
    <footer class="footer"><div class="container"><div class="row align-items-center flex-row-reverse"><div class="col-md-12 col-sm-12 text-center">Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>.</div></div></div></footer>
    </div>
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