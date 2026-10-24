<?php
include('db_conn.php');
session_start();

/* =========================
   1. CHECK LOGIN FIRST
========================= */
if (!isset($_SESSION['email'])) {
    header("Location: ../admn_log/login-4.php");
    exit();
}

$user_check = $_SESSION['email'];

/* =========================
   2. SESSION TIMEOUT LOGIC
========================= */
$timeout_duration = 1200; // 15 minutes

if (isset($_SESSION['LAST_ACTIVITY'])) {

    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {

        session_unset();
        session_destroy();

        header("Location: ../admn_log/login-4.php?timeout=1");
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time();

/* =========================
   3. FETCH USER DATA
========================= */
$sql = mysqli_query($conn, "
    SELECT * FROM glo_admn 
    WHERE email='$user_check' 
    AND `role`='Super Admin'
");

$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

/* =========================
   4. PROTECT AGAINST EMPTY RESULT
========================= */
if(!$row){
    session_destroy();
    header("Location: ../admn_log/login-4.php");
    exit();
}

/* =========================
   5. SESSION VARIABLES
========================= */
$session_uin = $row['uin'];
$session_fullname = $row['fullname'];
$session_email = $row['email'];
$session_phone = $row['phone_number'];
$session_gender = $row['gender'];
$session_role = $row['role'];
$session_photo = $row['photo'];
?>