<?php 
require("../connection/conn.php");
session_start();
if(!isset($_POST['username'])){
  header("Location: ../views/index.php");
}
if($_SERVER['REQUEST_METHOD']=="POST"){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE `username` = :username AND `password`= :password");
  $stmt->execute(['username'=>$username, 'password'=>$password]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row){
    $_SESSION['admin_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['password'] = $row['password'];
    header('location: ../views/dashboard.admin.php');
  }else {
    header("Location: ../views/admin.php");
  }
} 
?>