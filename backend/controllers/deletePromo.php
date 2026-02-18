<?php
require("../connection/conn.php");
session_start();
$id = $_GET['id'];
$stmt=$pdo->prepare("DELETE FROM `discount_table` WHERE `id`=$id");
$stmt->execute();
try{
    $_SESSION['delete_promo'] = "<div class='position-absolute w-25 align-self-center alert alert-danger'>Promo deleted</div>";
}catch(PDOException $e){
    echo "an error occurred".$e;
}
header("Location: ../views/promoControl.php");