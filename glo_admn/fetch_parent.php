<?php
include("db_conn.php");

if(isset($_POST['parent_email'])){
    $parent_email = trim($_POST['parent_email']);
    $parent_email = mysqli_real_escape_string($conn, $parent_email);

    $sql = mysqli_query($conn, "SELECT * FROM parents WHERE parent_email = '$parent_email'");
    $row = mysqli_fetch_assoc($sql);

    if($row){
        echo json_encode(array(
            "status"        => "found",
            "parent_name"   => $row['parent_name'],
            "parent_uin"    => $row['parent_uin'],
            "parent_number" => $row['parent_number']
        ));
    } else {
        echo json_encode(array("status" => "not_found"));
    }
}
?>