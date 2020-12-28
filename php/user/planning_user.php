<?php
// TODO :  afficher le planning en tableau
$reponse = $bdd->prepare('SELECT * FROM planning JOIN users_in_planning ON id_planning = id WHERE id_user = ?');
$reponse->execute(array($_SESSION['id']));
echo '<form><table>
        <thead>
            <tr>
                <th colspan="5">Planning pour ' . htmlspecialchars($infoUser[2]) . ' ' . htmlspecialchars($_SESSION['prénom']) . '</th>
            </tr>
            <tr>
                <th>Jour</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Entraîneur</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            ';

$prénom_entraîneur = $bdd->prepare('SELECT prénom FROM users WHERE id = ?');
$notifié = $bdd->prepare('SELECT notifié FROM users_in_planning JOIN users ON id_user = ? AND id_planning = ?');
$notifiéEdit = $bdd->prepare('UPDATE users_in_planning SET notifié = 0 WHERE id_user = ? AND id_planning = ?');

$isNeverSeen = false;

while($planning = $reponse->fetch()){
    $prénom_entraîneur->execute(array($planning['entraîneur_id']));
    $notifié->execute(array($_SESSION['id'], $planning['id']));

    $planningName = "planning_" . $planning['id'];
    $classPlanning = 'alreadySeen';
    if(isset($_GET[$planningName]) AND !empty($_GET[$planningName])){
        $notifiéEdit->execute(array($_SESSION['id'], $planning['id']));
    }
    else if($notifié->fetch()['notifié']){
        $isNeverSeen = true;
        $classPlanning = 'neverSeen';
    }
    echo '<tr class="' . $classPlanning . '"><td>' . $planning['jour'] . '</td>
        <td>' . $planning['heure_début'] . '</td>
        <td>' . $planning['heure_fin'] . '</td>
        <td>' . $prénom_entraîneur->fetch()[0] . '</td>
        <td>';
    
    if($classPlanning == 'neverSeen'){
        echo '<label for="'. $planningName . '">Vu : </label>
            <input type="checkbox" name="'. $planningName . '" id="'. $planningName . '">';
    }else{
        echo 'Vu';
    }

    echo '</td></tr>';
}
        
echo '</tbody></table>';

if($isNeverSeen){
    echo '<input type="submit" value="Valider"/>';
} 

echo '</form>';

?>
