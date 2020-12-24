<?php
// TODO :  afficher le planning en tableau
$reponse = $bdd->prepare('SELECT * FROM planning JOIN users_in_planning ON id_planning = id WHERE id_user = ?');
if ($reponse->execute(array($_SESSION['id']))){
    echo '<table>
            <thead>
                <tr>
                    <th colspan="4">Planning pour ' . htmlspecialchars($infoUser[2]) . ' ' . htmlspecialchars($_SESSION['prénom']) . '</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jour</td>
                    <td>Heure de début</td>
                    <td>Heure de fin</td>
                    <td>Entraîneur</td>
                </tr>';
    
    while($planning = $reponse->fetch()){
        $prénom_entraîneur = $bdd->query('SELECT prénom FROM users WHERE id = ' . $planning['entraîneur_id']);
        echo '<tr><td>' . $planning['jour'] . '</td>'.
            '<td>' . $planning['heure_début'] . '</td>'.
            '<td>' . $planning['heure_fin'] . '</td>'.
            '<td>' . $prénom_entraîneur->fetch()[0] . '</td></tr>';  
    }
           
    echo '</tbody></table>';

    require 'inc/passwordChanger.php';
}
if ($infoUser[0]){ // Admin : peut créer et supprimer des horaires
    require 'inc/admin.php';
}
?>
