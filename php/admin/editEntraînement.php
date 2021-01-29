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
        $listeEntraîneurs = "";
        while($entraîneur = $reponse->fetch()){
            $listeEntraîneurs .= '<option value=' . $entraîneur[1] . '>' . $entraîneur[0] . '</option>';
        }
        echo $listeEntraîneurs;
        ?>
    </select>

    <script>
        const utilisateurs = <?=json_encode($users)?>;
        const entraîneurs = <?=json_encode($listeEntraîneurs)?>
    </script>
    <script src="js/edit_user_planning.js"></script>
    
    <div id="user_adding"></div>

    <button type="button" onclick="nb_user_adding++;add_user('user_adding', nb_user_adding)">Ajouter un utilisateur</button>
    <button type="button" onclick="del_user('div_user_adding_' + nb_user_adding);nb_user_adding--">Supprimer un utilisateur</button>

    <input type="submit" value="Créer">
</form>

<?php 

function check_entraîneur_bdd($date, $h_début, $h_fin, $entraîneur_id_check, $conn_bdd){
    $req = $conn_bdd->prepare('SELECT id FROM planning WHERE jour = ? AND heure_début = ? AND heure_fin = ? AND entraîneur_id = ?');
    $req->execute(array($date, $h_début, $h_fin, $entraîneur_id_check));
    if($req->fetch()){ // Vérifie si un cours est déjà programmé pour le jour, l'heure, et le prof sélectionnés
        echo '<p>Un cours est déjà programmé à cette date pour cet entraîneur.</p>';
        return false;
    }
    return true;
}

function warn_user($id_user, $msg, $conn_bdd){
    $nomUser = $conn_bdd->prepare('SELECT nom, prénom FROM users WHERE id = ?');
    $nomUser->execute(array($id_user));
    $nomUser = $nomUser->fetch();
    echo '<p>Attention : L\'utilisateur ' . $nomUser['nom'] . ' ' . $nomUser['prénom'] . $msg . '</p>';
}

if(isset($_POST['date'], $_POST['h_début'], $_POST['h_fin'], $_POST['entraîneur'])){ // Vérifie l'intégrité des données et insère le cours dans la BDD
    if(!empty($_POST['date']) AND !empty($_POST['h_début']) AND !empty($_POST['h_fin']) AND !empty($_POST['entraîneur'])){
        if(isset( $_POST['user_adding_1']) AND !empty( $_POST['user_adding_1'])){
            if(check_entraîneur_bdd($_POST['date'], $_POST['h_début'], $_POST['h_fin'], $_POST['entraîneur'], $bdd)){
                $req = $bdd->prepare('INSERT INTO planning (jour, heure_début, heure_fin, entraîneur_id) VALUES (?, ?, ?, ?)');
                $req->execute(array($_POST['date'], $_POST['h_début'], $_POST['h_fin'], $_POST['entraîneur']));
                $idUser = 1;
                $usersInserted = [];
                $req = $bdd->prepare('INSERT INTO users_in_planning (id_user, id_planning, notifié) VALUES (?, ' . $bdd->lastInsertId() . ', 1)');
                $nameVariableUser = 'user_adding_' . $idUser;
                do{
                    if(!in_array($_POST[$nameVariableUser], $usersInserted)){ // On vérifie que l'élève n'a pas été sélectionné 2 fois
                        $req->execute(array($_POST[$nameVariableUser]));
                        $usersInserted[] = $_POST[$nameVariableUser];
                    }else{
                        warn_user($_POST[$nameVariableUser], ' a été sélectionné plusieurs fois.', $bdd);
                    }
                    $idUser++;
                    $nameVariableUser = 'user_adding_' . $idUser;
                }while(isset($_POST[$nameVariableUser]) AND !empty($_POST[$nameVariableUser]));
                echo '<p>Cours inséré.</p>';
            }
        }else{
            echo '<p>Erreur : Utilisateurs présents en cours non spécifiés.</p>';
        }
        
    }
}

