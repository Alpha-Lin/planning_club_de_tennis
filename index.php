<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning club de tennis</title>
</head>
<body>
    <form action="index.php" method="POST">
        <label for="nom">Nom : </label>
        <input name="nom" id="nom" type="text" required>
        <label for="prénom">Prénom : </label>
        <input name="prénom" id="prénom" type="text" required>
        <label for="motDePasse">Mot de passe : </label> <!-- faire une slice -->
        <input name="motDePasse" id="motDePasse" type="password" required>

        <input type="submit" value="Envoyer">
    </form>

    <?php require('inc/connection.php');?>
</body>
</html>
