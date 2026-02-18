<?php
session_start();
require("../connection/conn.php");
if(!isset($_POST['id'])){
    header("Location: ../views/editUser.php");
}
;
// UPDATE `agent`
// SET `name` = 'Milissent Germanny',
// `email` = 'mgermann@skyrock.com',
// `password` = 'dWRnrw45#@', `code_agency` = '5', `status` = 'deceased' WHERE `agent`.`code_agent` = 8;
$stmt = $pdo->prepare("UPDATE `agent` SET `name` = ?, `email` = ?, `password` = ?, `status` = ? WHERE `agent`.`code_agent` = ?");
try{
    $stmt->execute([$_POST["name"], $_POST["email"], $_POST["password"], $_POST["status"], $_POST["id"]]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['update_message'] = "User Updated successfully";
    $_SESSION['updated'] = true;
} catch (PDOException $e){
    $_SESSION['err'] = $e;
    $_SESSION['update_message'] = "This username or email already exist";
    $_SESSION['updated'] = false;
}
header("Location: ../views/userControl.php");
exit();