###### Modifie le planning avec les données fournies ######
if(isset($_POST['date_Change'], $_POST['h_début_Change'], $_POST['h_fin_Change'], $_POST['id_planning_edit'])){ // Vérifie l'intégrité des données et modifie le cours dans la BDD
    if(!empty($_POST['date_Change']) AND !empty($_POST['h_début_Change']) AND !empty($_POST['h_fin_Change']) AND !empty($_POST['id_planning_edit'])){
        if(isset($_POST['entraîneur_Change']) AND !empty($_POST['entraîneur_Change'])){ // Change l'entraîneur si demandé
            if(check_entraîneur_bdd($_POST['date_Change'], $_POST['h_début_Change'], $_POST['h_fin_Change'], $_POST['entraîneur_Change'], $bdd)){
                $req = $bdd->prepare('UPDATE planning SET entraîneur_id = ? WHERE id = ?');
                $req->execute(array($_POST['entraîneur_Change'], $_POST['id_planning_edit']));
            }
        }

        // Ajoute des élèves si demandé
        $idUser = 1;
        $usersInserted = [];
        $req = $bdd->prepare('INSERT INTO users_in_planning (id_user, id_planning, notifié) VALUES (?, ?, 1)');
        $nameVariableUser = 'planning_' . $_POST['id_planning_edit'] .  '&_' . $idUser;
        while(isset($_POST[$nameVariableUser]) AND !empty($_POST[$nameVariableUser])){
            $vérif_planning = $bdd->query('SELECT id FROM users JOIN users_in_planning ON id = id_user');
            $vérif_planning = $vérif_planning->fetchAll(PDO::FETCH_COLUMN, 0);
            if(!in_array($_POST[$nameVariableUser], $usersInserted)){ // On vérifie que l'élève n'a pas été sélectionné plusieurs fois
                if(!in_array($_POST[$nameVariableUser], $vérif_planning)){ // On vérifie que l'élève n'y est pas plusieurs fois
                    $req->execute(array($_POST[$nameVariableUser], $_POST['id_planning_edit']));
                }else{
                    warn_user($_POST[$nameVariableUser], ' est déjà inscrit pour ce cours.', $bdd);
                }
                $usersInserted[] = $_POST[$nameVariableUser];
            }else{
                warn_user($_POST[$nameVariableUser], ' a été sélectionné plusieurs fois', $bdd);
            }
            $idUser++;
            $nameVariableUser = 'planning_' . $_POST['id_planning_edit'] .  '&_' . $idUser;
        }

        // Supprime des élèves si demandé
        $idUser = 1;
        $nameVariableUser = 'removed_user_' . $_POST['id_planning_edit'] .  '_' . $idUser;
        if(isset($_POST[$nameVariableUser]) AND !empty($_POST[$nameVariableUser])){
            $nbUsersInPlanning = $bdd->prepare('SELECT COUNT(*) FROM users_in_planning WHERE id_planning = ?');
            $nbUsersInPlanning->execute(array($_POST['id_planning_edit']));

            if($nbUsersInPlanning->fetch()[0] > 1){
                $req = $bdd->prepare('DELETE FROM users_in_planning WHERE id_planning = ? AND id_user = ?');
                do{
                    $req->execute(array($_POST['id_planning_edit'], $_POST[$nameVariableUser]));
                    $idUser++;
                    $nameVariableUser = 'removed_user_' . $_POST['id_planning_edit'] .  '_' . $idUser;
                }while(isset($_POST[$nameVariableUser]) AND !empty($_POST[$nameVariableUser]));
            }else{
                echo '<p>Impossible de supprimer tous les élèves présents en cours.</p>';
            }
        }
        
        // Met à jour le planning dans la BDD
        $req = $bdd->prepare('UPDATE planning SET jour = ?, heure_début = ?, heure_fin = ? WHERE id = ?');
        $req->execute(array($_POST['date_Change'], $_POST['h_début_Change'], $_POST['h_fin_Change'], $_POST['id_planning_edit']));
        $req = $bdd->prepare('UPDATE users_in_planning SET notifié = 1 WHERE id_planning = ?');
        $req->execute(array($_POST['id_planning_edit']));
        echo '<p>Cours modifié.</p>';
    }
}

if(isset($_GET['id_del']) AND !empty($_GET['id_del'])){ // Vérifie si l'on veut supprimer un cours et si oui l'exécute alors
    $req = $bdd->prepare('DELETE FROM planning WHERE id = ?');
    $req->execute(array($_GET['id_del']));
    echo '<p>Cours supprimé.</p>';

}?>
