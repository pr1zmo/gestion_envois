<?php
require("../connection/conn.php");
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../views/index.php");
}
if(isset($_SESSION['my_err'])){
    echo $_SESSION['my_err'];
}
unset($_SESSION['my_err']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/10027750d5.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">-->
    <title>Promotion</title>
</head>
<style>
    <?php include("../styles/dashboard.css"); ?>
    <?php include("../styles/side_admin.css"); ?>
    .position-absolute{
        z-index: 4958498594589;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0px 0px 50px 1px red;
        border-bottom-right-radius: 7px;
        border-bottom-left-radius: 7px;
        text-align: center;
        animation: anni .4s forwards;
        animation-delay: 3s;
        transition: .4s;
    }
    @keyframes anni {
        0%{
            bottom: auto;
        }
        100%{
            bottom: 100%;
            box-shadow: none;
        }
    }
    body{
        overflow: hidden;
        color: white;
    }
    th, td{
        border: 1px solid black;
        padding: 4px 8px;
    }
    form{
        display: flex;
        flex-direction: column;
        height: 80vh;
    }
    .main_form{
        height: 90vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<body>
<?php include("../components/header.admin.php"); ?>
<div class="main">
    <?php include("../components/sidebar.admin.php"); ?>
    <!--    <iframe src="../assets/histoiredelaposte.pdf" width="100%" height="100%"></iframe>-->
    <div class="main_form">
        <form onsubmit="validateForm()" action="../controllers/addPromo.php" class="form-control w-50 bg-dark text-light" method="post">
            <label for="amount" class="form-label">Percentage: </label>
            <input type="number" class="form-control" max="100" min="1" required name="amount" placeholder="Amount" />
            <label class="form-label" for="type">Type: </label>

            <label for="start_date" class="form-label">Start date: </label>
            <input class="form-control" id="start_date" name="start_date" type="date" placeholder="start_date"  required/>
            <label for="end_date" class="form-label">End date: </label>
            <input class="form-control" id="end_date" name="end_date" type="date" placeholder="end_date" required />
            <div class="mt-3">
                <button name="submit" type="submit" class="btn text-light bg-success">Add</button>
                <a href="../views/promoControl.php" class="mx-2 btn text-light bg-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
    function validateForm(e) {
    var startDate = new Date(document.getElementById("start_date").value);
    var endDate = new Date(document.getElementById("end_date").value);

    if (endDate < startDate) {
    alert("End date must be greater than or equal to start date");
    return false;
    }
    return true;
    }

</script>
</body>