<h2>Changer le mot de passe d'un utilisateur</h2>
    
<form action="?id_f=3" method="POST">

    <label for="user">Utilisateur : </label>
    <select name="user" id="user">
        <?php
        foreach($users as $user){
            echo '<option value=' . $user['id'] . '>' . $user['prénom'] . ' ' . $user['nom'] . '</option>';
        }
        ?>
    </select>

    <label for="newPassword">Nouveau mot de passe : </label>
    <input name="newPassword" id="newPassword" type="password" required>

    <input type="submit" value="Envoyer">
</form>

<?php
if (isset($_POST['newPassword'], $_POST['user'])){
    if (!empty($_POST['newPassword']) AND !empty($_POST['user'])){
        $req = $bdd->prepare('UPDATE users SET motDePasse = ? WHERE id = ?');
        if ($req->execute(array(password_hash($_POST['newPassword'], PASSWORD_ARGON2ID),
                        $_POST['user']))
            ){
            echo '<p>Mot de passe changé</p>';
        }
    } 
}
?>
