<?php
include("db_conn.php");
if (isset($_REQUEST['parent_uin'])) {
    $sql = "UPDATE parents SET status = 'deactivated' WHERE parent_uin = '$_REQUEST[parent_uin]'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo"<script>alert('Parent Was Sucessfully Deactivated');
        location.href= 'all_parent.php';
        </script>";
    }else{
           echo"<script>alert('Database Error');
        location.href= 'all_parent.php';
        </script>";
    }
}
?>