<?php

require 'php/admin/ajoutUser.php';

// prépare à l'avance toutes les infos des utilisateurs
$reponse = $bdd->query('SELECT id, prénom, nom FROM users');
$users = $reponse->fetchAll();

require 'php/admin/passwordChangerAdmin.php';

require 'php/admin/editEntraînement.php';

require 'php/admin/showPlannings.php';

?>
<div id="cours_décalage"></div>
