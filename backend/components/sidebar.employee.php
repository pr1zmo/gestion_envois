<?php
$current_url = $_SERVER['REQUEST_URI'];
?>
<div class="d-flex flex-column flex-shrink-0 p-3 text-white side-bar">
    <div class="side-bar-inner">
        <div class="side-bar-content">
            <ul class="side-bar-menu">
                <li class="one side-bar-menu-item">
                  <a href="../views/home.php" class="text-white side-bar-menu-item-link text-decoration-none text-center <?php echo ($current_url=="/gestion_envoie/views/home.php")?"active_side_bar":"" ?>"><i class="fa fa-home"></i>Home</a>
                </li>
                <li class="two side-bar-menu-item">
                  <a href="../views/dashboard.php" class="text-white side-bar-menu-item-link text-decoration-none text-center <?php echo ($current_url=="/gestion_envoie/views/dashboard.php")?"active_side_bar":"" ?>"><i class="fa fa-home"></i>Ajouter</a><!-- END SIDEBAR MENU ITEM -->
                </li>
                <li class="two side-bar-menu-item">
                    <a href="../views/liste_envoie.php" class="text-white side-bar-menu-item-link text-decoration-none text-center <?php echo ($current_url=="/gestion_envoie/views/liste_envoie.php")?"active_side_bar":"" ?>"><i class="fa fa-home"></i>Envois</a><!-- END SIDEBAR MENU ITEM -->
                </li>
            </ul>
        </div>
    </div>
</div>