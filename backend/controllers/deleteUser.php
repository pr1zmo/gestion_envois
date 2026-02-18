<?php
require('../connection/conn.php');
session_start();
if (!isset($_POST['user_code'])) {
  header("Location: ../views/userControl.php");
}
/** @var TYPE_NAME $pdo */
$stmt = $pdo->prepare("UPDATE `agent` SET `agent`.`status` = 'inactive' WHERE code_agent = :code");
$stmt->execute(['code' => $_POST['user_code']]);
if (!empty($stmt)) {
  $_SESSION['messageType'] = "success";
  $_SESSION['message'] = "Agent deleted Successfully";
} else {
  $_SESSION['messageType'] = "failure";
  $_SESSION['message'] = "An error occured";
}
header("Location: ../views/userControl.php");