<?php echo '<p>Présent</p>';
// TODO :  afficher le planning en tableau
$reponse = $bdd->prepare("SELECT * FROM planning JOIN users_in_planning ON id_planning = id WHERE id_user = ?");
if ($reponse->execute(array($login[1]))){
    echo '<table>
            <thead>
                <tr>
                    <th>Planning pour ' . htmlspecialchars($infoUser[3]) . ' ' . htmlspecialchars($login[0]) . '</th>
                </tr>
            </thead>
            <tbody>';
    
    while($planning = $reponse->fetch()){
        echo '<tr><td>'.$planning.'</td></tr>';
    }
    echo '</tbody></table>';
}
if ($infoUser[1]){ // Admin : peut créer et supprimer des horaires
    require 'inc/admin.php';
}
?>