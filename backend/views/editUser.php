<div id="halo"><?php
session_start();
include("../connection/conn.php");

?>
</div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<style>
    <?php include("../styles/dashboard.css") ?>
    <?php include("../styles/side_admin.css") ?>
    body{
        overflow: hidden;
        /* background: green !important; */
    }
    .form-control{
        flex-grow: 1;
        width: 800px;
        overflow-y: auto;
    }
    #error_message{
        position: absolute;
        left: 0;
        top: 0;
        padding: 12px;
        z-index: 8989898;
    }
    .form-control form{
        width: 600px;
        overflow: auto;
    }
</style>

<body>
<?php include("../components/header.admin.php") ?>
<?php
$stmt = $pdo->prepare("SELECT * FROM agent WHERE code_agent = :codeAGENT");
$stmt->execute(['codeAGENT' => $_POST['code_agent']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$status_user = $row['status'];
// var_dump($row);
// var_dump($row["email"]);
// echo "<br><hr>";
// var_dump($_SESSION);
?>
<div class="main">
    <?php include("../components/sidebar.admin.php"); ?>
    <div class="form-control bg-dark text-light w-25 h-auto align-content-center">
        <h1>Edit user info</h1>
        <form class="form-control w-auto h-auto bg-dark text-light" method="POST" action="../controllers/editUser.php">
            <label for="id" class="form-label">ID:</label>
            <input type="text" class="form-control" disabled value="<?php echo $_POST['code_agent'] ?>" id="">
            <input type="hidden" name="id" class="form-control" value="<?php echo $_POST['code_agent'] ?>" id="">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo $row['name'] ?>" id="">
            <?php echo isset($_SESSION['err'])?$_SESSION['err']:false ?>

            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="email" value="<?php echo $row['email'] ?>"
                   id="">

            <label for="password" class="form-label">Password:</label>
            <input type="text" name="password" class="form-control" placeholder="password"
                   value="<?php echo $row['password'] ?>" id="">

            <label for="status" class="form-label">Status:</label>

            <select name="status" class="form-control" id="">
                <?php $stmt2 = $pdo->prepare("SHOW COLUMNS from `agent` WHERE FIELD='status'");
                $stmt2->execute();
                $row_status = $stmt2->fetchAll(PDO::FETCH_ASSOC);?><?php
                foreach ($row_status as $row){
                    $statusEnum = [];
                    if (preg_match_all("/'([^']+)'/", $row['Type'], $matches)) {
                        $statusEnum = $matches[1];
                    }

                    print_r($statusEnum);
                    foreach ($statusEnum as $option_status){?>
                        <option <?php echo ($status_user==$option_status)?"selected":'' ?> value="<?php echo $option_status ?>"><?php echo $option_status ?></option><?php
                    }
                }
                ?>
            </select>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary bg-primary" name="confirm">Confirm changes</button>
                <a href="../views/userControl.php" class="btn btn-primary bg-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>

</html>