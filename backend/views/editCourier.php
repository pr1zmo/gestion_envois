<?php
require("../connection/conn.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap');
    <?php require("../styles/dashboard.css"); ?>
    <?php include("../styles/side_admin.css"); ?>
    <?php include("../styles/radio.css"); ?>
    body{
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    th, td{
        border: 1px solid white;
    }
    .main{
        flex-grow: 1;
        width: 100%;
        display: flex;
        flex-direction: row;
    }
    .header{
        /* height: 80px */
    }
    .page_me{
        display: flex;
    }
</style>
<body>
<header>

    <?php include("../components/header.admin.php"); ?>
</header>
<div class="main">
    <?php include("../components/sidebar.admin.php"); ?>
    <div class="page_me justify-content-center">
        <?php
        $stmt = $pdo->prepare("select * from `tarif_par_produit_courier` where `id`=:id");
        $stmt->execute(['id'=>$_GET['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
    <form style="margin-left: 250px" class="p-4 form-control h-50 align-self-center bg-dark text-light d-flex flex-column" method="post" action="../controllers/tarifControl_courier.php">
            <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
            <label class="form-label" for="">Weight Range:</label>
            <div class="row">
                <div class="col"><input class="form-control" type="text" value="<?php echo $row['from'] ?>" disabled/></div>
                <div class="col"><input class="form-control" type="text" value="<?php echo $row['to'] ?>" disabled/></div>
            </div>
            <label>Prix</label>
            <input class="form-control mb-3" name="prix" value="<?php echo $row['price'] ?>" type="text" />
            <div>
                <a href="../views/tarifControl.php" class="btn btn-danger bg-danger">Cancel</a>
                <button type="submit" class="btn btn-success bg-success">Confirm</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>