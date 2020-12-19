<?php
// On récupère dans la base de données tous les emails, les motDePasse hashés et les ID.

$bdd = new PDO('mysql:host=localhost;dbname=planning_tennis', 'root', ''); // user et mdp à changer par la suite

// On vérifie si l'utilisateur a fourni des identifiants et si ils correspondent avec ceux de la base de données

if(isset($_POST['nom']) AND isset($_POST['prénom']) AND isset($_POST['motDePasse']))
{
	if (!empty($_POST['nom']) AND !empty($_POST['prénom']) AND !empty($_POST['motDePasse']))
	{
		$reponse = $bdd->prepare("SELECT motDePasse FROM users WHERE nom = ? AND prénom = ?"); // va chercher le hash de l'utilisateur
		if ($reponse->execute(array($_POST['nom'], $_POST['prénom']))) // vérifie que la requête a réussi
		{
			if ($mdp = $reponse->fetch()){
				if (password_verify($_POST['motDePasse'], $mdp[0]))
				{
					echo '<p>Présent</p>';
					// TODO :  afficher le planning
				}else{
					echo '<p>MDP incorrect</p>';
				}
			}else{
				echo '<p>Nom ou Prénom incorrect</p>';
			}
		}else{
			echo '<p>Erreur dans la requête à la BDD</p>';
		}
		
	}
}
?>
