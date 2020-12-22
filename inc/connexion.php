<?php
// On récupère dans la base de données tous les emails, les motDePasse hashés et les ID.

$bdd = new PDO('mysql:host=localhost;dbname=planning_tennis', 'root', ''); // user et mdp à changer par la suite

// On vérifie si l'utilisateur a fourni des identifiants et s'ils correspondent avec ceux de la base de données

if(isset($_POST['nom']) AND isset($_POST['prénom']) AND isset($_POST['motDePasse']))
{
	if (!empty($_POST['nom']) AND !empty($_POST['prénom']) AND !empty($_POST['motDePasse']))
	{
		if(isset($_POST['typeRequête'])){
			$req = $bdd->prepare('INSERT INTO users(nom, prénom, motDePasse, typeCompte) VALUE (?, ?, ?, ?)');
			$req->execute(array($_POST['nom'],
								$_POST['prénom'],
								password_hash($_POST['motDePasse'], PASSWORD_ARGON2ID),
								isset($_POST['admin']) ? 1 : 0)
			); //Pour insérer un nouvel utilisateur
			echo '<p>User inséré</p>';
		}else{
			$reponse = $bdd->prepare("SELECT motDePasse, typeCompte, id FROM users WHERE nom = ? AND prénom = ?"); // va chercher le hash de l'utilisateur
			if ($reponse->execute(array($_POST['nom'], $_POST['prénom']))) // vérifie que la requête a réussi
			{
				$infoUser = $reponse->fetch();

				if ($infoUser[0]){ // vérifie que l'utilisateur existe
					if (password_verify($_POST['motDePasse'], $infoUser[0]))
					{
						require 'inc/interface.php';
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
}
?>
