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

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico">

    <!-- TITLE -->
    <title>Admin Dashboard || Josh </title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
     <link href="assets/css/style.css" rel="stylesheet">

	<!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">

</head>

<body class="app sidebar-mini ltr light-mode">

<?php include("menu.php");?>
    
            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">
                     <?php
date_default_timezone_set("Africa/Lagos");

$hour = date("H");

if ($hour >= 0 && $hour < 12) {
    $greetings = "Good Morning, How Was Your Night";
} elseif ($hour >= 12 && $hour < 17) {
    $greetings = "Good Afternoon, How is your day going";
} else {
    $greetings = "Good Evening, don't sleep late";
}
?>

<!-- PAGE-HEADER -->
<div class="page-header">
  <h1 class="page-title">
  <?php 
echo $greetings . " || " . $session_fullname . 
" You Are Welcome To Josh College As a " . $session_role; 
?>
</h1>
</div>
                            <!-- <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard 01</li>
                                </ol>
                            </div>
                        </div> -->
                        <!-- PAGE-HEADER END -->

                            <!-- ROW OPEN -->
                             
                                  <div class="row">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#input-modal">
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3" data-bs-dismiss="modal">
                                <div class="card bg-primary img-card box-primary-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h3 class="mb-0 number-font">Add Parent </h3>
                                                <p class="text-white mb-0"></p>
                                            </div>
                                            <div class="ms-auto"> <i class="fa fa-plus text-white fs-30 me-2 mt-2"></i> </div>
                                        </div>
                                    </div>
                                </div>
                                 </a>


                                   <!-- Inout modal -->
    <div class="modal fade" id="input-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <!-- <h6 class="modal-title">New message to @mdo</h6> -->
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                 <form  method="post" enctype="multipart/form-data">
       <?php 
include("db_conn.php");
error_reporting(E_ALL);

$today = date("y");
$rand = rand(1000,9999);
$id = "PRT/".$today.'/'.$rand;

