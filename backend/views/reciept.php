<?php
include("../connection/conn.php");
session_start();
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM `package` WHERE `id`=$id");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" href="bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <title>Reciept</title>
</head>
<style>
    *{
        /*border: 1px solid black;*/
    }
    body{
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table{
        border: 1px solid black;
        border-collapse: collapse;
    }
    td, th{
        border: 1px solid black;
    }
    .card{
        background: #ffffcc !important;
    }
    .special{
        border: none !important;
        padding-top: 12px !important;
    }
    #table th{
        padding-right: 175px;
    }
    #table *{
        border: none;
    }
    #details{
        border: 1px solid black;
        border-radius: 5px;
    }
    #details p{
        margin: 0;
    }
    #details .row{
        margin-bottom: 12px;
    }
    #details .col{
        margin-left: 12px;
    }
</style>
<body>
<div class="card w-50 bg-secondary m-5">
    <div class="card-header d-flex align-items-center justify-content-around">
        <img src="../assets/poste_logo.png" />
        <div>
            <h5>Merci d'avoir choisi Poste maroc</h5>
        </div>
    </div>
    <div class="card-body">
        <h3 class="text-center">Monsieur <?PHP echo $result['expediteur'] ?></h3>
        <div>Product id: <?php echo $result['code_package'] ?></div>
        <p class="bg-dark text-light rounded-1 text-center p-2">Veuillez conserver une copie de ce reçu.</p>
        <hr>
        <div>
                Order details:
            <div id="details" class="container">
                <div class="row">
                    <div class="col align-items-start border-dark border-1 d-flex flex-column  text-start p-0">
                        <p class="bold">order id:</p>
                        <p> <?php echo $result['code_package'] ?></p>
                    </div>
                    <div class="col align-items-start border-dark border-1 d-flex flex-column  text-start p-0">
                        <p class="bold">Bill to:</p>
                        <p>Monsieur  <?php echo $result['destinataire'] ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col align-items-start border-dark border-1 d-flex flex-column  text-start p-0">
                        <p class="bold">order date:</p>
                        <p> <?php echo $result['date'] ?></p>
                    </div>
                    <div class="col align-items-start border-dark border-1 d-flex flex-column   text-start p-0">
                        <p class="bold">Source:</p>
                        <p>Poste maroc ( <?php echo $result['destination'] ?>)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1">
            Description:
            <table class="table table-striped border-1 border-dark">
                <tr>
                    <th>Type</th>
                    <th>Prix</th>
                </tr>
                <tr>
                    <td> <?php echo $result['type'] ?></td>
                    <td> <?php echo $result['prix'] ?> DH</td>
                </tr>
            </table>
        </div>
        <table id="table" class="table border-dark d-flex align-items-end justify-content-end">
            <tr>
                <th>Total:</th>
                <th> <?php echo $result['prix'] ?> DH</th>
            </tr>
        </table>
        <hr>
        <div class="text-center">
            BARID AL-MAGHRIB S.A  (Poste Maroc).<br>

            Siège social : av Moulay Ismail, 10020-RABAT, Maroc.<br>

            Tél (Fixe): 0537 210 202 / 0537 210 530<br>

            Fax : 0537 204 089 / 0537 210 53<br>
        </div>
        <hr>
        <div class="text-end">
            <a href="../views/home.php" class="btn btn-danger text-center">Return</a>
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <button class="text-center btn btn-success" type="submit" id="printButton" onclick="print()">Download <i class="fas fa-download"></i></button>
        </div>
    </div>
</div>
</body>
<script>
    import { jsPDF } from "jspdf";
    // window.jsPDF = window.jspdf.jsPDF;
    const doc = new jsPDF({
        orientation: "portrait",
        unit: "in",
        format: [34, 4]
    });

    // doc.text("Hello world!", 1, 1);
    doc.save("two-by-four.pdf");
    // var docPDF = new jsPDF();
    // function print(){
    //     var elementHTML = document.querySelector("#printTable");
    //     docPDF.html(elementHTML, {
    //         callback: function(docPDF) {
    //             docPDF.save('HTML Linuxhint web page.pdf');
    //         },
    //         x: 15,
    //         y: 15,
    //         width: 170,
    //         windowWidth: 650
    //     });
    // }
</script>
</html>
