<div id="halo"><?php
session_start();
include("../connection/conn.php");
if(isset($_SESSION['user_added'])){
    if($_SESSION['user_added']){
    if(isset($_SESSION['user_added'])){
        echo "<div class='message-success'>".$_SESSION['user_added']. "</div>";
    }
    }
}
?>
</div>
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
    <?php include("../styles/dashboard.css") ?>
    <?php include("../styles/side_admin.css") ?>
    body{
        /*overflow: hidden;*/
        /* background: green !important; */
    }
    .form-control{
        flex-grow: 1;
        width: 800px;
        overflow-y: auto;
    }
    #error_message{
        position: absolute;
        left: 0;
        top: 0;
        padding: 12px;
        z-index: 8989898;
    }
    .form-control form{
        width: 600px;
        overflow: auto;
    }
    .bg-light {
  background: #eef0f4;
}

.choices__list--dropdown .choices__item--selectable {
  padding-right: 1rem;
}

.choices__list--single {
  padding: 0;
}

.card {
  transform: translateY(-50%);
}

.choices[data-type*=select-one]:after {
  right: 1.5rem;
}

.shadow {
  box-shadow: 0.3rem 0.3rem 1rem rgba(178, 200, 244, 0.23);
}

</style>

<body>
<?php include("../components/header.admin.php") ?>
<?php
// var_dump($row);
// var_dump($row["email"]);
// echo "<br><hr>";
// var_dump($_SESSION);
?>
<div class="main">
    <?php include("../components/sidebar.admin.php"); ?>
    <div class="form-control bg-dark text-light w-25 h-auto align-content-center">
        <h1>Add user</h1>
        <form class="form-control w-auto h-auto bg-dark text-light" method="POST" action="../controllers/adduser.php">

            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" placeholder="Name" required/>
            <?php if(isset($_SESSION['add_message_name']) and $_SESSION['add_message_name']=="This name already exists"){
                echo "<p class='alert alert-danger border-1 p-2 mt-2 w-25'>".$_SESSION['add_message_name']."</p>";
            } ?>
            <?php
            unset($_SESSION['add_message_name']);
            ?>


            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="email" id=""  required/>
            <?php if(isset($_SESSION['add_message_email']) and $_SESSION['add_message_email']=="This email already exists"){
                echo "<p class='alert alert-danger border-1 p-2 mt-2 w-25'>".$_SESSION['add_message_email']."</p>";
            } ?>
            <?php
            unset($_SESSION['add_message_email']);
            ?>
            <label for="password" class="form-label">Password:</label>
            <input type="text" name="password" class="form-control" placeholder="password" id="" required/>


            <label for="code_agency" class="form-label">Agency:</label>
            <select name="code_agency" class="form-label selectpicker form-control border-0 rounded shadow" required>
                <?php
                $stmt = $pdo->prepare("SELECT `nom_agency`, `code_agency` from `agency`");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($row){
                  // echo $row["nom_agency"];
                  foreach($row as $rows){
                    echo "<option value=".$rows['code_agency'].">".$rows['nom_agency']."</option>";
                  }};
              ?>
              <!-- <input type="text" value="<?php echo $_SESSION['code_agency'] ?>" name="code_agency" /> -->
            </select><br>

            <label for="status" class="form-label">Status:</label>



            <select name="status" class="form-control" id="" required>
                <?php $stmt2 = $pdo->prepare("SHOW COLUMNS from `agent` WHERE FIELD='status'");
                $stmt2->execute();
                $row_status = $stmt2->fetchAll(PDO::FETCH_ASSOC);?><?php
                foreach ($row_status as $row){
                    $statusEnum = [];
                    if (preg_match_all("/'([^']+)'/", $row['Type'], $matches)) {
                        $statusEnum = $matches[1];
                    }

                    print_r($statusEnum);
                    foreach ($statusEnum as $option_status){?>
                        <option value="<?php echo $option_status ?>"><?php echo $option_status ?></option><?php
                    }
                }
                ?>
            </select>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary bg-primary" name="confirm">Confirm changes</button>
                <a href="../views/userControl.php" class="btn btn-primary bg-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>