<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Secure Auth</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <canvas id="star-canvas"></canvas>

  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      </div>
      <div class="auth-header">
        <h2>Verify your email</h2>
        <p>Enter your email and we'll send you an OTP code.</p>
      </div>

      <form id="email-form" novalidate method="post">
        <?php
            include("db_conn.php");
            error_reporting(E_ALL);
            $today = date("y");
            $rand = rand(100000,999999);
            $id = "ADMIN/".$today.'/'.$rand;
            if (isset($_REQUEST['verify_email'])) {
              $uin = trim(addslashes($_REQUEST['uin'] ?? ''));
              $email = trim(addslashes($_REQUEST['email']));
              $otp = $rand;
              
              $check = mysqli_query($conn,"SELECT * FROM glo_admn WHERE email = '$email'");
              $checkrows = mysqli_num_rows($check);
              if ($checkrows > 0) {
                echo"<script>alert('User Already Exists');</script>";
              }else {
                $sql = "INSERT INTO glo_admn (email, otp, `status`, uin)
                 VALUES('$email', '$otp', 'pending', '$uin')";
                $result= mysqli_query($conn, $sql);
                if ($result) {
                  echo "<script>
                alert('An An OTP has been sent to $email');
                location.href='otp.php?email=$email';
              </script>";
                }else{
                  echo"<script>
                  alert('Error Inserting Records');
                   location.href='email_register.php';
                   </script>";
                }
              }
            }
        ?>
      <input type="hidden" value="<?php echo $id?>" name="uin" id="">
        <div class="form-group">
          <label for="reset-email">Email Address</label>
          <div class="input-wrapper">
            <input type="email" id="reset-email" placeholder="name@example.com" required name="email">
          </div>
          <div class="error-message"></div>
        </div>

        <button type="submit" class="btn-primary" name="verify_email">
          <span class="btn-text">Send Code</span>
          <div class="spinner"></div>
        </button>
      </form>

      <div class="auth-footer">
        Remembered your password? <a href="index.html">Sign in</a>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="script.js"></script>
</body>
</html>