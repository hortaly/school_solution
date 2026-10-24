
<?php 
include('db_conn.php');
date_default_timezone_set('Africa/Lagos');

if (isset($_REQUEST['email'])) {
    $sql = "SELECT * FROM glo_admn WHERE email = '$_REQUEST[email]'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $dbemail = $row['email'];
    $dbotp = $row['otp'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link href="images/favicon.png" rel="icon">
<title><?php echo $dbotp;?></title>
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
          <h4 class="text-center mb-3 mt-4">Please Enter OTP</h4>
          <p class="text-muted text-center mb-4">We have sent you one time password to your mobile number.</p>
          <form id="otp-screen" class="text-center" method="post">
            <?php
            include("db_conn.php");
            error_reporting(E_ALL);
            if (isset($_REQUEST['verify_otp'])) {
                $n1 = $_REQUEST['n1'];
                $n2 = $_REQUEST['n2'];
                $n3 = $_REQUEST['n3'];
                $n4 = $_REQUEST['n4'];
                $newotp = $n1.$n2.$n3.$n4;
                if ($dbotp != $newotp) {
                    echo"<script>alert('Invalid O.T.P');
                    location.href='otp-4.php';
                    </script>";
                }else {
                    
                    $sql="UPDATE glo_admn SET `status` ='Active' WHERE email ='$dbemail'";
                    
                    mysqli_query($conn,$sql) or die (mysqli_error($conn));
                    $num=mysqli_insert_id($conn);
                                        if(mysqli_affected_rows($conn)!=1){
                                            $message= "Error inserting the application information.";
                                            }
                          echo "<script>alert(' $dbemail You Can Now Provide Your Personal Information')
                        location.href='register_admn.php?email=$dbemail'</script>";
                    
                    }
            }
            ?>
            
            <label class="form-label">Enter 4 digit code</label>
			<div class="row g-3">
              <div class="col">
                <input type="text" class="form-control text-center text-6 py-2" maxlength="1" name="n1" required="" autocomplete="off">
              </div>
              <div class="col">
                <input type="text" class="form-control text-center text-6 py-2" maxlength="1" name="n2" required="" autocomplete="off">
              </div>
              <div class="col">
                <input type="text" class="form-control text-center text-6 py-2" maxlength="1" name="n3" required="" autocomplete="off">
              </div>
              <div class="col">
                <input type="text" class="form-control text-center text-6 py-2" maxlength="1" name="n4" required="" autocomplete="off">
              </div>
            </div>
            <div class="d-grid my-4">
				<button class="btn btn-primary" type="submit" name="verify_otp">Verify</button>
			</div>
          </form>
          <p class="text-center text-muted mb-2">Didn't get the code? <a href="#">Resend it</a></p>
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