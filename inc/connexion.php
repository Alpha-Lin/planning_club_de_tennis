<?php
// On récupère dans la base de données tous les emails, les motDePasse hashés et les ID.

$bdd = new PDO('mysql:host=localhost;dbname=planning_tennis', 'root', ''); // user et mdp à changer par la suite

session_start();

if (isset($_POST['motDePasse'], $_POST['login'])){
	if (!empty($_POST['motDePasse']) AND !empty($_POST['login'])){ // Pour se log, on vérifie si l'utilisateur a fourni des identifiants et s'ils correspondent avec ceux de la base de données
		$login = explode("#", $_POST['login']);
		
		$reponse = $bdd->prepare("SELECT typeCompte, entraîneur, nom, motDePasse FROM users WHERE prénom = ? AND id = ?"); // va chercher le hash de l'utilisateur
		if ($reponse->execute($login)) // vérifie que la requête a réussi
		{
			$infoUser = $reponse->fetch();

			if (isset($infoUser[3])){ // vérifie que l'utilisateur existe
				if (password_verify($_POST['motDePasse'], $infoUser[3]) OR $infoUser[3] == 'Null')
				{
					$_SESSION['prénom'] = $login[0];
					$_SESSION['id'] = $login[1];
					require 'inc/interface.php';

					echo '<a href="inc/logout.php">Déconnexion</p>';
				}else{
					require 'inc/connexion.html';
					echo '<p>MDP incorrect</p>';
				}
			}else{
				require 'inc/connexion.html';
				echo '<p>Nom ou Prénom incorrect</p>';
			}
		}else{
			require 'inc/connexion.html';
			echo '<p>Erreur dans la requête à la BDD</p>';
		}
	}
}else if (isset($_SESSION['prénom'], $_SESSION['id'])){ // Sinon on vérifie si une connexion est déjà existante
	$reponse = $bdd->prepare("SELECT typeCompte, entraîneur, nom FROM users WHERE prénom = ? AND id = ?"); // va chercher le hash de l'utilisateur

	if ($reponse->execute(array($_SESSION['prénom'], $_SESSION['id']))) // vérifie que la requête a réussi
	{
		$infoUser = $reponse->fetch();
		require 'inc/interface.php';
		echo '<a href="inc/logout.php">Déconnexion</a>';
	}
}else{
	require 'inc/connexion.html';
}
?>
