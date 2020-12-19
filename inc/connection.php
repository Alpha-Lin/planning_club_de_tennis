<?php
// On récupère dans la base de données tous les emails, les motDePasse hashés et les ID.

$bdd = new PDO('mysql:host=localhost;dbname=planning_tennis', 'root', ''); // user et mdp à changer par la suite
$reponse = $bdd->query("SELECT * FROM users");

// On vérifie si l'utilisateur a fourni des identifiants et si ils correspondent avec ceux de la base de données

if(isset($_POST['nom']) AND isset($_POST['prénom']) AND isset($_POST['motDePasse']))
{
	if (!empty($_POST['nom']) AND !empty($_POST['prénom']) AND !empty($_POST['motDePasse']))
	{
		while ($donnees = $reponse->fetch())
		{
			if (($_POST['nom'] == $donnees['nom'] AND $_POST['nom'] == $donnees['email']))
			{
				if (password_verify($_POST['motDePasse'], $donnees['motDePasse']))
				{
					// TODO :  afficher le planning
				}
			}
		}
	}
}
?>
