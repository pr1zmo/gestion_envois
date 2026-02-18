<div style="display: none" class="msg">
<?php
require("../connection/conn.php");
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?></div>
<?php
if(isset($_SESSION['inactive_user'])){
  echo "<div class='inactive_user'>".$_SESSION['inactive_user']."</div>";
  unset($_SESSION["inactive_user"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion commandes</title>
</head>
<style>
  <?php require("../styles/style.css") ?>
  <?php require("../styles/fade_in.css") ?>

    .form-holder{
        overflow: hidden;
    }
  body, html{
      background: rgb(2,0,36);
      background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,121,71,1) 35%, rgba(0,255,137,1) 100%);
  }

</style>
<body class="fade-in">
<div class='header'>
    <div><img src="../assets/poste_logo.png" /></div>
    <div>
      <a href="../views/admin.php" title="go to admin dashboard" class="btn btn-warning">admin</a>
    </div>
</div>
<div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Login</h3>
                        <p>Fill in the data below.</p>
                        <form class="requires-validation" action="../controllers/login.php" method="post">
                            <div class="col-md-12">
                                <input onchange="check()" class="text-dark form-control" type="email" name="email" placeholder="E-mail Address" required>
                            </div>
                           <div class="col-md-12">
                              <input onchange="check()" class="text-dark form-control" type="password" name="password" placeholder="Password" required>
                           </div>
                           <div>
                            <?php if(isset($_SESSION['error'])){
                              echo '<div class="text-danger pt-2">'.$_SESSION['error'] . '</div>';
                              unset($_SESSION['error']);
                            } ?>
                           </div>
                            <div class="form-button mt-3">
                                <button id="submit" type="submit" class="btn btn-success bg-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
function check() {
  "use strict";
  const forms = document.querySelectorAll(".requires-validation");
  Array.from(forms).forEach(function (form) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add("was-validated");
  });
}



</script>
<script src="../scripts/script.js"></script>
</html>