<?php
require("../connection/conn.php");
var_dump($_POST);
session_start();
if($_SERVER['REQUEST_METHOD']=="POST"){
    $stmt1 = $pdo->prepare("SELECT `id`, `code_package`, `expediteur`, `type`, `prix`, `status`, `date`, `destinataire` FROM `package` WHERE `date`>=:date_debut AND `date`<=:date_fin");
    $stmt1->execute(['date_debut'=>$_POST['date_debut'], 'date_fin'=>$_POST['date_fin']]);
    $row = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['total'] = 0;
    foreach ($row as $total_prix){
        $_SESSION['total'] = $_SESSION['total']+$total_prix['prix'];
    }
    $_SESSION['data'] = $row;
    $_SESSION['date_debut'] = $_POST['date_debut'];
    $_SESSION['date_fin'] =  $_POST['date_fin'];
    $_SESSION['error'] = "No Package was found";

    header("Location: ../views/liste_envoie.php");
    exit();
}
