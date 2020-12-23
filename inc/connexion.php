<?php
// On récupère dans la base de données tous les emails, les motDePasse hashés et les ID.

$bdd = new PDO('mysql:host=localhost;dbname=planning_tennis', 'root', ''); // user et mdp à changer par la suite

if (isset($_POST['motDePasse']) AND !empty($_POST['motDePasse'])){
	if (isset($_POST['login']) AND !empty($_POST['login'])){ // Pour se log, on vérifie si l'utilisateur a fourni des identifiants et s'ils correspondent avec ceux de la base de données
		$login = explode("#", $_POST['login']);
		
		$reponse = $bdd->prepare("SELECT motDePasse, typeCompte, entraîneur, nom FROM users WHERE prénom = ? AND id = ?"); // va chercher le hash de l'utilisateur
		if ($reponse->execute($login)) // vérifie que la requête a réussi
		{
			$infoUser = $reponse->fetch();

			if (isset($infoUser[0])){ // vérifie que l'utilisateur existe
				if (password_verify($_POST['motDePasse'], $infoUser[0]) OR $infoUser[0] == 'Null')
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
	}else if (isset($_POST['prénom']) AND isset($_POST['nom'])){ // Pour insérer un nouvel utilisateur
		if (!empty($_POST['prénom']) AND !empty($_POST['nom'])){
			$req = $bdd->prepare('INSERT INTO users(nom, prénom, motDePasse, entraîneur, typeCompte) VALUE (?, ?, ?, ?, ?)');
			$req->execute(array($_POST['nom'],
								$_POST['prénom'],
								password_hash($_POST['motDePasse'], PASSWORD_ARGON2ID),
								isset($_POST['entraîneur']) ? 1 : 0,
								isset($_POST['admin']) ? 1 : 0)
			);
			echo '<p>User inséré</p>';
		}
	}
}
?>
