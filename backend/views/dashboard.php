<?php
require ("../connection/conn.php");
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../views/index.php");
}
if(isset($_SESSION['message_success'])){
    if($_SESSION['worked']){
if(isset($_SESSION["last_id"])){
    $id = $_SESSION['last_id'];
}
//        echo "<div class='alert alert-success text-center'>".$_SESSION['message_success']."<br><a class='link text-decoration-none' href='../views/reciept.php?id=$id'>download receipt</a></div>";
    }
}
if(isset($_SESSION['c_p'])){"hOIJFE ifjiejf hfiehfi".var_dump($_SESSION);};
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
    <?php include("../styles/home.css") ?>
    .alert{
        position: absolute;
        z-index: 9898989;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 4px;
        box-shadow: 0px 0px 50px 5px #d1e7dd;
        /*animation: hide .3s forwards;*/
        /*animation-delay: 5s;*/
    }
    @keyframes hide {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0;
            pointer-events: none;
        }
    }
</style>
<body onload="load()">
<div id="root"></div>
<?php include("../components/header.employe.php") ?>
<div class="main">
    <?php include("../components/sidebar.employee.php"); ?>
    <div class="container-fluid">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <h3>
                        Calculer le prix
                    </h3>
                    <form class="requires-validation" id="myForm" method="post">
                        <div class="col-md-12">
                            <input class="text-dark form-control"  value="<?php echo isset($_POST['poids']) ? $_POST['poids'] : ''; ?>" id="input" type="number" name="poids" placeholder="Poids" required>
                            <select name="type" id="type" required>
                                <option value="kg">Kg</option>
                                <option value="g">g</option>
                            </select>
                        </div>
                        <div>
                        </div>
                        <div class="form-button mt-3">
                            <button id="submit" type="submit" class="btn btn-primary">Afficher les prix</button>
                        </div>
                    </form>
                    <div id="result"></div>
                </div>
            </div>
            <div id="table">
                <table border=1>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>
                            colis
                        </td>
                        <td>
                            <?php
                            if(isset($_POST['poids'])){
                                $poids = $_POST['poids'];
                                $type = $_POST['type'];
                                if($type != "kg") {
                                    $poids = $poids / 1000;
                                }
                                $stmt2 = $pdo->prepare('SELECT `price` FROM `tarif_par_produit_colis` WHERE `from` < :lepoids AND `to`>= :lepoids');
                                $stmt2->execute(['lepoids' => $poids]);
                                $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                                if($row){
                                    echo "<div>".$row['price']."</div>";
                                    $courier_price = $row['price'];
                                }
                                else{
                                    echo "<div>-</div>";
                                }
                            } else {
                                echo "<script>document.querySelector('#table').style.display = 'none'</script>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if(!empty($courier_price)){
                                echo "<a 
                      href='proceed.php?type=colis&price=".$courier_price."' 
                      class='proceed'>Proceed</a>";
                            }else{
                                echo "<a href='#' class='proceed disabled-link'>Proceed</a>";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>courrier</td>
                        <td>
                            <?php
                            if(isset($_POST['poids'])){
                                $poids = $_POST['poids'];
                                $type = $_POST['type'];
                                if($type != "kg") {
                                    $poids = $poids / 1000;
                                }
                                global $pdo;
                                $stmt2 = $pdo->prepare('SELECT `price` FROM `tarif_par_produit_courier` WHERE `from` < :lepoids AND `to`>=:lepoids');
                                $stmt2->execute(['lepoids' => $poids]);
                                $username = $stmt2->fetch(PDO::FETCH_ASSOC);
                                if(!empty($username)){
                                    echo "<div>".$username['price']."</div>";
                                    $newprice = $username['price'];
                                }
                                else {
                                    echo "<div>-</div>";
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            // $link = "proceed/page.php?type=courier&" . http_build_query(array("theprice" => $row['price']));
                            //   echo "<a class='proceed' href='$link'>Proceed</a>";
                            if(!empty($newprice)){
                                echo "<a href='proceed.php?type=courier&price=".$newprice."' class='proceed'>Proceed</a>";
                            }else{
                                echo "<a href='#' class='proceed disabled-link'>Proceed</a>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
<script>
</script>
</html>
