<nav id="main_nav">
    <a href="?id_f=1"><button type="button">Mon Planning</button></a>
    <a href="?id_f=2"><button type="button">Paramètre du compte</button></a>
    <?php if($infoUser['typeCompte']){
        echo '<a href="?id_f=3"><button type="button">Admin</button></a>';
    }?>
    <a href="php/user/logout.php" id="logout"><button type="button">Déconnexion</button></a>
</nav>
