<?php
session_start();

if (isset($_SESSION['username'])) {
  if ($_SESSION['user_role'] == 'Admin') {
    header('Location: admin/dashboard.php');
  } else if ($_SESSION['user_role'] == 'Librarian') {
    header('Location: librarian/borrow_return.php');
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign in & Sign up Form</title>
  <link rel="stylesheet" href="../assets/css/login.css" />
  
</head>

<body>
  <main>
    <div class="box">
      <div class="inner-box">
        <div class="forms-wrap">
          <form autocomplete="off" class="sign-in-form" id="logInForm">
            <div class="logo">
              <h3>aklatan.</h3>
            </div>

            <div class="heading">
              <h2>Login</h2>
              <h6>Enter your login details below</h6>

            </div>

            <div class="actual-form">
              <div class="input-wrap">
                <input
                  type="text"
                  minlength="4"
                  class="input-field"
                  autocomplete="off"
                  id="username"
                  placeholder="Username" />

              </div>

              <div class="input-wrap">
                <input
                  type="password"
                  minlength="4"
                  class="input-field"
                  autocomplete="off"
                  id="password"
                  placeholder="********" />

              </div>

              <input type="submit" value="Sign In" class="sign-btn" />

              <p class="text">
                Forgotten your password or you login datails?
                <a href="#">Get help</a> signing in
              </p>
            </div>
          </form>


        </div>

        <div class="carousel">
          <div class="images-wrapper">
            <img src="../assets/images/image1.jpg" class="image img-1 show" alt="" />

          </div>


        </div>
      </div>
    </div>
  </main>

  <!-- Javascript file -->

  <script src="../assets/js/login.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>