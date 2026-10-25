<?php 
include('db_conn.php');
date_default_timezone_set('Africa/Lagos');

$dbemail = '';
if (isset($_REQUEST['email'])) {
    $email_param = mysqli_real_escape_string($conn, $_REQUEST['email']);
    $sql = "SELECT * FROM glo_admn WHERE email = '$email_param'";
    $result = mysqli_query($conn, $sql);
    if($result && $row = mysqli_fetch_array($result)) {
        $dbemail = $row['email'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Secure Auth</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <canvas id="star-canvas"></canvas>

  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
      </div>
      <div class="auth-header">
        <h2 id="wizard-title">Create Account</h2>
        <p id="wizard-subtitle">Step 1 of 2: Basic Information</p>
      </div>

      <form id="signup-form" novalidate method="post" enctype="multipart/form-data">

      <?php 
      if (isset($_POST['register'])) {
          $email = mysqli_real_escape_string($conn, $_REQUEST['email']);
          $fullname = mysqli_real_escape_string($conn, $_REQUEST['fullname']);
          $password = mysqli_real_escape_string($conn, $_REQUEST['password']);
          $con_password = mysqli_real_escape_string($conn, $_REQUEST['con_password']);
          $phone_number = mysqli_real_escape_string($conn, $_REQUEST['phone_number']);

          // Check password match
          if($password != $con_password){
              echo "<script>alert('Passwords do not match');</script>";
          } else {
              // Upload image
              $photo = $_FILES["photo"]['name'];
              $tmp_name = $_FILES["photo"]['tmp_name'];
              $target = "photos/" . basename($photo);

              if(move_uploaded_file($tmp_name, $target)){
                  $sql = "UPDATE glo_admn SET 
                  fullname = '$fullname',
                  role = 'Super Admin',
                  password = PASSWORD('$password'),
                  photo = '$photo',
                  phone_number = '$phone_number'
                  WHERE email = '$email'";

                  $result = mysqli_query($conn, $sql);

                  if($result){
                      echo "<script>
                      alert('$fullname Login Account Successfully Updated');
                      window.location.href='index.php';
                      </script>";
                  } else {
                      echo "<script>alert('Database Error');</script>";
                  }
              } else {
                  echo "<script>alert('Image Upload Failed');</script>";
              }
          }
      }
      ?>

        <!-- Wizard Step 1 -->
        <div class="wizard-step active" id="step-1">
          <div class="form-group">
            <label for="fullname">Full Name</label>
            <div class="input-wrapper">
              <input type="text" id="fullname" placeholder="Otali Joshua" name="fullname" required>
            </div>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrapper">
              <input type="email" id="email" placeholder="name@example.com" name="email" 
              value="<?php echo htmlspecialchars($dbemail); ?>" readonly>
            </div>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <div class="input-wrapper">
              <input type="number" id="phone_number" placeholder="0901923222" name="phone_number" required>
            </div>
            <div class="error-message"></div>
          </div>

          <button type="button" class="btn-primary" id="next-step-btn">
            Next Step
          </button>
        </div>

        <!-- Wizard Step 2 -->
        <div class="wizard-step" id="step-2" style="display: none;">
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <input type="password" id="password" name="password" placeholder="At least 8 chars" required>
              <button type="button" class="toggle-password" data-target="password">Show</button>
            </div>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <div class="input-wrapper">
              <input type="password" id="confirm-password" name="con_password" placeholder="Re-enter password" required>
              <button type="button" class="toggle-password" data-target="confirm-password">Show</button>
            </div>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="photo">Profile Photo</label>
            <div class="input-wrapper">
              <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
            </div>
            <div class="error-message"></div>
          </div>

          <div class="wizard-actions">
            <button type="button" class="btn-secondary" id="prev-step-btn">Back</button>
            <button type="submit" class="btn-primary" name="register">
              <span class="btn-text">Create Account</span>
              <div class="spinner"></div>
            </button>
          </div>
        </div>
      </form>

      <div class="auth-footer">
        Already have an account? <a href="index.html">Sign in</a>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="script.js"></script>
</body>
</html>