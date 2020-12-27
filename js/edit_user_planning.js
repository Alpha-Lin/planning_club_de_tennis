let nb_user = 0

function add_user(div_id){
    nb_user++
    let users_show = "<div id=\"div_" + div_id + "_" + nb_user + "\"><label for=" + div_id + "\"_" + nb_user + "\">Élève " + nb_user + " : </label><select name=\"" + div_id + "_" + nb_user + "\" id=\"" + div_id + "_" + nb_user + "\">" 
    utilisateurs.forEach(user => {
        users_show += "<option value=" + user['id'] + ">" + (user['nom'] === null ? user['prénom'] : user['nom'] + ' ' + user['prénom'])
    })
    document.getElementById(div_id).innerHTML += users_show
}

function del_user(div_id){
    document.getElementById("div_" + div_id + "_" + nb_user).remove()
    nb_user--
}

function décaler(id, jour, h_début, h_fin, id_entraîneur, div_id, listeEntraîneurs){
    document.getElementById("cours_décalage").innerHTML = '<form action="?id_f=3" method="POST"> \
    <label for="dateChange">Jour : </label> \
    <input name="dateChange" id="dateChange" type="date" value="' + jour +'" required> \
    \
    <label for="h_débutChange">Horaire début : </label> \
    <input name="h_débutChange" id="h_débutChange" type="time" value="' + h_début +'" required> \
    \
    <label for="h_finChange">Horaire fin : </label> \
    <input name="h_finChange" id="h_finChange" type="time" value="' + h_fin +'" required> \
    \
    <label for="entraîneurChange">Entraîneur : </label> \
    <select name="entraîneurChange" id="entraîneurChange">'
    + listeEntraîneurs +
    '</select> \
    \
    <div id="user_changing"></div> \
    \
    <button type="button" onclick="add_user(\'' + div_id + '\')">Ajouter un utilisateur</button> \
    <button type="button" onclick="del_user(\'' + div_id + '\')">Supprimer un utilisateur</button> \
    \
    <input type="hidden" value=' + id + '></input> \
    \
    <input type="submit" value="Modifier"> \
</form>'
}
