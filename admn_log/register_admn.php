<?php
include("db_conn.php");
if (isset($_REQUEST['email'])) {
    $sql = "SELECT * FROM glo_admn WHERE email = '$_REQUEST[email]'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $dbemail = $row['email'];

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link href="images/favicon.png" rel="icon">
<title>Oxyy - Login and Register Form Html Template</title>
<meta name="description" content="Login and Register Form Html Template">
<meta name="author" content="harnishdesign.net">

<!-- Web Fonts
========================= -->
<link rel='stylesheet' href='../../css-1.css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i' type='text/css'>

<!-- Stylesheet
========================= -->
<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
<!-- Colors Css -->
<link id="color-switcher" type="text/css" rel="stylesheet" href="#">
</head>
<body>

<!-- Preloader -->
<div class="preloader">
  <div class="lds-ellipsis">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>
</div>
<!-- Preloader End -->

<div id="main-wrapper" class="oxyy-login-register h-100 d-flex flex-column bg-transparent">
  <div class="container my-auto">
    <div class="row">
      <div class="col-md-9 col-lg-7 col-xl-5 mx-auto">
        <div class="bg-white shadow-md rounded p-4 px-sm-5 mt-4">
          <div class="logo"> <a class="d-flex justify-content-center" href="index.php" title="Oxyy"><img src="images/logo-lg.png" alt="Oxyy"></a> </div>
          <hr class="mx-n4 mx-sm-n5">
          <p class="lead text-center">Looks like you're new here!</p>
          <form id="registerForm" method="post" enctype="multipart/form-data">
      <?php 

if (isset($_POST['register'])) {

    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $gender = trim($_POST['gender']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);
    $con_password = trim($_POST['con_password']);
    $phone_number = trim($_POST['phone_number']);

    // Check password match
    if($password != $con_password){
        echo "<script>alert('Passwords do not match')</script>";
        exit();
    }

    // Upload image
    $photo = $_FILES["photo"]['name'];
    $tmp_name = $_FILES["photo"]['tmp_name'];

    $target = "photos/" . $photo;

    if(move_uploaded_file($tmp_name, $target)){

        $sql = "UPDATE glo_admn SET 
        fullname = '$fullname',
        gender = '$gender',
        role = '$role',
        password = PASSWORD('$password'),
        photo = '$photo',
        phone_number = '$phone_number'
        WHERE email = '$_REQUEST[email]'";

        $result = mysqli_query($conn, $sql);

        if($result){

            echo "<script>
            alert('$fullname Login Account Successfully Updated');
            window.location.href='login-4.php';
            </script>";

        }else{

            echo "<script>alert('Database Error')</script>";

        }

    }else{

        echo "<script>alert('Image Upload Failed')</script>";

    }

}
?>
                                           

        
            <div class="mb-3">
                <div class="step" id="step1">
                    <label for="emailAddress" class="form-label">Email Address</label>
             <input type="email" class="form-control" id="emailAddress"  value="<?php echo"$dbemail"?>" 
             placeholder="Enter Your Email" name="email" readonly> <br>

              <label for="emailAddress" class="form-label">Fullname</label>
             <input type="text" class="form-control" id="emailAddress" 
             placeholder="Enter Your Fullname" name="fullname" required> <br>
 
             <label for="emailAddress" class="form-label">Profile Picture</label>
             <input type="file" class="form-control" id="emailAddress" 
              name="photo" accept="image/*" required> <br>
              <button type="button" onclick="nextStep(1)" class="btn btn-primary">Next</button>
                </div>
              

             <div class="step" id="step2" style="display: none;">
               <label for="emailAddress" class="form-label">Gender</label>
              <input type="text" list="gender" name="gender" class="form-control" placeholder="Choose your Gender" required>
        <datalist id="gender">
            <option value="Male">
            <option value="Female">
    
        </datalist><br>
              <label for="emailAddress" class="form-label">Role</label>
              <input type="text" list="role" name="role" class="form-control" placeholder="Choose your Role" required>
        <datalist id="role">
            <option value="Super Admin">
            <option value="Head Teacher">
            <option value="Registrar">
        </datalist><br>

               <button type="button" onclick="prevStep(2)" class="btn btn-outline-secondary" >Back</button>
       <button type="button"  onclick="nextStep(2)" class="btn btn-primary">Next</button>


             </div>
               
             <div class="step" id="step3" style="display: none">
                <label for="emailAddress" class="form-label">Phone Number</label>
             <input type="number" class="form-control" id="emailAddress" 
             placeholder="Enter Your Phone Number" name="phone_number" required> <br>

                 <div class="password_wraper">
           <label for="emailAddress" class="form-label">Password</label>
             <input type="password" class="form-control" id="password" 
             placeholder="Enter Your Password" name="password" required>

             <span onclick="togglePassword('password')" id="too">👁</span>
         </div><br>
         <div class="password_wraper">
           <label for="emailAddress" class="form-label">Confirm Password</label>
             <input type="password" class="form-control" id="con_password" 
             placeholder="Confirm Your Password" name="con_password" required>

             <span onclick="togglePassword('con_password')" id="too">👁</span>
         </div>
        
        

         
          <button type="button" onclick="prevStep(3)" class="btn btn-outline-secondary" >Back</button>
          <button type="submit" name="register" class="btn btn-success btn-lg" >Register</button>

        <script type="text/javascript">
            function nextStep(current) {
                document.getElementById('step' + current).style.display = 'none';
                document.getElementById('step' + (current + 1)).style.display = 'block';
            }
            function prevStep(current) {
                document.getElementById('step' + current).style.display = 'none';
                document.getElementById('step' + (current - 1)).style.display = 'block';
            }
        </script>
            </div>
            
           

            <script type="text/javascript">
        function togglePassword(id) {
            let input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
            }else{
                input.type = 'password';
            }
        }

         </script>
         <style>
            .password_wrapper{
                position: relative;
                display: flex;
                align-items: center;
            }
            #too{
                
                cursor: pointer;
            }
         </style>
          </form>
          <!-- <p class="text-center text-muted mb-2">Already have an account? <a href="login-4.php">Log In</a></p> -->
        </div>
      </div>  
    </div>
  </div>
  <div class="container-fluid py-3">
    <p class="text-center text-2 text-muted mb-0">Copyright © 2021 <a href="#">Oxyy</a>. All Rights Reserved.</p>
  </div>
