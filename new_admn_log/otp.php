
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP - Secure Auth</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <canvas id="star-canvas"></canvas>

  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <div class="auth-header">
        <h2>Enter Verification Code</h2>
        <p>We've sent a 6-digit code to your email.</p>
      </div>

      <form id="otp-form" novalidate method="post">
            <?php
            include("db_conn.php");
            error_reporting(E_ALL);
            if (isset($_REQUEST['verify_otp'])) {
                $n1 = $_REQUEST['n1'];
                $n2 = $_REQUEST['n2'];
                $n3 = $_REQUEST['n3'];
                $n4 = $_REQUEST['n4'];
                $n5 = $_REQUEST['n5'];
                $n6 = $_REQUEST['n6'];
                $newotp = $n1.$n2.$n3.$n4.$n5.$n6;
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
                        location.href='signup.php?email=$dbemail'</script>";
                    
                    }
            }
            ?>
        <div class="otp-inputs">
          <input type="text" name="n1" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
          <input type="text" name="n2" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
          <input type="text" name="n3" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
          <input type="text" name="n4" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
          <input type="text" name="n5" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
          <input type="text" name="n6" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
        </div>

        <button type="submit" class="btn-primary" name="verify_otp">
          <span class="btn-text">Verify Code</span>
          <div class="spinner"></div>
        </button>
      </form>

      <div class="auth-footer">
        Back to <a href="index.html">Sign in</a>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="script.js"></script>
</body>
</html>