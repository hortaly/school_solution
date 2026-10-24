<?php
include('session.php');
include('db_conn.php');
$attemptUin = mysqli_real_escape_string($conn, $_POST['attempt_uin'] ?? '');
$questionUin = mysqli_real_escape_string($conn, $_POST['question_uin'] ?? '');
$selectedOption = mysqli_real_escape_string($conn, $_POST['selected_option'] ?? '');
if (empty($attemptUin) || empty($questionUin)) exit();
$check = mysqli_query($conn, "SELECT id FROM cbt_student_answers WHERE attempt_uin='$attemptUin' AND question_uin='$questionUin' LIMIT 1");
if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "UPDATE cbt_student_answers SET selected_option='$selectedOption' WHERE attempt_uin='$attemptUin' AND question_uin='$questionUin'");
} else {
    $now = date("Y-m-d H:i:s");
    mysqli_query($conn, "INSERT INTO cbt_student_answers (timestamp, attempt_uin, question_uin, selected_option) VALUES('$now','$attemptUin','$questionUin','$selectedOption')");
}
echo "saved";
