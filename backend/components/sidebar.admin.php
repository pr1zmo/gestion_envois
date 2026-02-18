<?php
$current_url = $_SERVER['REQUEST_URI'];
?>
<nav class="nav flex-column">
  <ul>
    <li>
      <a class="text-light text-center nav-link <?php if($current_url=="/Gestion_envoie/views/dashboard.admin.php"){echo "active";}else{echo "";} ?>" href="../views/dashboard.admin.php"><img src="../assets/home.svg" />Home</a>
    </li>
    <li>
      <a class="text-light text-center nav-link <?php if($current_url=="/gestion_envoie/views/userControl.php"){echo "active";}else{echo "";} ?>" href="../views/userControl.php"><img src="../assets/employee.svg" />Manage employees</a>
    </li>
    <li>
      <a class="text-light text-center nav-link <?php echo ($current_url == '/gestion_envoie/views/promoControl.php') ? "active_link" : ''; ?>" href="../views/promoControl.php"><img src="../assets/sale.svg" />Add Promo</a>
    </li>
      <li>
        <a class="text-light text-center nav-link <?php echo ($current_url == '/gestion_envoie/views/tarifControl.php') ? "active_link" : ''; ?>" href="../views/tarifControl.php"><img src="../assets/tarif.svg" />Gestion tarif</a>
      </li>
  </ul>
</nav>
