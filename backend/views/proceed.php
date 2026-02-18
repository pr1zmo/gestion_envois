<?php 
require("../connection/conn.php");
session_start();
if(!isset($_SESSION['email'])){
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <title>Localhost</title>
  </head>
  <style>
    <?php include("../styles/style.css"); ?>
    <?php include("../styles/sidebar_style.css"); ?>
    <?php include("../styles/proceed.css"); ?>

  </style>
</html>
<body style="overflow-y:auto">
<?php include("../components/header.admin.php"); ?>
<div class="main">
<?php include("../components/sidebar.employee.php"); ?>

      <div class="message">
<!--          --><?php //if(isset($_SESSION['message'])){
//              echo "<script>alert(`".$_SESSION['message']."`)</script>";
//          } ?>
      </div>
    <div class="didi"></div>
    <div class="container-fluid">
      <div class="form-holder">
        <div class="form-content">
          <div class="form-items">
            <h3 class="text-center">Proceed The order</h3>
            <form
              class="requires-validation"
              action="../controllers/proceed.php"
              method="post"
            >
                <input value="<?php echo $_GET['type']; ?>" type="hidden" name="type" />
                <input value="<?php echo $_SESSION['agent_id']; ?>" type="hidden" name="code_agent" />
                <input value="<?php echo $_SESSION['agency_id']; ?>" type="hidden" name="code_agency" />
                <input value="<?php echo $_GET['price']; ?>" type="hidden" name="price" />
              <div class="col-md-12">
              <label class="form-check-label" for="expediteur">
                Sender
              </label>
                <input
                  class="text-dark from-control"
                  name="expediteur"
                  type="text"
                  placeholder="sender's name"
                  required
                />
              </div>
              <div class="row g-3" style=" display:flex!important;justify-content:space-between!important;">
                <div class="col">
                <label class="form-check-label" for="type">
                    Type:
                </label>
                <input
                  value="<?php echo $_GET['type']; ?>"
                  class="new-inputleft text-dark from-control"
                  type="text"
                  placeholder="Type"
                  disabled
                />
                </div>
                <div class="col">
                <label class="form-check-label" for="code_agency">
                    Prix
                </label>
                <input
                  value="<?php echo $_GET['price']; ?>"
                  class="new-inputright text-dark from-control"
                  type="text"
                  placeholder="Prix"
                  disabled
                />
                </div>
              </div>
              <div class="col-md-12">
              <div class="row g-3" style=" display:flex!important;justify-content:space-between!important;">
              <div class="col">
              <label class="form-check-label" for="destination">
                Destination
              </label>
                <select
                  class="text-dark from-select"
                  name="destination"
                  type="text"
                  placeholder="Destination"
                  required
                >
                  <option value="" disabled selected>
                    select a Destination
                  </option>
                  <?php
                    $stmt = $pdo->prepare("SELECT * from `agency`");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    if($row){
                      // echo $row["nom_agency"];
                      foreach($row as $rows){
                        echo "<option value=".$rows['nom_agency'].">".$rows['nom_agency']."</option>";
                    }}
                  ?>
                </select>
              </div>
                    <div class="col">
                      <label for="destinataire" class="form-check-label">Destinataire</label>
                      <input
                  class="new-inputright text-dark from-control"
                  type="text"
                  name="destinataire"
                  placeholder="Destinataire"
                  required
                />
                    </div>
            </div>
              </div>
              <div class="col-md-12">
                <label for="address-des" class="form-check-label">address du destinataire</label>
                <input 
                    class="text-dark form-control"
                    type="text"
                    name="address-des"
                    placeholder="address du destinataire"
                    required
                />
              </div>
              <div class="col-md-12">
<!--              <label class="form-check-label" for="code_agency">-->
<!--                date-->
<!--              </label>-->
                <input type="hidden" name="date" value="<?php echo date("Y/m/d") ?>">
                <input type="text" disabled value="<?php echo date("Y/m/d") ?>">
              </div>
              <div class="col-md-12">
                <div class="row g-3" style=" display:flex!important;justify-content:space-between!important;">
                  <div class="col">
                  <label class="form-check-label" for="code_agent">
                    Agent
                  </label>
                  <?php 
                  $agent_id  =$_SESSION['agent_id'];
                  $agency_id = $_SESSION['agency_id'];
                  $stmt_nom_agent = $pdo->prepare("SELECT agent.`name` from agent WHERE `agent`.`code_agent` = $agent_id");
                  $stmt_nom_agent->execute();
                  $row = $stmt_nom_agent->fetch(PDO::FETCH_ASSOC);
                  if($row){
                    $new_agent_name = $row['name'];
                  }else{
                    echo "error";
                  }
                  ?>
                  <input type="text" id="" value="<?php echo $new_agent_name ?>" disabled>
                  </div>
                  <div class="col">
                  <label class="form-check-label" for="code_agency">
                    Code agency
                  </label>
                  <?php 
                  $agency  =$_SESSION['agency_id'];
                  $stmt_nom_agency = $pdo->prepare("SELECT agency.`nom_agency` from agency WHERE `agency`.`code_agency` = $agency_id");
                  $stmt_nom_agency->execute();
                  $row = $stmt_nom_agency->fetch(PDO::FETCH_ASSOC);
                  if($row){
                    $new_agency_name = $row['nom_agency'];
                  }else{
                    echo "error";
                  }
                  ?>
                  <input type="text" id="" value="<?php echo  $new_agency_name?>" disabled>

                  </div>
                </div>
              </div>
              <div class="col-md-12">
              <div class="row g-3" style=" display:flex!important;justify-content:space-between!important;">
                    <div class="col">
                    <input class="form-check-input" type="checkbox" name="fragile" value="" id="fragile">
                  <label class="form-check-label" for="fragile">
                    Fragile
                  </label>
                    </div>
                    <div class="col">
                    <input class="form-check-input" type="checkbox" name="cache_en_delivery" value="" id="cache_en_delivery">
              <label class="form-check-label" for="cache_en_delivery">
                cash on delivery
              </label>

                    </div>
            </div>
              </div>
              <div class="form-button mt-3">
                <a href="dashboard.php" class="btn btn-danger">< Go back</a>
                <button id="submit" type="submit" class="btn btn-primary">
                  Porceed
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
