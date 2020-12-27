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
            $listeEntraîneurs .= '<option value=' . $entraîneur[1] . '>' . $entraîneur[0] . '</option>';
        }
        echo $listeEntraîneurs;
        ?>
    </select>

    <script>const utilisateurs = <?=json_encode($users)?></script>
    <script src="js/edit_user_planning.js"></script>
    
    <div id="user_adding"></div>

    <button type="button" onclick="add_user('user_adding')">Ajouter un utilisateur</button>
    <button type="button" onclick="del_user('user_adding')">Supprimer un utilisateur</button>

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

}?>
