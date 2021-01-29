<?php
######## Affiche toutes les heures de cours programmées ########

$reponse = $bdd->query('SELECT * FROM planning'); // Récupère tous les cours enregistrés
$maxUsers = 0;
$table = "";

$nbUsersPrepare = $bdd->prepare('SELECT COUNT(id) FROM users JOIN users_in_planning ON id = id_user WHERE id_planning = ?');
$prénom_entraîneur = $bdd->prepare('SELECT prénom FROM users WHERE id = ?');
$utilisateurPlanning = $bdd->prepare('SELECT id, nom, prénom FROM users JOIN users_in_planning ON id = id_user WHERE id_planning = ?');

while($planning = $reponse->fetch()){
    $nbUsersPrepare->execute(array($planning['id']));
    $nbUsers = $nbUsersPrepare->fetch()[0];
    if ($nbUsers > $maxUsers){
        $maxUsers = $nbUsers;
    }
    
    $prénom_entraîneur->execute(array($planning['entraîneur_id']));
    $prénom_entraîneur_clair = $prénom_entraîneur->fetch()[0];

    $planningID = 'planning_' . $planning['id'];

    $table .= '<tr class="alreadySeen" id="tr_' . $planningID . '"><td><a href="?id_f=3&id_del=' . $planning['id'] . '">X</a></td>
            <td id="décalage_' . $planning['id'] . '"><button type="button" onclick="décaler(' . $planning['id'] . ')">Éditer le cours</button></td>
            <td class="' . $planningID . '">' . $planning['jour'] . '</td>
            <td class="' . $planningID . '">' . $planning['heure_début'] . '</td>
            <td class="' . $planningID . '">' . $planning['heure_fin'] . '</td>
            <td class="' . $planningID . '">' . $prénom_entraîneur_clair . '</td>';

    $utilisateurPlanning->execute(array($planning['id']));

    while($nomUser = $utilisateurPlanning->fetch()){
        $table .= '<td id="' . $planningID . '_' . $nomUser['id']  . '" class="' . $planningID . '">' . $nomUser['nom'] . ' ' . $nomUser['prénom'] . '</td>';
    }

    $table .= '</tr>';  
}

if($maxUsers){
    $maxUsers -= 1;
}

echo '<form action="?id_f=3" method="POST"><table>
        <thead>
            <tr>
                <th colspan="' . (7 + $maxUsers) . '" class="editable_length">Plannings</th>
            </tr>
            <tr>
                <th>Supprimer</th>
                <th>Éditer</th>
                <th>Jour</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Entraîneur</th>
                <th colspan="' . (1 + $maxUsers) . '" class="editable_length">Utilisateurs</th>
            </tr>
        </thead>
        <tbody>'
        . $table .
        '</tbody></table></form>';

?>
