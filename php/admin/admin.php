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

// prépare à l'avance toutes les infos des utilisateurs
$reponse = $bdd->query('SELECT id, prénom, nom FROM users');
$users = $reponse->fetchAll();

require 'php/admin/passwordChangerAdmin.php';
?>

<h2>Créer un entraînement : </h2>
<form action="?id_f=3" method="POST">
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

    <script src="js/ajout_user.js"></script>

    <div id="user_adding"></div>

    <button type="button" onclick="add_user(<?=htmlspecialchars(json_encode($users))?>)">Ajouter un utilisateur</button>
    <button type="button" onclick="del_user()">Supprimer un utilisateur</button>

    <input type="submit" value="Créer">
</form>

<?php if(isset($_POST['date'], $_POST['h_début'], $_POST['h_fin'], $_POST['entraîneur'])){ // Vérifie l'intégrité des données et insère le cours dans la BDD
    if(!empty($_POST['date']) AND !empty($_POST['h_début']) AND !empty($_POST['h_fin']) AND !empty($_POST['entraîneur'])){
        if(isset( $_POST['user_1']) AND !empty( $_POST['user_1'])){
            $req = $bdd->prepare('SELECT id FROM planning WHERE jour = ? AND heure_début = ? AND heure_fin = ? AND entraîneur_id = ?');
            $req->execute(array($_POST['date'], $_POST['h_début'], $_POST['h_fin'], $_POST['entraîneur']));
            if($req->fetch()){ // Vérifie si un cours est déjà programmé pour le jour, l'heure, et le prof sélectionnés
                echo '<p>Un cours est déjà programmé à cette date pour cet entraîneur.</p>';
            }else{
                $req = $bdd->prepare('INSERT INTO planning (jour, heure_début, heure_fin, entraîneur_id) VALUES (?, ?, ?, ?)');
                $req->execute(array($_POST['date'], $_POST['h_début'], $_POST['h_fin'], $_POST['entraîneur']));
                $idUser = 1;
                $usersInserted = array();
                $req = $bdd->prepare('INSERT INTO users_in_planning (id_user, id_planning) VALUES (?, ' . $bdd->lastInsertId() . ')');
                do{
                    if(!in_array($_POST['user_' . $idUser], $usersInserted)){ // On vérifie que l'élève n'a pas été sélectionné 2 fois
                        
                        $req->execute(array($_POST['user_' . $idUser]));
                        $usersInserted[] = $_POST['user_' . $idUser];
                    }
                    $idUser++;
                }while(isset($_POST['user_' . $idUser]) AND !empty($_POST['user_' . $idUser]));
                echo '<p>Cours inséré.</p>';
            }
        }else{
            echo '<p>Erreur : Utilisateurs présents en cours non spécifiés.</p>';
        }
        
    }
}

if(isset($_GET['id_del']) AND !empty($_GET['id_del'])){ // Vérifie si l'on veut supprimer un cours et si oui l'exécute alors
    $req = $bdd->prepare('DELETE FROM planning WHERE id = ?');
    $req->execute(array($_GET['id_del']));
    echo '<p>Cours supprimé.</p>';

}

######## Affiche toutes les heures de cours programmées ########

$reponse = $bdd->query('SELECT * FROM planning'); // Récupère tous les cours enregistrés
$maxUsers = 0;
$table = "";

$nbUsersPrepare = $bdd->prepare('SELECT COUNT(id) FROM users JOIN users_in_planning ON id = id_user WHERE id_planning = ?');
$prénom_entraîneur = $bdd->prepare('SELECT prénom FROM users WHERE id = ?');
$utilisateurPlanning = $bdd->prepare('SELECT nom, prénom FROM users JOIN users_in_planning ON id = id_user WHERE id_planning = ?');

while($planning = $reponse->fetch()){
    $nbUsersPrepare->execute(array($planning['id']));
    $nbUsers = $nbUsersPrepare->fetch()[0];
    if ($nbUsers > $maxUsers){
        $maxUsers = $nbUsers;
    }
    
    $prénom_entraîneur->execute(array($planning['entraîneur_id']));

    $table .= '<tr><td><a href="?id_f=3&id_del=' . $planning['id'] . '">X</a></td>
            <td>' . $planning['jour'] . '</td>
            <td>' . $planning['heure_début'] . '</td>
            <td>' . $planning['heure_fin'] . '</td>
            <td>' . $prénom_entraîneur->fetch()[0] . '</td>';

    $utilisateurPlanning->execute(array($planning['id']));

    while($nomUser = $utilisateurPlanning->fetch()){
        $table .= '<td>' . $nomUser['nom'] . ' ' . $nomUser['prénom'] . '</td>';
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
