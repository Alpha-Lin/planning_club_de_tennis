<div class="pannel">
    <h2>Changer de mot de passe</h2>
        
    <form action="?id_f=2" method="POST">

        <label for="newPasswordUser">Nouveau mot de passe : </label>
        <input name="newPasswordUser" id="newPasswordUser" type="password" required>

        <input type="submit" value="Envoyer">
    </form>

    <?php
    if (isset($_POST['newPasswordUser']) AND !empty($_POST['newPasswordUser'])){
        $req = $bdd->prepare('UPDATE users SET motDePasse = ? WHERE id = ' . $_SESSION['id']);
        if ($req->execute(array(password_hash($_POST['newPasswordUser'], PASSWORD_ARGON2ID)))){
            echo '<p>Mot de passe changé</p>';
        }
    }
    ?>
</div>
