let nb_user = 0

function add_user(utilisateurs){
    nb_user++
    let users_show = "<div id=\"div_user_" + nb_user + "\"><label for=\"user_" + nb_user + "\">Élève " + nb_user + " : </label><select name=\"user_" + nb_user + "\" id=\"user_" + nb_user + "\">" 
    utilisateurs.forEach(user => {
        users_show += "<option value=" + user['id'] + ">" + (user['nom'] === null ? user['prénom'] : user['nom'] + ' ' + user['prénom'])
    })
    document.getElementById("user_adding").innerHTML += users_show
}

function del_user(){
    document.getElementById("div_user_" + nb_user).remove()
    nb_user--
}
