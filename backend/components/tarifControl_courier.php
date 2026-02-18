<div class="text-light">
    <table class="table text-light table-striped table-responsive table-dark w-50">
        <caption class="caption-top text-light">Table Courier</caption>
        <tr>
            <th>Weight range</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php
        $stmt = $pdo->prepare("SELECT * from `tarif_par_produit_courier`");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row){
            ?>
            <tr><td><?php echo $row['from']."  KG  ---->".$row['to'] ?>  KG</td>
                <td><?php echo $row['price'] ?></td>
                <td class="me d-flex m-auto w-100">
                    <a href="../views/editCourier.php?id=<?php echo $row['id']?>" class="btn btn-primary bg-primary me-1 "><img src="../assets/edit.svg" height="20px"/></a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>