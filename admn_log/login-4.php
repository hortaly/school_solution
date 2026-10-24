<?php
session_name('ADMINSESS');
session_start();
include("db_conn.php");

if (isset($_POST['identifier']) && isset($_POST['password'])) {
    $identifier = stripslashes($_POST['identifier']);
    $identifier = mysqli_real_escape_string($conn, $identifier);

    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if identifier matches either email or phone
$query = "
SELECT * FROM glo_admn 
WHERE 
(
    email = '$identifier' 
    OR phone_number = '$identifier'
)
AND password = PASSWORD('$password')
AND status = 'active'
LIMIT 1
";

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $user = mysqli_fetch_assoc($result);

        // Fresh session ID after login
        session_regenerate_id(true);

        // Set session variables
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['status'] = $user['status'];
        $_SESSION['uin'] = $user['uin'];
        $_SESSION['photo'] = $user['photo'];
        $_SESSION['phone_number'] = $user['phone_number'];
        $_SESSION['gender'] = $user['gender'];
        // $_SESSION['station'] = $user['station'];

        // If user was redirected due to timeout or not being logged in
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect_url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect_url");
            exit();
        }

        // Role-based redirection fallback
        if ($user['role'] == 'Super Admin') {
            header("Location: ../glo_admn/");
            exit();
        } elseif ($user['role'] == 'Head Teacher') {
            header("Location: ../headteach_app/");
            exit();
        } elseif ($user['role'] == 'Registrar') {
            header("Location: ../registrar_app/");
            exit();
        } else {
            echo "<script>alert('Unauthorized role. Please contact admin.'); 
            location.href='login-4.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid User Login Credential'); 
        location.href='login-4.php';</script>";
    }
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
          <div class="logo"> <a class="d-flex justify-content-center" href="index.html" title="Oxyy"><img src="images/logo-lg.png" alt="Oxyy"></a> </div>
          <hr class="mx-n4 mx-sm-n5">
          <p class="lead text-center">We are glad to see you again!</p>
          <form id="loginForm" method="post">
            <div class="mb-3">
              <label for="emailAddress" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="emailAddress" required="" placeholder="Enter Your Email">
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="loginPassword" required="" placeholder="Enter Password">
            </div>
            <div class="row mt-4">
              <div class="col">
                <div class="form-check">
                  <input id="remember-me" name="remember" class="form-check-input" type="checkbox">
                  <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
              </div>
              <div class="col text-end"><a href="forgot-password-4.html">Forgot Password ?</a></div>
            </div>
            <div class="d-grid my-4">
				<button class="btn btn-primary" type="submit">Sign In</button>
			</div>
          </form>
          <p class="text-muted text-center mb-2">Don't have an account? <a href="register-4.html">Sign Up</a></p>
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