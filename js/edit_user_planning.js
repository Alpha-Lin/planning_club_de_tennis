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

function annuler_change(id, infosCours){
    document.getElementById("décalage_" + id).innerHTML = "<button type='button' onclick='décaler(" + id + ")'>Éditer le cours</button>"
    let dataCours = document.getElementsByClassName("planning_" + id)
    for (let i = 0; i < dataCours.length; i++) {
        console.log(infosCours)
        dataCours[i].textContent = infosCours[i]
    }
}

function décaler(id){
    let dataCours = document.getElementsByClassName("planning_" + id)
    let typeInput = ""
    let idInput = ""
    let infosCours = []
    for (let i = 0; i < dataCours.length; i++) {
        infosCours.push("'" + dataCours[i].textContent + "'")
        switch(i){
            case 0:
                typeInput = "date"
                idInput = "dateChange"
                break
            case 1:
                typeInput = "time"
                idInput = "h_début_change"
                break
            case 2:
                typeInput = "time"
                idInput = "h_fin_Change"
                break
        }
        if (i < 3){
            dataCours[i].innerHTML = "<input value='" + dataCours[i].textContent +"' type='" + typeInput + "' id='" + idInput + "'/>"
        }else{
            if (i == 3){
                dataCours[i].innerHTML = "<select id='entraîneurChange'>" + entraîneurs + "</select>"
            }else{
                dataCours[i].innerHTML = "<select id='user_edditing_" + id + "_" + (i -3) + "'></select>"
                add_user('user_edditing_' + id + "_" + (i -3))
            }
        }
    }
    document.getElementById("décalage_" + id).innerHTML = '<button type="button" onclick="annuler_change(' + id + ', [' + infosCours + '])">Annuler</button><input type="submit" value="Confirmer"></button>'
}
