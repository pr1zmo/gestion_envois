<?php
require("../connection/conn.php");
session_start();
$_SESSION['code_package'] = "result";
ob_start();
if (isset($_SESSION)) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $expediteur = $_POST['expediteur'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        $destination = $_POST['destination'];
        $date = $_POST['date'];
        $destinataire = $_POST['destinataire'];
        $address_destinataire = $_POST['address-des'];
        $code_agent = $_POST['code_agent'];
        $code_agency = $_POST['code_agency'];
        $fragile = $_POST['fragile'];
        $cache_en_delivery = $_POST['cache_en_delivery'];
        if (isset($fragile)) {
            $fragile = 1;
        } else {
            $fragile = 0;
        }
        if (isset($cache_en_delivery)) {
            $cache_en_delivery = 1;
        } else {
            $cache_en_delivery = 0;
        }
        $stmt = $pdo->prepare("INSERT INTO `package`(`expediteur`, `type`, `prix`, `destination`, `date`, `code_agent`, `code_agency`, `fragile`, `cache_en_delivery`, `destinataire`, `destinataire_adress`) VALUES (:expediteur,:type,:price,:destination,:date,:code_agent,:code_agency,:fragile,:cache_en_delivery,:destinataire,:destinataire_adress)");


        $stmt->execute([
            'expediteur' => $expediteur,
            'type' => $type,
            'price' => $price,
            'destination' => $destination,
            'date' => $date,
            'code_agent' => $code_agent,
            'code_agency' => $code_agency,
            'destinataire' => $destinataire,
            'destinataire_adress' => $address_destinataire,
            'fragile' => $fragile,
            'cache_en_delivery' => $cache_en_delivery
        ]);
        $last_id = $pdo->lastInsertId();
        if($type=="courier"){
            $code_package = 'CR' . str_pad($last_id, 11 - strlen($last_id), '0', STR_PAD_LEFT) . 'MA';
        } elseif ($type=="colis"){
            $code_package = 'CL' . str_pad($last_id, 11 - strlen($last_id), '0', STR_PAD_LEFT) . 'MA';
        };
        $stmt2=$pdo->prepare("update `package` set `code_package`='$code_package' where `id`=$last_id");
        $stmt2->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($row)) {
            $_SESSION['message_success'] = "Votre commande a été bien effectué<br><a href='download.php'>telechager ici</a>";
            $_SESSION['worked'] = true;
            $_SESSION['last_id'] = $last_id;
        } else {
            $_SESSION['message_failed'] = "Une erreur est survenue";
            $_SESSION['worked'] = false;
        }
        header("Location: ../views/dashboard.php");
    }
}
ob_flush();
