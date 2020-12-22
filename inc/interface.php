<?php echo '<p>Présent</p>';
// TODO :  afficher le planning en tableau
$reponse = $bdd->prepare("SELECT * FROM planning JOIN users_in_planning ON id_planning = id WHERE id_user = ?");
if ($reponse->execute(array($infoUser[2]))){
    echo '<table>
            <thead>
                <tr>
                    <th>Planning pour ' . htmlspecialchars($_POST['nom']) . ' ' . htmlspecialchars($_POST['prénom']) . '</th>
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