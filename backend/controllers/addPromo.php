<?php
require("../connection/conn.php");
session_start();
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
if (strtotime($end_date) < strtotime($start_date)){
    $_SESSION['my_err'] = "<div class='position-absolute w-25 align-self-center alert alert-danger'>Date is invalid</div>";
    header("Location:../views/addPromo.php");
    exit;
} else{
    try{
        if (isset($_POST['submit'])){
            $stmt=$pdo->prepare("INSERT INTO `discount_table`(`amount`, `start_date`, `end_date`) VALUES (:amount, :start_date, :end_date)");
            $stmt->bindParam(':amount', $_POST['amount']);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
        }
        $_SESSION['added_promo'] = "promo added successfully";
        header("Location: ../views/promoControl.php");
        exit;
    } catch(PDOException $e){
        echo $e->getMessage();
    }
}
?>