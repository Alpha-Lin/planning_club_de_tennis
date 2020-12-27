<?php
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
            <td><button type="button" onclick="décaler(' . $planning['id'] . ', \'' . $planning['jour'] . '\', \'' . $planning['heure_début'] . '\', \'' . $planning['heure_fin'] . '\', ' . $planning['entraîneur_id'] . ', \'user_changing\', \'' . $listeEntraîneurs . '\')">Décaler le cours</button></td>
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
                <th colspan="' . (7 + $maxUsers) . '">Plannings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Supprimer</td>
                <td>Décaler</td>
                <td>Jour</td>
                <td>Heure de début</td>
                <td>Heure de fin</td>
                <td>Entraîneur</td>
                <td colspan="' . (1 + $maxUsers) . '">Utilisateurs</td>
            </tr>'
        . $table .
        '</tbody></table>';

?>
