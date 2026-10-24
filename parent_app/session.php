<?php
include('db_conn.php');
session_name('PARENTSESS');
session_start();

/* =========================
   1. CHECK LOGIN FIRST
========================= */
if (!isset($_SESSION['email'])) {
    header("Location: ../parents_log/index.php");
    exit();
}

$user_check = mysqli_real_escape_string($conn, $_SESSION['email']);

/* =========================
   2. SESSION TIMEOUT LOGIC
========================= */
$timeout_duration = 1200; // 20 minutes

if (isset($_SESSION['LAST_ACTIVITY'])) {

    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {

        session_unset();
        session_destroy();

        header("Location: ../parents_log/index.php?timeout=1");
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time();

/* =========================
   3. FETCH USER DATA
========================= */
$sql = mysqli_query($conn, "
    SELECT * FROM parents 
    WHERE parent_email='$user_check' 
    AND status ='active'
");

$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

/* =========================
   4. PROTECT AGAINST EMPTY RESULT
========================= */
if(!$row){
    session_unset();
    session_destroy();
    header("Location: ../parents_log/index.php");
    exit();
}

/* =========================
   5. SESSION VARIABLES
========================= */
$session_uin = $row['parent_uin'];
$session_fullname = $row['parent_name'];
$session_email = $row['parent_email'];
$session_phone = $row['parent_number'];
// $session_gender = $row['gender'];
// $session_role = $row['role'];
$session_photo = $row['parent_photo'];
?>