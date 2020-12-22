<p>Admin</p>
<p>Créer un entraînement : </p>
<form>
    <label for="h_début">Horaire début : </label>

    <label for="h_fin">Horaire fin : </label>

    <label for="entraîneur">Entraîneur : </label>
    <select name="entraîneur" id="entraîneur">
        <?php $reponse = $bdd->query('SELECT prénom, id FROM users WHERE entraîneur = 1');
        while($entraîneur = $reponse->fetch()){
            echo '<option value=' . $entraîneur[1] . '>' . $entraîneur[0] . '</option>';
        }
        ?>
    </select>
</form>