<?php
session_start();
require("../connection/conn.php");
$stmt = $pdo->prepare("update `tarif_par_produit_courier` set `price` = :price where `id`=:id");
$stmt->execute(['price'=>$_POST['prix'], 'id'=>$_POST['id']]);
if($stmt){
    $_SESSION['message'] = "success";
} else {
    $_SESSION['message'] = "failed";
}
header("Location: ../views/tarifControl.php");
