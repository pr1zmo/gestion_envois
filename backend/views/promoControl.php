<?php
require("../connection/conn.php");
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../views/index.php");
}
if(isset($_SESSION['promo_added'])){
    echo "<div class='alert alert-success'>".$_SESSION['promo_added']."</div>";
}
if(isset($_SESSION['delete_promo'])){
    echo $_SESSION['delete_promo'];
}
unset($_SESSION['delete_promo']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/10027750d5.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!--    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">-->
    <title>Promotion</title>
</head>
<style>
  <?php include("../styles/dashboard.css"); ?>
  <?php include("../styles/side_admin.css"); ?>
  /**{*/
  /*    border: 1px solid white;*/
  /*}*/
  .position-absolute{
      z-index: 4958498594589;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0px 0px 50px 1px red;
      border-bottom-right-radius: 7px;
      border-bottom-left-radius: 7px;
      text-align: center;
      animation: anni .4s forwards;
      animation-delay: 3s;
      transition: .4s;
  }
  @keyframes anni {
      0%{
          bottom: auto;
      }
      100%{
          bottom: 100%;
          box-shadow: none;
      }
  }
    body{
        overflow: hidden;
        color: white;
    }
  th, td{
      border: 1px solid black;
      padding: 4px 8px;
  }
  .promo{
      width: 100%;
      padding-left: 24px;
  }
  .content{
      height: 80vh;
      overflow-y: scroll;
  }
</style>

<body>
  <?php include("../components/header.admin.php"); ?>
<div class="main">
    <?php include("../components/sidebar.admin.php"); ?>
<!--    <iframe src="../assets/histoiredelaposte.pdf" width="100%" height="100%"></iframe>-->
    <div class="promo">
        <div class="p-2">
            <a href="../views/addpromo.php" class="btn btn-primary"><i class="fa-solid fa-plus p-lg-1"></i> Add Promo</a>
        </div>
        <div class="content">
            <?php
            $stmt = $pdo->prepare("select * from `discount_table` where 1");
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($result)){
                ?>
                <table border="1">
                        <tr>
                            <th>id</th>
                            <th>Percentage</th>
                            <th>date de debut</th>
                            <th>date de fin</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                <?php
                foreach ($result as $row){
                    ?>
                        <tr>
                            <td><?php echo $row['id'] ?></td>
                            <td><?php echo $row['amount'] ?> %</td>
                            <td><?php echo $row['start_date'] ?></td>
                            <td><?php echo $row['end_date'] ?></td>
                            <td class="<?php if($row['status']=='ongoing'){echo "bg-success";}else if($row['status']=='not yet started'){echo "bg-warning";}else if($row['status']=='ended'){echo "text-decoration-line-through bg-dark text-light";} ?>"><?php echo $row['status'] ?></td>
                            <td><a href="../controllers/deletePromo.php?id=<?php echo $row['id'] ?>" onclick="return confirm('are you sure you want to delete this Promo')" class="m-lg-1 btn btn-primary bg-danger"><i class="fa-solid fa-trash"></i>delete</a></td>
                        </tr>
                    <?php
                }
                ?></table><?php
            }else{
                echo "<h1>No Discounts</h1>";
            }
            ?>
        </div>
    </div>
</div>
</body>