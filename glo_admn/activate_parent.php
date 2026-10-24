<?php
include("db_conn.php");
if (isset($_REQUEST['parent_uin'])) {
    $sql = "UPDATE parents SET status = 'active' WHERE parent_uin = '$_REQUEST[parent_uin]'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo"<script>alert('Parent Was Sucessfully Activated');
        location.href= 'all_parent.php';
        </script>";
    }else{
           echo"<script>alert('Database Error');
        location.href= 'all_parent.php';
        </script>";
    }
}
?>