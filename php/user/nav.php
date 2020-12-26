<nav>
    <a href="?id_f=1">Mon Planning</a>
    <a href="?id_f=2">Paramètre du compte</a>
    <?php if($infoUser['typeCompte']){
        echo '<a href="?id_f=3">Admin</a>';
    }?>
</nav>
