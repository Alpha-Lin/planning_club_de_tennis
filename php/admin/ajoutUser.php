<h2>Ajouter un utilisateur : </h2>

<form action="?id_f=3" method="POST">
    <label for="nom">Nom : </label>
    <input name="nom" id="nom" type="text">

    <label for="prénom">Prénom : </label>
    <input name="prénom" id="prénom" type="text" required>

    <label for="birthday">Année de naissance : </label>
    <input name="birthday" id="birthday" type="number" min="1903" max="2100">

    <label for="mdpInscription">Mot de passe : </label> <!-- faire une slice -->
    <input name="mdpInscription" id="mdpInscription" type="password" required>

    <label for="is_entraîneur">Entraîneur : </label>
    <input name="is_entraîneur" id="is_entraîneur" type="checkbox">

    <label for="admin">Admin : </label>
    <input name="admin" id="admin" type="checkbox">

    <input type="submit" value="Envoyer">
</form>

<?php
if (isset($_POST['prénom'], $_POST['nom'], $_POST['mdpInscription'], $_POST['birthday'])){ // Pour insérer un nouvel utilisateur
    if (!empty($_POST['prénom']) AND !empty($_POST['mdpInscription'])){
        $req = $bdd->prepare('INSERT INTO users(nom, prénom, motDePasse, entraîneur, typeCompte, année_naissance) VALUE (?, ?, ?, ?, ?, ?)');
        $req->execute(array(!empty($_POST['nom']) ? $_POST['nom'] : Null,
                            $_POST['prénom'],
                            password_hash($_POST['mdpInscription'], PASSWORD_ARGON2ID),
                            isset($_POST['is_entraîneur']) ? 1 : 0,
                            isset($_POST['admin']) ? 1 : 0,
                            !empty($_POST['birthday']) ? $_POST['birthday'] : Null)
        );
        echo '<p>User inséré</p>';
    }else{
        echo '<p>Erreur lors de l\'insertion des données de l\'utilisateur</p>';
    }
}
?>
