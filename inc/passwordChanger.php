<h2>Changer de mot de passe</h2>
    
<form action="index.php" method="POST">

    <label for="newPassword">Nouveau mot de passe : </label>
    <input name="newPassword" id="newPassword" type="password" required>

    <input type="submit" value="Envoyer">
</form>

<?php
if (isset($_POST['newPassword']) AND !empty($_POST['newPassword'])){
    $req = $bdd->prepare('UPDATE users SET motDePasse = ? WHERE id = ' . $_SESSION['id']);
    if ($req->execute(array(password_hash($_POST['newPassword'], PASSWORD_ARGON2ID)))){
        echo '<p>Mot de passe changé</p>';
    }
}
?>