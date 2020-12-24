let nb_user = 0

function add_user(utilisateurs){
    nb_user++
    let users_show = "<select name=\"user_" + nb_user + "\" id=\"user_" + nb_user + "\">" 
    utilisateurs.forEach(user => {
        users_show += "<option value=" + user['id'] + ">" + (user['nom'] === null ? user['prénom'] : user['nom'] + ' ' + user['prénom']) + "</option>"
    })
    document.getElementById("user_adding").innerHTML += users_show
}

function del_user(){
    document.getElementById("user_" + nb_user).remove()
    nb_user--
}
