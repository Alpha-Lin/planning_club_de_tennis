<div id="admin_pannel">
    <script src="js/admin_nav.js"></script>
    <nav id="admin_nav">
        <button type="button" onclick="admin_pannel(true)" id="admin_nav1">Gestion entraînements</button>
        <button type="button" onclick="admin_pannel(false)" id="admin_nav2">Gestion utilisateurs</button>
    </nav>
    <div id="gestion" class="pannel">
        <div id="gestion_users" hidden>
            <?php

            require 'php/admin/editUser.php';

            require 'php/admin/passwordChangerAdmin.php';

            echo '</div><div id="gestion_entraînements">';

            require 'php/admin/editEntraînement.php';

            require 'php/admin/showPlannings.php';

            ?>
        </div>
    </div>
</div>
