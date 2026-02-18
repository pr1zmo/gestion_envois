<?php
require ("../connection/conn.php");
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../views/index.php");
}
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
    <?php include("../styles/fade_in.css") ?>

</style>
<body class="fade-in" onload="load()">
<div id="root"></div>
<?php include("../components/header.employe.php") ?>
<div class="main">
    <?php include("../components/sidebar.employee.php"); ?>
        <div class="container-fluid">
<!--            <object data="../assets/histoiredelaposte.pdf" type="application/pdf" width="100%" height="600px">-->
<!--                <p>Unable to display PDF. Click <a href="path/to/your/file.pdf">here</a> to download.</p>-->
<!--            </object>-->
            <iframe src="../assets/histoiredelaposte.pdf" width="100%" height="600px" aria-controls="false">
            </iframe>
        </div>
</div>
</body>
<script src="../scripts/script.js">
</script>
</html>
