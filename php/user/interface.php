<?php
require 'php/user/nav.php';

if(isset($_GET['id_f']) AND !empty($_GET['id_f'])){
    switch($_GET['id_f']){
        case 2:
            require 'php/user/accountParameters.php';
            break;
        case 3:
            if($infoUser['typeCompte']){
                require 'php/admin/admin.php';
            }
            break;
        default:
            require 'php/user/planning_user.php';
            
    }
}else {
    require 'php/user/planning_user.php';
}
?>
