<?php
require("../connection/conn.php");
session_start();
if(isset($_SESSION['message'])){
    echo "<div id='alert' class='alert alert-dark position-absolute w-50 align-self-center border-3 border-dark' style='z-index: 99898989'>".$_SESSION['message']."</div>";
}
unset($_SESSION['message']);
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
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap');
    <?php require("../styles/dashboard.css"); ?>
    <?php include("../styles/side_admin.css"); ?>
    <?php include("../styles/radio.css"); ?>
    body{
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    th, td{
        border: 1px solid white;
    }
    table{
        margin-left: 250px;
    }
    #alert{
        animation: anni .3s forwards;
        animation-delay: 3s;
    }
    @keyframes anni {
        0%{
            opacity: 1;
            pointer-events: all;
        }
        100%{
            opacity: 0;
            pointer-events: none;
        }
    }
    .main{
        flex-grow: 1;
        width: 100%;
        display: flex;
        flex-direction: row;
    }
    .header{
        /* height: 80px */
    }
    .page{
        font-family: 'Montserrat', sans-serif;
        flex: 1;
        font-weight: 1000 !important;
    }
    .page form{
        margin-inline: 20px
    }
    .page form a{
        width: 200px;
        padding: 12px;
    }
    .page form a:hover{
        background-color: blue !important;
    }
    td, th{
        text-align: center;
    }
    .me{
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
    }
    .me a{
        transition: .3s ease;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;

    }
    .me a:hover{
        transform: scale(1.1);
    }
</style>
<body>
<header>

    <?php include("../components/header.admin.php"); ?>
</header>
<div class="main">
    <?php include("../components/sidebar.admin.php"); ?>
    <div class="page">
        <div class="wrapper">
            <form class="form-control d-flex flex-row bg-transparent border-0" type="post">
                <input type="radio" name="radios" value="colis" id="option-1">
                <input type="radio" name="radios" value="courier" id="option-2">
                <label for="option-1" class="option option-1">
                    <div class="dot"></div>
                    <span>Colis</span>
                </label>
                <label for="option-2" class="option option-2">
                    <div class="dot"></div>
                    <span>Courier</span>
                </label>
                <button class="btn btn-primary bg-light text-dark" type="submit">Go</button>


            </form>
        </div><br>
        <?php
            if(!empty($_GET['radios'])){
                if($_GET['radios']=="colis"){
                    include("../components/tarifControl_colis.php");
                } else if($_GET['radios'] == "courier"){
                    include("../components/tarifControl_courier.php");
                }
            }
        ?>
    </div>
</div>
</body>
</html>