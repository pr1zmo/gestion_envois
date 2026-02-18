<?php
require ("../connection/conn.php");
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../views/index.php");
}
$stmt2 = $pdo->prepare("SELECT SUM(`prix`) from `package` where `date`= current_date and `code_agent`=:code_agent");
$stmt2->execute(['code_agent'=>$_SESSION['agent_id']]);
$total = $stmt2->fetch(PDO::FETCH_ASSOC);
$_SESSION['total_sales'] = $total;
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
    <?php include("../styles/dashboard.css") ?>
    <?php include("../styles/sidebar_style.css") ?>
    <?php include("../styles/liste_envoies.css") ?>
    .header{
        z-index: 9999;
    }
    .selection{
        font-size: 16px !important;
    }
</style>
<body onload="load()" class="overflow-hidden">
<div id="root"></div>
<?php include("../components/header.employe.php") ?>
<div class="main">
    <?php include("../components/sidebar.employee.php") ?>
    <div class="container overflow-x-scroll" id="list_envoies">
        <?php
        $stmt = $pdo->prepare("SELECT * from `package` WHERE `code_agent`=:code_agent");
        $stmt->execute(['code_agent'=>$_SESSION['agent_id']]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <form action="../controllers/filter_dates.php" method="post" class="form w-5 d-flex align-items-center" id="selection">
            <label for="date_debut">Start date:</label>
            <input value="<?= isset($_SESSION['date_debut']) ? $_SESSION['date_debut'] : '' ?>" type="date" class="form-items text-dark" name="date_debut" required/>
            <label for="date_fin">End date:</label>
            <input value="<?= isset($_SESSION['date_fin']) ? $_SESSION['date_fin'] : '' ?>" type="date" class="form-items text-dark" name="date_fin" required/>
            <button class="btn btn-light text-dark">Search</button>
            <button type="button" class="btn btn-danger text-light" onclick="location.reload()">Clear search</button>
        </form>
        <table class="table table-bordered table-responsive table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Expediteur</th>
                <th>Type</th>
                <th>Prix</th>
                <th>Status</th>
                <th>Date</th>
                <th>Destinataire</th>
                <th>reciept</th>
            </tr>
        <?php
        if(empty($_SESSION['data'])){
            if(!empty($_SESSION['error'])){
                echo "<script>alert('".$_SESSION['error']."')</script>";
                unset($_SESSION['error']);
            }
            foreach($row as $rows){
                ?>
                <tr>
                    <td><?php echo $rows['code_package'] ?></td>
                    <td><?php echo $rows['expediteur'] ?></td>
                    <td><?php echo $rows['type'] ?></td>
                    <td><?php echo $rows['prix']; ?><?php if($rows['old_price']!=0){echo "<span class='ms-2 bg-primary rounded-3 p-2 text-decoration-line-through'>".$rows['old_price']."</span>";} ?></td>
                    <td class="<?php if($rows['status']=='pending'){echo 'bg-warning';}else if($rows['status']=='delivered'){echo 'bg-success';}else if($rows['status']){echo 'bg-secondary';} ?>"><?php echo $rows['status'] ?></td>
                    <td><?php echo $rows['date'] ?></td>
                    <td><?php echo $rows['destinataire'] ?></td>
                    <td><a href="../views/reciept.php?id=<?php echo $rows['id'] ?>">Download reciept</a></td>
                </tr>
                <?php
            }
        } else {
            foreach($_SESSION['data'] as $new_data){?>
                <tr>
                    <td>
                        <?php echo $new_data['code_package'] ?>
                    </td>
                    <td><?php echo $new_data['expediteur'] ?></td>
                    <td><?php echo $new_data['type'] ?></td>
                    <td><?php echo $new_data['prix'] ?></td>
                    <td class="<?php if($new_data['status']=='pending'){echo 'bg-warning';}else if($new_data['status']=='delivered'){echo 'bg-success';}else if($new_data['status']){echo 'bg-secondary';} ?>"><?php echo $new_data['status'] ?></td>
                    <td><?php echo $new_data['date'] ?></td>
                    <td><?php echo $new_data['destinataire'] ?></td>
                    <td><a href="../views/reciept.php?id=<?php echo $new_data['id'] ?>">Download reciept</a></td>
            </tr>
                <?php
                unset($_SESSION['data']);
            }
        }
        unset($_SESSION['date_debut'], $_SESSION['date_fin']);
        ?>
        </table>
    <?php
        if(isset($_SESSION['total']) and ($_SESSION['total']!=0) ){?>
            <div id="today" class="selection">Total by Selection: <?php echo $_SESSION['total']; ?></div><?php
        } else{
            ?>
            <div id="today">Total Today: <?php echo (implode(",", $_SESSION['total_sales'])==null?"0":implode(",", $_SESSION['total_sales'])); ?></div>
        <?php
        }
        unset($_SESSION['total']);
    ?>

    </div>
</div>
</body>
<script>
    require("../javascript/app.js");
</script>
</html>
