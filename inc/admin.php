<?php // prépare à l'avance toutes les infos des utilisateurs

$reponse = $bdd->query('SELECT id, prénom, nom FROM users');

$users = $reponse->fetchAll();

?>

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

    <script src="ajout_user.js"></script>

    <div id="user_adding"></div>

    <button type="button" onclick="add_user(<?=htmlspecialchars(json_encode($users))?>)">Ajouter un utilisateur</button>
    <button type="button" onclick="del_user()">Supprimer un utilisateur</button>

    <input type="submit" value="Créer">
</form>

<?php if(isset($_GET['date'], $_GET['h_début'], $_GET['h_fin'], $_GET['entraîneur'])){ // Vérifie l'intégrité des données et insère le cours dans la BDD
    if(!empty($_GET['date']) AND !empty($_GET['h_début']) AND !empty($_GET['h_fin']) AND !empty($_GET['entraîneur'])){
        if(isset( $_GET['user_1']) AND !empty( $_GET['user_1'])){
            $req = $bdd->prepare('SELECT id FROM planning WHERE jour = ? AND heure_début = ? AND heure_fin = ? AND entraîneur_id = ?');
            $req->execute(array($_GET['date'], $_GET['h_début'], $_GET['h_fin'], $_GET['entraîneur']));
            if($req->fetch()){ // Vérifie si un cours est déjà programmé pour le jour, l'heure, et le prof sélectionnés
                echo '<p>Un cours est déjà programmé à cette date pour cet entraîneur.</p>';
            }else{
                $req = $bdd->prepare('INSERT INTO planning (jour, heure_début, heure_fin, entraîneur_id) VALUES (?, ?, ?, ?)');
                if($req->execute(array($_GET['date'], $_GET['h_début'], $_GET['h_fin'], $_GET['entraîneur']))){
                    $idUser = 1;
                    $idPlanning = $bdd->lastInsertId();
                    while(isset($_GET['user_' . $idUser]) AND !empty($_GET['user_' . $idUser])){
                        $req = $bdd->prepare('INSERT INTO users_in_planning (id_user, id_planning) VALUES (?, ' . $idPlanning . ')');
                        $req->execute(array($_GET['user_' . $idUser]));
                        $idUser++;
                    }
                    echo '<p>Cours inséré.</p>';
                }else{
                    echo '<p>Erreur lors de l\'insertion du cours.</p>';
                }
            }
        }else{
            echo '<p>Erreur : Utilisateurs présents en cours non spécifiés.</p>';
        }
        
    }
}

if(isset($_GET['id_del']) AND !empty($_GET['id_del'])){ // Vérifie si l'on veut supprimer un cours et si oui l'exécute alors
    $req = $bdd->prepare('DELETE FROM planning WHERE id = ?');
    if($req->execute(array($_GET['id_del']))){
        echo '<p>Cours supprimé.</p>';
    }else{
        echo '<p> Erreur : le cours n\'a pas pu être supprimé.</p>';
    }
}

$reponse = $bdd->query('SELECT * FROM planning'); // Récupère tous les cours enregistrés


$maxUsers = 0;
$table = "";

while($planning = $reponse->fetch()){
    $nb = $bdd->query('SELECT COUNT(id) FROM users JOIN users_in_planning ON id = id_user WHERE id_planning = ' . $planning['id']);
    $nbUsers = $nb->fetch()[0];
    if ($nbUsers > $maxUsers){
        $maxUsers = $nbUsers;
    }

    $prénom_entraîneur = $bdd->query('SELECT prénom FROM users WHERE id = ' . $planning['entraîneur_id']);

    $table .= '<tr><td><a href="?id_del=' . $planning['id'] . '">X</a></td>
            <td>' . $planning['jour'] . '</td>
            <td>' . $planning['heure_début'] . '</td>
            <td>' . $planning['heure_fin'] . '</td>
            <td>' . $prénom_entraîneur->fetch()[0] . '</td>';
    $utilisateurPlanning = $bdd->query('SELECT nom, prénom FROM users JOIN users_in_planning ON id = id_user WHERE id_planning = ' . $planning['id']);

    while($nomUser = $utilisateurPlanning->fetch()){
        $table .= '<td>' . $nomUser['prénom'] . '</td>';
    }

    $table .= '</tr>';  
}

if($maxUsers){
    $maxUsers -= 1;
}

echo '<table>
        <thead>
            <tr>
                <th colspan="' . (6 + $maxUsers) . '">Plannings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Supprimer</td>
                <td>Jour</td>
                <td>Heure de début</td>
                <td>Heure de fin</td>
                <td>Entraîneur</td>
                <td colspan="' . (1 + $maxUsers) . '">Utilisateurs</td>
            </tr>'
        . $table .
        '</tbody></table>';

?>
