<?php
session_start();
require("../connection/conn.php");

$stmt=$pdo->prepare("INSERT INTO agent(`name`, `email`, `password`, `code_agency`, `status`) VALUES (?,?,?,?,?)");
// $rs = $_POST[];
// $stmt2 = $pdo->prepare("SELECT `code_agency` from `agency` where `nom_agency`LIKE '$_POST['code_agency']%'");
// $stmt2->execute([]);
// $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result['code_agency']);
try{
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['password'], $_POST['code_agency'], $_POST['status']]);
    $_SESSION['add_message'] = "user added succesfully";
    $_SESSION['user_added'] = true;
} catch(PDOException $e){
    $_SESSION['weeee'] = $e->getCode();
    $_SESSION['user_added'] = false;
    if($e->getCode() !== null){
        if($e->getCode() == '45003'){
            $_SESSION['add_message_name'] = "This name already exists";
            $_SESSION['add_message_email'] = "This email already exists";
            return;
        }
        if($e->getCode() == '45001'){
            $_SESSION['add_message_name'] = "This name already exists";
        } else if($e->getCode() == '45002'){
            $_SESSION['add_message_email'] = "This email already exists";
        }
    }
header("Location: ../views/adduser.php");
//    var_dump($e->getCode());
exit();
}
header("Location: ../views/userControl.php");
exit();