</div>

<!-- Styles Switcher -->
<div id="styles-switcher" class="left">
  <h5>Color Switcher</h5>
  <hr>
  <ul class="mb-0">
    <li class="blue" data-bs-toggle="tooltip" title="Blue" data-path="#"></li>
    <li class="indigo" data-bs-toggle="tooltip" title="Indigo" data-path="css/color-indigo.css"></li>
    <li class="purple" data-bs-toggle="tooltip" title="Purple" data-path="css/color-purple.css"></li>
    <li class="pink" data-bs-toggle="tooltip" title="Pink" data-path="css/color-pink.css"></li>
    <li class="red" data-bs-toggle="tooltip" title="Red" data-path="css/color-red.css"></li>
    <li class="orange" data-bs-toggle="tooltip" title="Orange" data-path="css/color-orange.css"></li>
    <li class="yellow" data-bs-toggle="tooltip" title="Yellow" data-path="css/color-yellow.css"></li>
    <li class="teal" data-bs-toggle="tooltip" title="Teal" data-path="css/color-teal.css"></li>
    <li class="green" data-bs-toggle="tooltip" title="Green" data-path="css/color-green.css"></li>
    <li class="cyan" data-bs-toggle="tooltip" title="Cyan" data-path="css/color-cyan.css"></li>
    <li class="brown" data-bs-toggle="tooltip" title="Brown" data-path="css/color-brown.css"></li>
  </ul>
  <button class="btn switcher-toggle"><i class="fas fa-cog"></i></button>
</div>
<!-- Styles Switcher End --> 

<!-- Script --> 
<script src="vendor/jquery/jquery.min.js"></script> 
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> 
<!-- Style Switcher --> 
<script src="js/switcher.min.js"></script> 
<script src="js/theme.js"></script>
      
           
</body>
</html>