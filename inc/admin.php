<p>Admin</p>

<p>Ajouter un utilisateur : </p>

<form action="index.php" method="POST">
    <label for="nom">Nom : </label>
    <input name="nom" id="nom" type="text">

    <label for="prénom">Prénom : </label>
    <input name="prénom" id="prénom" type="text" required>

    <label for="birthday">Année de naissance : </label>
    <input name="birthday" id="birthday" type="number" min="1903" max="2100">

    <label for="mdpInscription">Mot de passe : </label> <!-- faire une slice -->
    <input name="mdpInscription" id="mdpInscription" type="password" required>

    <label for="entraîneur">Entraîneur : </label>
    <input name="entraîneur" id="entraîneur" type="checkbox">

    <label for="admin">Admin : </label>
    <input name="admin" id="admin" type="checkbox">

    <input type="submit" value="Envoyer">
</form>

<?php if (isset($_POST['prénom']) AND isset($_POST['nom']) AND isset($_POST['mdpInscription']) AND isset($_POST['birthday'])){ // Pour insérer un nouvel utilisateur
		if (!empty($_POST['prénom']) AND !empty($_POST['mdpInscription'])){
			$req = $bdd->prepare('INSERT INTO users(nom, prénom, motDePasse, entraîneur, typeCompte, année_naissance) VALUE (?, ?, ?, ?, ?, ?)');
			$req->execute(array(!empty($_POST['nom']) ? $_POST['nom'] : Null,
								$_POST['prénom'],
								password_hash($_POST['mdpInscription'], PASSWORD_ARGON2ID),
								isset($_POST['entraîneur']) ? 1 : 0,
                                isset($_POST['admin']) ? 1 : 0,
                                !empty($_POST['birthday']) ? $_POST['birthday'] : Null)
			);
			echo '<p>User inséré</p>';
		}else{
            echo '<p>Erreur lors de l\'insertion des données de l\'utilisateur</p>';
        }
    }
?>

<p>Créer un entraînement : </p>
<form>
    <label for="h_début">Horaire début : </label>

    <label for="h_fin">Horaire fin : </label>

    <label for="entraîneur">Entraîneur : </label>
    <select name="entraîneur" id="entraîneur">
        <?php $reponse = $bdd->query('SELECT prénom, id FROM users WHERE entraîneur = 1');
        while($entraîneur = $reponse->fetch()){
            echo '<option value=' . $entraîneur[1] . '>' . $entraîneur[0] . '</option>';
        }
        ?>
    </select>
</form>