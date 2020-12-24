<?php // prépare à l'avance toutes les infos des utilisateurs

$reponse = $bdd->query('SELECT * FROM users');

$users = $reponse->fetchAll();

?>

<p>Admin</p>

<h2>Ajouter un utilisateur : </h2>

<form action="index.php" method="POST">
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

<?php require 'inc/passwordChangerAdmin.php';
if (isset($_POST['prénom']) AND isset($_POST['nom']) AND isset($_POST['mdpInscription']) AND isset($_POST['birthday'])){ // Pour insérer un nouvel utilisateur
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

<h2>Créer un entraînement : </h2>
<form action="index.php" method="GET">
    <label for="date">Jour : </label>
    <input name="date" id="date" type="date" required>

    <label for="h_début">Horaire début : </label>
    <input name="h_début" id="h_début" type="time" required>

    <label for="h_fin">Horaire fin : </label>
    <input name="h_fin" id="h_fin" type="time" required>

    <label for="entraîneur">Entraîneur : </label>
    <select name="entraîneur" id="entraîneur">
        <?php $reponse = $bdd->query('SELECT prénom, id FROM users WHERE entraîneur = 1');
        while($entraîneur = $reponse->fetch()){
            echo '<option value=' . $entraîneur[1] . '>' . $entraîneur[0] . '</option>';
        }
        ?>
    </select>

    <input type="submit" value="Créer">
</form>
