<?php
require("../connection/conn.php");
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../views/index.php");
}
if(isset($_SESSION['update_message'])){
    if($_SESSION['updated']){
        echo "<div class='message-success'> ".$_SESSION['update_message']."</div>";
    } else {
        echo "<div class='message-failed''> ".$_SESSION['update_message']."</div>";
    }
}
unset($_SESSION["update_message"]);
if(isset($_SESSION['add_message'])){
    if($_SESSION['user_added']){
    echo "<div class='message-success'>".$_SESSION['add_message']."</div>";
    }
else {
        echo "<div class='message-failed'> ".$_SESSION['add_message']."</div>";
    }}
unset($_SESSION['add_message']);
//if (isset($_SESSION['message'])) {
//    if ($_SESSION['messageType'] == 'success') {
//        echo "<div id='message-success' class='message-success'>" . $_SESSION['message'] . "</div>";
//    } elseif ($_SESSION['messageType'] == 'error') {
//        echo "<div id='message-success' class='message-error'>" . $_SESSION['message'] . "</div>";
//    }
//}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Document</title>
</head>
<style>
  <?php include("../styles/dashboard.css"); ?>
  <?php include("../styles/userControl.css"); ?>
  <?php include("../styles/side_admin.css"); ?>
  .side_nav{
    height: 100%;
    position: sticky;
    top: 0;
  }
  body{
    overflow: hidden;
  }
  .main{
    overflow: scroll;
  }
  .add_user{
    transition: .3s ease;
    right: 48px;
    box-sizing: border-box;
    box-shadow: 0px 0px 20px 4px black
  }
  .add_user:hover{
    transform: scale(1.1);
  }
  .add_user a{
    text-decoration: none;
    color: white;
  }
  .password input{
    width: 0;
  }
  table{
      border-bottom: 56px solid transparent;
  }
</style>

<body>
  <?php include("../components/header.admin.php"); ?>
  <div class="btn btn-primary add_user bg-success"><a href="../views/adduser.php">Add user <span class="badge badge-light">+</span></a></div>
  <div class="main">
    <div class="side_nav">
    <?php include("../components/sidebar.admin.php"); ?>
    </div>
    <div>
    <h1 class="text-center" style="color: white">Employees Manager</h1>
    <div class="row">
      <div class="col">
        <table class='table table-dark table-striped'>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Agency</th>
            <th>Status</th>
            <th>action</th>
          </tr>
          <?php
          $stmt = $pdo->prepare("SELECT * FROM agent");
          $stmt->execute();
          $rows = $stmt->fetchAll();
          foreach ($rows as $row) {
              if($row['status'] != 'inactive' or $row['status']='') { ?>
                      <tr>
                        <td>
                          <?php echo $row['code_agent']; ?>
                        </td>
                        <td>
                          <?php echo $row['name']; ?>
                        </td>
                        <td>
                          <?php echo $row['email']; ?>
                        </td>
                        <td class="passwordd">
                          <input class="form-input w-75" type="password" value="<?php echo $row['password']; ?>"
                            id="passwordd_<?php echo $row['code_agent']; ?>">
                          <?php
                  echo "<button type='button' class='a-weir' onclick='togglePassword(`passwordd_" . $row['code_agent'] . "`)'>
                ";
                  ?>
                <img src="../assets/eye-open.svg" height="20px" />
                </button>
              </td>
              <td>
              <?php
              $row_agency = $row['code_agency'];
                $stmt2 = $pdo->prepare("SELECT `nom_agency` from `agency` where `agency`.`code_agency`=$row_agency");
                $stmt2->execute();
                $code = $stmt2->fetch(PDO::FETCH_ASSOC);
                 echo $row['code_agency']; ?><br>
                 (<?php echo $code['nom_agency'] ; ?>)
              </td>
              <td>
                <?php echo $row['status']; ?>
              </td>

              <td class='td'>
                <form method="post" action="../views/editUser.php">
                  <input type="hidden" name="code_agent" value="<?php echo $row['code_agent'] ?>">
                  <button type="submit" class="btn btn-primary bg-primary" href="#">
                    <img src="../assets/edit.svg" height="20px" />
                  </button>
                </form>
                <form onsubmit="submit_form(event)" method="post" action="../controllers/deleteUser.php">
                  <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-primary bg-danger"
                    href="#">
                    <img src="../assets/delete.svg" height="20px" />
                    <input type="hidden" value="<?php echo $row['code_agent'] ?>" name="user_code" />
                  </button>
                </form>
              </td>
            </tr>
                    <?php
            } else {
            }
        }
        ?>

        </table>
            
      </div>
    </div>
  </div>
  </div>
      </div>
  </div>
  <script>
        function togglePassword(id) {
          var passwordField = document.getElementById(id);
          var button = passwordField.nextElementSibling;
          if (passwordField.type === "password") {
            passwordField.type = "text";
            button.innerHTML = '<img src="../assets/eye-closed.svg" height="20px" />';
          } else {
            passwordField.type = "password";
            button.innerHTML = '<img src="../assets/eye-open.svg" height="20px" />';
          }
        }
        </script>


    // function sendValue(myValue) {
    //   var value = myValue; // the value you want to send
    //   var xhr = new XMLHttpRequest(); // create a new XMLHttpRequest object
    //   xhr.open('POST', '../controllers/deleteUser.php', true); // set the request method, the URL, and whether the request should be asynchronous
    //   xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // set the request header
    //   xhr.onreadystatechange = function () { // set the callback function to handle the response
    //     if (xhr.readyState == 4 && xhr.status == 200) {
    //       console.log(xhr.responseText); // display the response in the console
    //     }
    //   };
    //   xhr.send('value=' + encodeURIComponent(value)); // send the request with the value as a parameter
    // }

    // const delete_form = document.querySelector(".delete-form");
    // const delete_button = document.querySelector(".delete");
    // function submit_form(event) {
    //   event.preventDefault();
    //   e.preventDefault();
    //   delete_button.addEventListener("click", (event) => {
    //     alert("Are you sure you want to delete?")
    //     event.preventDefault();
    //     sendValue(value)
    //   })
    // }
    // function confirmDelete(value) {
    //   var modal = document.getElementById("modal");
    //   modal.style.display = "block";
    // }

  </script>

</body>
<!-- 

 -->