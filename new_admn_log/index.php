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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Secure Auth</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Animated Shooting Star Background Canvas -->
  <canvas id="star-canvas"></canvas>

  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
      </div>
      <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Please enter your details to sign in.</p>
      </div>

      <form id="login-form" novalidate method="post">
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrapper">
            <input type="text" id="email" placeholder="name@example.com" required name="identifier">
          </div>
          <div class="error-message"></div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <input type="password" id="password" placeholder="••••••••" required name="password">
            <button type="button" class="toggle-password" data-target="password" >Show</button>
          </div>
          <div class="error-message"></div>
        </div>

        <div class="form-options">
          <span></span>
          <a href="email.html">Forgot password?</a>
        </div>

        <button type="submit" name="submit" class="btn-primary">
          <span class="btn-text">Sign In</span>
          <div class="spinner"></div>
        </button>
      </form>

      <div class="auth-footer">
        Don't have an account? <a href="email_register.php">Sign up</a>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="script.js"></script>
</body>
</html>