if (isset($_REQUEST['add_parent'])) {

    $parent_fullname = trim($_POST['parent_name']);
     $parent_surname = strtoupper(trim($_POST['parent_surname']));
    $parent_email = trim($_POST['parent_email']);
    $parent_number = trim($_POST['parent_number']);
   

    // Upload image
    $parent_photo = $_FILES["parent_photo"]['name'];
    $tmp_name = $_FILES["parent_photo"]['tmp_name'];

    $target = "../parents_log/parent_photo/" . $parent_photo;

    if(move_uploaded_file($tmp_name, $target)){

        $check = mysqli_query($conn, "SELECT * FROM parents 
        WHERE parent_uin = '$id' 
        OR parent_number = '$parent_number' 
        OR parent_email = '$parent_email' ");

        $check_row = mysqli_num_rows($check);

        if ($check_row > 0) {

            echo "<script>
            alert('Parent Already Exist');
            location.href='index.php';
            </script>";

        }else{

            $sql = "INSERT INTO parents 
            (parent_name, parent_email, parent_number, `password`, parent_uin, parent_photo, `status`, parent_surname) 
            VALUES 
            ('$parent_fullname', '$parent_email', '$parent_number', PASSWORD('$parent_surname'), '$id', '$parent_photo', 'active', '$parent_surname')";

            $result = mysqli_query($conn, $sql);

            if($result){

                echo "<script>
                alert('Success');
                window.location.href='index.php';
                </script>";

            }else{

                echo "<script>alert('Database Error')</script>";

            }

        }

    }else{

        echo "<script>alert('Image Upload Failed')</script>";

    }

}
?>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Parent Surname:</label>
                        <input type="text" class="form-control" id="recipient-name" name="parent_surname" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Parent Name:</label>
                        <input type="text" class="form-control" id="recipient-name" name="parent_name" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Parent Email:</label>
                        <input type="email" class="form-control" id="recipient-name" name="parent_email" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Parent Number:</label>
                        <input type="number" class="form-control" id="recipient-name" name="parent_number" required>
                      </div>
                     
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Photo:</label>
                        <input type="file" class="form-control" id="recipient-name" accept="image/*" name="parent_photo" required>
                      </div>
                      <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit" name="add_parent">Add Parent</button>
                    <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                </div>
                    </form>
                  </div>
              
            </div>
        </div>
    </div>
    <!-- End Datepicker modal -->
                            </div>

                            
                      
                            <!-- COL END -->
                             
                                  <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#input-modal7">
                                <div class="card bg-warning img-card box-warning-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h3 class="mb-0 number-font">Add Staff</h3>
                                             
                                            </div>
                                            <div class="ms-auto"> <i class="fa fa-users text-white fs-30 me-2 mt-2"></i> </div>
                                        </div>
                                    </div>
                                </div>
                                 </a>

                                                                   <!-- Inout modal -->
    <div class="modal fade" id="input-modal7">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <!-- <h6 class="modal-title">New message to @mdo</h6> -->
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                 <form  method="post" enctype="multipart/form-data">
       <?php 
include("db_conn.php");
error_reporting(E_ALL);

$today = date("y");
$rand = rand(1000,9999);
$id = "STF/".$today.'/'.$rand;

if (isset($_REQUEST['add_staff'])) {

    $staff_name = trim($_POST['staff_name']);
    $staff_number = trim($_POST['staff_number']);
    $staff_email = trim($_POST['staff_email']);
    $staff_dob = trim($_POST['staff_dob']);
    $staff_gender = trim($_POST['staff_gender']);
    $staff_role = trim($_POST['staff_role']);
    $staff_password = trim($_POST['staff_password']);
   

    // Upload image
    $staff_photo = $_FILES["staff_photo"]['name'];
    $tmp_name = $_FILES["staff_photo"]['tmp_name'];

    $target = "../staff_log/staff_photo/" . $staff_photo;

    if(move_uploaded_file($tmp_name, $target)){

        $check = mysqli_query($conn, "SELECT * FROM staff 
        WHERE staff_uin = '$id' 
        OR staff_number = '$staff_number' 
        OR staff_email = '$staff_email' ");

        $check_row = mysqli_num_rows($check);

        if ($check_row > 0) {

            echo "<script>
            alert('Parent Already Exist');
            location.href='index.php';
            </script>";

        }else{

            $sql = "INSERT INTO staff 
            (staff_name, staff_email, staff_number, staff_password, staff_uin, staff_photo, staff_gender, staff_role, staff_dob, `status`) 
            VALUES 
            ('$staff_name', '$staff_email', '$staff_number', PASSWORD('$staff_password'), '$id', '$staff_photo',
             '$staff_gender', '$staff_role', '$staff_dob', 'Active')";

            $result = mysqli_query($conn, $sql);

            if($result){

                echo "<script>
                alert('Success');
                window.location.href='index.php';
                </script>";

            }else{

                echo "<script>alert('Database Error')</script>";

            }

        }

    }else{

        echo "<script>alert('Image Upload Failed')</script>";

    }

}
?>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Staff Name:</label>
                        <input type="text" class="form-control" id="recipient-name" name="staff_name" required>
                      </div>
                <div class="mb-3">
    <label for="staff_gender" class="col-form-label">Gender:</label>

    <input type="text"
           class="form-control"
           list="gender_list"
           id="staff_gender"
           name="staff_gender"
           required>

    <datalist id="gender_list">
        <option value="Male">
        <option value="Female">
    </datalist>
</div>
                <div class="mb-3">
    <label for="staff_role" class="col-form-label">Role:</label>

    <input type="text"
           class="form-control"
           list="role_list"
           id="staff_role"
           name="staff_role"
           required>

    <datalist id="role_list">
        <option value="Teaching Staff">
        <option value="Non Teaching Staff">
    </datalist>
</div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">DOB:</label>
                        <input type="date" class="form-control" id="recipient-name" name="staff_dob" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="recipient-name" name="staff_email" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Phone Number:</label>
                        <input type="number" class="form-control" id="recipient-name" name="staff_number" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Password:</label>
                        <input type="text" class="form-control" id="recipient-name" name="staff_password" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Photo:</label>
                        <input type="file" class="form-control" id="recipient-name" name="staff_photo" required accept="image*/">
                      </div>
                     
                      <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit" name="add_staff">Add Staff</button>
                    <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                </div>
                    </form>
                  </div>
              
            </div>
        </div>
    </div>
    <!-- End Datepicker modal -->
                            </div>
                            
                           
          <!-- COL END -->
<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
    <a href="#" data-bs-toggle="modal" data-bs-target="#input-modal_student">
        <div class="card bg-success img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h3 class="mb-0 number-font">Student</h3>
                        <p class="text-white mb-0"></p>
                    </div>
                    <div class="ms-auto">
                        <i class="fa fa-mortar-board text-white fs-30 me-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Input modal -->
    <div class="modal fade" id="input-modal_student">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">

                    <?php 
                    include("db_conn.php");
                    error_reporting(E_ALL);

                    $today = date("y");
                    $rand = rand(1000,9999);
                    $id = "JOSH/".$today.'/'.$rand;

                    if (isset($_REQUEST['add_student'])) {
                        // for student
                        $student_name   = trim($_POST['student_name']);
                        $student_dob    = trim($_POST['student_dob']);
                        $student_gender = trim($_POST['student_gender']);
                        $student_class  = trim($_POST['student_class']);

                        // for parents
                        $parent_email  = trim($_POST['parent_email']);
                        $parent_name   = trim($_POST['parent_name']);
                        $parent_number = trim($_POST['parent_number']);
                        $parent_uin    = trim($_POST['parent_uin']);

                        // Upload image
                        $student_photo = $_FILES["student_photo"]['name'];
                        $tmp_name      = $_FILES["student_photo"]['tmp_name'];
                        $target        = "../student_log/student_photo/" . $student_photo;

                        if(move_uploaded_file($tmp_name, $target)){

                            $check     = mysqli_query($conn, "SELECT * FROM students WHERE student_uin = '$id'");
                            $check_row = mysqli_num_rows($check);

                            if ($check_row > 0) {

                                echo "<script>
                                alert('Student Already Exist');
                                location.href='index.php';
                                </script>";

                            } else {

                                $sql = "INSERT INTO students 
                                (student_name, parent_email, student_uin, student_photo, student_gender,
                                 student_dob, status, student_wallet, parent_number, parent_name, parent_uin, student_class) 
                                VALUES 
                                ('$student_name', '$parent_email', '$id', '$student_photo', '$student_gender',
                                 '$student_dob', 'Active', '0', '$parent_number', '$parent_name', '$parent_uin', '$student_class')";

                                $result = mysqli_query($conn, $sql);

                                if($result){

                                    // Auto-assign existing fees for this class to the new student
                                    $date      = date("Y-m-d H:i:s");
                                    $get_fees  = mysqli_query($conn, "SELECT * FROM fees WHERE fee_class = '$student_class'");

                                    if(mysqli_num_rows($get_fees) > 0){
                                        while($fee = mysqli_fetch_assoc($get_fees)){
                                            mysqli_query($conn, "INSERT INTO ledger 
                                                (student_uin, fee_id, entry_type, amount, description, date) 
                                                VALUES 
                                                ('$id', '".$fee['fee_id']."', 'Debit', '".$fee['fee_amount']."', 
                                                '".$fee['fee_name']." - ".$fee['fee_term']." ".$fee['fee_session']."', '$date')");
                                        }
                                    }

                                    echo "<script>
                                    alert('Student added successfully!');
                                    window.location.href='index.php';
                                    </script>";

                                } else {

                                    echo "<script>alert('Database Error')</script>";

                                }
                            }

                        } else {

                            echo "<script>alert('Image Upload Failed')</script>";

                        }
                    }
                    ?>

                    <!-- Student Name -->
                    <div class="mb-3">
                        <label class="col-form-label">Student Name:</label>
                        <input type="text" class="form-control" name="student_name" required>
                    </div>

                    <!-- Student DOB -->
                    <div class="mb-3">
                        <label class="col-form-label">Student Dob:</label>
                        <input type="date" class="form-control" name="student_dob" required>
                    </div>

                    <!-- Student Photo -->
                    <div class="mb-3">
                        <label class="col-form-label">Student Photo:</label>
                        <input type="file" class="form-control" name="student_photo" required accept="image/*">
                    </div>

                    <!-- Student Gender -->
                    <div class="mb-3">
                        <label class="col-form-label">Student Gender:</label>
                        <select class="form-control" name="student_gender" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <?php
                    $class_query = mysqli_query($conn, "SELECT * FROM class");
                    ?>

                    <!-- Student Class -->
                    <div class="mb-3">
                        <label class="col-form-label">Student Class:</label>
                        <select class="form-control" name="student_class" required>
                            <option value="">-- Select Class --</option>
                            <?php while ($row = mysqli_fetch_assoc($class_query)) { ?>
                                <option value="<?php echo htmlspecialchars($row['class']); ?>">
                                    <?php echo htmlspecialchars($row['class']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                  <!-- Parent Email -->
<div class="mb-3">
    <label class="col-form-label">Parent Email:</label>
    <input type="email" class="form-control" 
           id="parent_email" name="parent_email" required>
</div>

<!-- Status Message -->
<div id="parent_status" class="mb-2"></div>

<!-- Parent Name (autofill) -->
<div class="mb-3">
    <label class="col-form-label">Parent Name:</label>
    <input type="text" class="form-control" 
           id="parent_name" name="parent_name" readonly>
</div>

<!-- Parent Number (hidden) -->
 <div class="mb-3">
    <label class="col-form-label">Parent Number:</label>
<input type="number" class="form-control" id="parent_number" name="parent_number" readonly>
 </div>

<!-- Parent UIN (autofill) -->
<div class="mb-3">
    <label class="col-form-label">Parent UIN:</label>
    <input type="text" class="form-control" 
           id="parent_uin" name="parent_uin" readonly>
</div>

<script>
document.getElementById('parent_email').addEventListener('blur', function(){
    var email = this.value.trim();
    if(email === '') return;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_parent.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            var res = JSON.parse(xhr.responseText);

            if(res.status === 'found'){
                document.getElementById('parent_name').value   = res.parent_name;
                document.getElementById('parent_uin').value    = res.parent_uin;
                document.getElementById('parent_number').value = res.parent_number;
                document.getElementById('parent_status').innerHTML = 
                    '<span class="text-success"><i class="fa fa-check"></i> Parent Found</span>';
            } else {
                document.getElementById('parent_name').value   = '';
                document.getElementById('parent_uin').value    = '';
                document.getElementById('parent_number').value = '';
                document.getElementById('parent_status').innerHTML = 
                    '<span class="text-danger"><i class="fa fa-times"></i> Parent Not Found</span>';
            }
        }
    };

    xhr.send('parent_email=' + encodeURIComponent(email));
});
</script>

                    <div class="modal-footer">
                        <button class="btn ripple btn-success" 
                                type="submit" name="add_student">Add Student</button>
                        <button class="btn ripple btn-danger" 
                                data-bs-dismiss="modal" type="button">Close</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End modal -->

</div>
<!-- COL END -->


                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                <a href="add_fee.php">
                                      <div class="card bg-danger img-card box-danger-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h3 class="mb-0 number-font">Add Fee</h3>
                           
                                            </div>
                                            <div class="ms-auto"> <i class="fa fa-shopping-cart text-white fs-30 me-2 mt-2"></i> </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                              
                            </div>
                            <!-- COL END -->
                        </div>
                        <!-- ROW CLOSED -->

                     

                        <!-- ROW-4 -->
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title mb-0">Student School Fees Record</h3>
                                    </div>
                                    <div class="card-body pt-4">
                                        <div class="grid-margin">
                                            <div class="">
                                                <div class="panel panel-primary">
                                                    <div class="tab-menu-heading border-0 p-0">
                                                        <div class="tabs-menu1">
                                                            <!-- Tabs -->
                                                            <ul class="nav panel-tabs product-sale">
                                                                <li><a href="#tab5" class="active"
                                                                        data-bs-toggle="tab">All Students</a></li>
                                                                <li><a href="#tab6" data-bs-toggle="tab"
                                                                        class="text-dark">Balanced</a></li>
                                                                <li><a href="#tab7" data-bs-toggle="tab"
                                                                        class="text-dark">Deptors</a></li>
                                                                <li><a href="#tab8" data-bs-toggle="tab"
                                                                        class="text-dark">Deptors</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body tabs-menu-body border-0 pt-0">
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="tab5">
                                                                <div class="table-responsive">
                                                                    <table id="data-table"
                                                                        class="table table-bordered text-nowrap mb-0">
                                                                        <thead class="border-top">
                                                                            <tr>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Tracking Id</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Product</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Customer</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Date</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Amount</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Payment Mode</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 10%;">Status</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765490</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/10.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Headsets</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cherry Blossom</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">30 Aug
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$6.721.5</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block"> 
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #76348798</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/12.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Flower Pot</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Simon Sais</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">15 Nov
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$35,7863</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #23986456</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/4.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Pen Drive</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Manny Jah</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">27 Jan
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$5,89,6437</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #87456325</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/8.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                New Bowl</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Florinda Carasco</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">19 Sep
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$17.98</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #65783926</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/6.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Leather Watch</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Ivan Notheridiya</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">06 Oct
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #34654895</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/1.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Digital Camera</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Willie Findit</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">10 Jul
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765345</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/11.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Earphones</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Addie Minstra</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">25 Jun
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$7,34,9768</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #67546577</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/2.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Shoes</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Laura Biding</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">22 Feb
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$7.76.654</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab6">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-bordered text-nowrap mb-0">
                                                                        <thead class="border-top">
                                                                            <tr>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Tracking Id</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Product</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Customer</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Date</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Amount</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Payment Mode</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 10%;">Status</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765490</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/10.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Headsets</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cherry Blossom</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">30 Aug
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$6.721.5</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #76348798</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/12.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Flower Pot</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Simon Sais</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">15 Nov
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$35,7863</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #23986456</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/4.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Pen Drive</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Manny Jah</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">27 Jan
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$5,89,6437</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #87456325</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/8.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                New Bowl</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Florinda Carasco</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">19 Sep
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$17.98</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #65783926</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/6.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Leather Watch</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Ivan Notheridiya</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">06 Oct
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #34654895</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/1.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Digital Camera</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Willie Findit</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">10 Jul
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765345</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/11.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Earphones</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Addie Minstra</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">25 Jun
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$7,34,9768</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #67546577</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/2.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Shoes</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Laura Biding</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">22 Feb
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$7.76.654</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-success-transparent rounded-pill text-success p-2 px-3">Shipped</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab7">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-bordered text-nowrap mb-0">
                                                                        <thead class="border-top">
                                                                            <tr>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Tracking Id</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Product</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Customer</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Date</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Amount</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Payment Mode</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 10%;">Status</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765490</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/10.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Headsets</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cherry Blossom</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">30 Aug
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$6.721.5</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #23986456</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/4.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Pen Drive</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Manny Jah</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">27 Jan
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$5,89,6437</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #87456325</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/8.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                New Bowl</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Florinda Carasco</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">19 Sep
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$17.98</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #65783926</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/6.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Leather Watch</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Ivan Notheridiya</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">06 Oct
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #34654895</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/1.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Digital Camera</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Willie Findit</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">10 Jul
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765345</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/11.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Earphones</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Addie Minstra</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">25 Jun
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$7,34,9768</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #67546577</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/2.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Shoes</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Laura Biding</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">22 Feb
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$7.76.654</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-warning-transparent rounded-pill text-warning p-2 px-3">Pending</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab8">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-bordered text-nowrap mb-0">
                                                                        <thead class="border-top">
                                                                            <tr>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Tracking Id</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Product</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Customer</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Date</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Amount</th>
                                                                                <th
                                                                                    class="bg-transparent border-bottom-0">
                                                                                    Payment Mode</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 10%;">Status</th>
                                                                                <th class="bg-transparent border-bottom-0"
                                                                                    style="width: 5%;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #98765490</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/10.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Headsets</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cherry Blossom</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">30 Aug
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$6.721.5</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #76348798</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/12.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Flower Pot</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Simon Sais</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">15 Nov
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$35,7863</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #23986456</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/4.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Pen Drive</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Manny Jah</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">27 Jan
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$5,89,6437</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #87456325</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/8.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                New Bowl</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Florinda Carasco</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">19 Sep
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$17.98</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Online Payment</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #65783926</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/6.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Leather Watch</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Ivan Notheridiya</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">06 Oct
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border-bottom">
                                                                                <td class="text-center">
                                                                                    <div class="mt-0 mt-sm-2 d-block">
                                                                                        <h6
                                                                                            class="mb-0 fs-14 fw-semibold">
                                                                                            #34654895</h6>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <span class="avatar bradius"
                                                                                            style="background-image: url(assets/images/orders/1.jpg)"></span>
                                                                                        <div
                                                                                            class="ms-3 mt-0 mt-sm-2 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Digital Camera</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Willie Findit</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><span class="mt-sm-2 d-block">10 Jul
                                                                                        2021</span></td>
                                                                                <td><span
                                                                                        class="fw-semibold mt-sm-2 d-block">$8.654.4</span>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex">
                                                                                        <div
                                                                                            class="mt-0 mt-sm-3 d-block">
                                                                                            <h6
                                                                                                class="mb-0 fs-14 fw-semibold">
                                                                                                Cash on Delivery</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="mt-sm-1 d-block">
                                                                                        <span
                                                                                            class="badge bg-danger-transparent rounded-pill text-danger p-2 px-3">Cancelled</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="g-2">
                                                                                        <a class="btn text-primary btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Edit"><span
                                                                                                class="fe fe-edit fs-14"></span></a>
                                                                                        <a class="btn text-danger btn-sm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-original-title="Delete"><span
                                                                                                class="fe fe-trash-2 fs-14"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ROW-4 END -->
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

        </div>

        <!-- Sidebar-right -->
        <div class="sidebar sidebar-right sidebar-animate">
            <div class="panel panel-primary card mb-0 shadow-none border-0">
                <div class="tab-menu-heading border-0 d-flex p-3">
                    <div class="card-title mb-0"><i class="fe fe-bell me-2"></i><span
                            class=" pulse"></span>Notifications</div>
                    <div class="card-options ms-auto">
                        <a href="javascript:void(0);" class="sidebar-icon text-end float-end me-3 mb-1"
                            data-bs-toggle="sidebar-right" data-target=".sidebar-right"><i
                                class="fe fe-x text-white"></i></a>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
                    <div class="tabs-menu border-bottom">
                        <!-- Tabs -->
                        <ul class="nav panel-tabs">
                            <li class=""><a href="#sidebar-side1" class="active" data-bs-toggle="tab"><i
                                        class="fe fe-settings me-1"></i>Feeds</a></li>
                            <li><a href="#sidebar-side2" data-bs-toggle="tab"><i class="fe fe-message-circle me-1"></i> Chat</a></li>
                            <li><a href="#sidebar-side3" data-bs-toggle="tab"><i class="fe fe-anchor me-1"></i>Timeline</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="sidebar-side1">
                            <div class="p-3 fw-semibold ps-5">Feeds</div>
                            <div class="card-body pt-2">
                                <div class="browser-stats">
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span class="feeds avatar-circle brround bg-primary-transparent"><i
                                                    class="fe fe-user text-primary"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">New user registered</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-secondary brround bg-secondary-transparent"><i
                                                    class="fe fe-shopping-cart text-secondary"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">New order delivered</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-danger brround bg-danger-transparent"><i
                                                    class="fe fe-bell text-danger"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">You have pending tasks</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-warning brround bg-warning-transparent"><i
                                                    class="fe fe-gitlab text-warning"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">New version arrived</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-pink brround bg-pink-transparent"><i
                                                    class="fe fe-database text-pink"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">Server #1 overloaded</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-info brround bg-info-transparent"><i
                                                    class="fe fe-check-circle text-info"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">New project launched</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                    <a href="javascript:void(0)"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 fw-semibold ps-5">Settings</div>
                            <div class="card-body pt-2">
                                <div class="browser-stats">
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span class="feeds avatar-circle brround bg-primary-transparent"><i
                                                    class="fe fe-settings text-primary"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">General Settings</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-secondary brround bg-secondary-transparent"><i
                                                    class="fe fe-map-pin text-secondary"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">Map Settings</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-danger brround bg-danger-transparent"><i
                                                    class="fe fe-headphones text-danger"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">Support Settings</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-warning brround bg-warning-transparent"><i
                                                    class="fe fe-credit-card text-warning"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">Payment Settings</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-2 mb-sm-0 mb-3">
                                            <span
                                                class="feeds avatar-circle avatar-circle-pink brround bg-pink-transparent"><i
                                                    class="fe fe-bell text-pink"></i></span>
                                        </div>
                                        <div class="col-sm-10 ps-sm-0">
                                            <div class="d-flex align-items-end justify-content-between ms-2">
                                                <h6 class="">Notification Settings</h6>
                                                <div>
                                                    <a href="javascript:void(0)"><i class="fe fe-settings me-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="sidebar-side2">
                            <div class="list-group list-group-flush">
                                <div class="pt-3 fw-semibold ps-5">Today</div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/2.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Addie Minstra</div>
                                            <p class="mb-0 fs-12 text-muted"> Hey! there I' am available.... </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/11.jpg"><span
                                                class="avatar-status bg-success"></span></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Rose Bush</div>
                                            <p class="mb-0 fs-12 text-muted"> Okay...I will be waiting for you </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/10.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Claude Strophobia</div>
                                            <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/13.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Eileen Dover</div>
                                            <p class="mb-0 fs-12 text-muted"> New product Launching... </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/12.jpg"><span
                                                class="avatar-status bg-success"></span></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Willie Findit</div>
                                            <p class="mb-0 fs-12 text-muted"> Okay...I will be waiting for you </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/15.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Manny Jah</div>
                                            <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/4.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Cherry Blossom</div>
                                            <p class="mb-0 fs-12 text-muted"> Hey! there I' am available....</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="pt-3 fw-semibold ps-5">Yesterday</div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/7.jpg"><span
                                                class="avatar-status bg-success"></span></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Simon Sais</div>
                                            <p class="mb-0 fs-12 text-muted">Schedule Realease...... </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/9.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Laura Biding</div>
                                            <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/2.jpg"><span
                                                class="avatar-status bg-success"></span></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Addie Minstra</div>
                                            <p class="mb-0 fs-12 text-muted">Contact me for details....</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/9.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Ivan Notheridiya</div>
                                            <p class="mb-0 fs-12 text-muted"> Hi we can explain our new project......
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/14.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Dulcie Veeta</div>
                                            <p class="mb-0 fs-12 text-muted"> Okay...I will be waiting for you </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/11.jpg"></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Florinda Carasco</div>
                                            <p class="mb-0 fs-12 text-muted">New product Launching...</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="avatar avatar-md brround cover-image"
                                            data-bs-image-src="assets/images/users/4.jpg"><span
                                                class="avatar-status bg-success"></span></span>
                                    </div>
                                    <div class="">
                                        <a href="chat.html">
                                            <div class="fw-semibold text-dark" data-bs-toggle="modal"
                                                data-target="#chatmodel">Cherry Blossom</div>
                                            <p class="mb-0 fs-12 text-muted">cherryblossom@gmail.com</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="sidebar-side3">
                            <ul class="task-list timeline-task">
                                <li class="d-sm-flex mt-4">
                                    <div>
                                        <i class="task-icon1"></i>
                                        <h6 class="fw-semibold">Task Finished<span
                                                class="text-muted fs-11 mx-2 fw-normal">09 July 2021</span></h6>
                                        <p class="text-muted fs-12">Adam Berry finished task on<a href="javascript:void(0)"
                                                class="fw-semibold"> Project Management</a></p>
                                    </div>
                                    <div class="ms-auto d-md-flex me-3">
                                        <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                        <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                    </div>
                                </li>
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon1"></i>
                                        <h6 class="fw-semibold">New Comment<span
                                                class="text-muted fs-11 mx-2 fw-normal">05 July 2021</span></h6>
                                        <p class="text-muted fs-12">Victoria commented on Project <a href="javascript:void(0)"
                                                class="fw-semibold"> AngularJS Template</a></p>
                                    </div>
                                    <div class="ms-auto d-md-flex me-3">
                                        <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                        <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                    </div>
                                </li>
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon1"></i>
                                        <h6 class="fw-semibold">New Comment<span
                                                class="text-muted fs-11 mx-2 fw-normal">25 June 2021</span></h6>
                                        <p class="text-muted fs-12">Victoria commented on Project <a href="javascript:void(0)"
                                                class="fw-semibold"> AngularJS Template</a></p>
                                    </div>
                                    <div class="ms-auto d-md-flex me-3">
                                        <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                        <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                    </div>
                                </li>
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon1"></i>
                                        <h6 class="fw-semibold">Task Overdue<span
                                                class="text-muted fs-11 mx-2 fw-normal">14 June 2021</span></h6>
                                        <p class="text-muted mb-0 fs-12">Petey Cruiser finished task <a href="javascript:void(0)"
                                                class="fw-semibold"> Integrated management</a></p>
                                    </div>
                                    <div class="ms-auto d-md-flex me-3">
                                        <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                        <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                    </div>
                                </li>
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon1"></i>
                                        <h6 class="fw-semibold">Task Overdue<span
                                                class="text-muted fs-11 mx-2 fw-normal">29 June 2021</span></h6>
                                        <p class="text-muted mb-0 fs-12">Petey Cruiser finished task <a href="javascript:void(0)"
                                                class="fw-semibold"> Integrated management</a></p>
                                    </div>
                                    <div class="ms-auto d-md-flex me-3">
                                        <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                        <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                    </div>
                                </li>
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon1"></i>
                                        <h6 class="fw-semibold">Task Finished<span
                                                class="text-muted fs-11 mx-2 fw-normal">09 July 2021</span></h6>
                                        <p class="text-muted fs-12">Adam Berry finished task on<a href="javascript:void(0)"
                                                class="fw-semibold"> Project Management</a></p>
                                    </div>
                                    <div class="ms-auto d-md-flex me-3">
                                        <a href="javascript:void(0)" class="text-muted me-2"><span class="fe fe-edit"></span></a>
                                        <a href="javascript:void(0)" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/Sidebar-right-->

        <!-- Country-selector modal-->
        <div class="modal fade" id="country-selector">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content country-select-modal">
                    <div class="modal-header">
                        <h6 class="modal-title">Choose Country</h6><button aria-label="Close" class="btn-close"
                            data-bs-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <ul class="row p-3">
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block active">
                                    <span class="country-selector"><img alt="" src="assets/images/flags-img/us_flag.jpg"
                                            class="me-3 language"></span>USA
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/italy_flag.jpg"
                                        class="me-3 language"></span>Italy
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/spain_flag.jpg"
                                        class="me-3 language"></span>Spain
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/india_flag.jpg"
                                        class="me-3 language"></span>India
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/french_flag.jpg"
                                        class="me-3 language"></span>French
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/russia_flag.jpg"
                                        class="me-3 language"></span>Russia
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/germany_flag.jpg"
                                        class="me-3 language"></span>Germany
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt=""
                                        src="assets/images/flags-img/argentina.jpg"
                                        class="me-3 language"></span>Argentina
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt="" src="assets/images/flags-img/malaysia.jpg"
                                        class="me-3 language"></span>Malaysia
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="javascript:void(0)" class="btn btn-country btn-lg btn-block">
                                    <span class="country-selector"><img alt="" src="assets/images/flags-img/turkey.jpg"
                                        class="me-3 language"></span>Turkey
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Country-selector modal-->

        <!-- FOOTER -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>. Designed with <span
                            class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!-- FOOTER END -->

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SPARKLINE JS-->
    <script src="assets/js/jquery.sparkline.min.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CHART-CIRCLE JS-->
    <script src="assets/js/circle-progress.min.js"></script>

    <!-- PIETY CHART JS-->
    <script src="assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script src="assets/plugins/peitychart/peitychart.init.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- INTERNAL CHARTJS CHART JS-->
    <script src="assets/plugins/chart/Chart.bundle.js"></script>
    <script src="assets/plugins/chart/utils.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

    <!-- INTERNAL Data tables js-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>

    <!-- INTERNAL APEXCHART JS -->
    <script src="assets/js/apexcharts.js"></script>
    <script src="assets/plugins/apexchart/irregular-data-series.js"></script>

    <!-- INTERNAL Flot JS -->
    <script src="assets/plugins/flot/jquery.flot.js"></script>
    <script src="assets/plugins/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/plugins/flot/chart.flot.sampledata.js"></script>
    <script src="assets/plugins/flot/dashboard.sampledata.js"></script>

    <!-- INTERNAL Vector js -->
    <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- SIDE-MENU JS-->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL INDEX JS -->
    <script src="assets/js/index1.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

</body>

</html>