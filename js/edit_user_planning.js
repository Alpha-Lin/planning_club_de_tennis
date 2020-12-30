let nb_user_adding = 0
let nb_user_edditing = 0

function add_user(div_id, compteur){ // Ajoute un dropdown de users
    let div_users = document.createElement('div')
    div_users.id = "div_" + div_id + "_" + compteur

    let users_show = document.createElement('select')
    users_show.name = div_id + "_" + compteur
    users_show.id = div_id + "_" + compteur
    utilisateurs.forEach(user => {
        let option = document.createElement('option')
        option.text = user['nom'] === null ? user['prénom'] : user['nom'] + ' ' + user['prénom']
        option.value = user['id']
        users_show.appendChild(option)
    })
    
    let label = document.createElement('label')
    label.htmlFor = div_id + "_" + compteur
    label.textContent = "Élève " + compteur

    div_users.appendChild(label)
    div_users.appendChild(users_show)
    document.getElementById(div_id).appendChild(div_users)
}

function del_user(div_id_compteur){ // Supprimer un dropdown de users
    console.log(div_id_compteur)
    document.getElementById(div_id_compteur).remove()
}

function annuler_change(id, infosCours){ // Annule le mode édition
    document.getElementById("décalage_" + id).innerHTML = "<button type='button' onclick='décaler(" + id + ")'>Éditer le cours</button>"
    let dataCours = document.getElementsByClassName("planning_" + id)
    for (let i = 0; i < dataCours.length; i++) {
        dataCours[i].textContent = infosCours[i]
    }
}

function décaler(id){ // Mode édition
    let dataCours = document.getElementsByClassName("planning_" + id)
    let typeInput = ""
    let idInput = ""
    let infosCours = []
    for (let i = 0; i < dataCours.length; i++) {
        infosCours.push("'" + dataCours[i].textContent + "'")
        switch(i){
            case 0:
                typeInput = "date"
                idInput = "date_Change"
                break
            case 1:
                typeInput = "time"
                idInput = "h_début_Change"
                break
            case 2:
                typeInput = "time"
                idInput = "h_fin_Change"
                break
        }
        if (i < 3){
            dataCours[i].innerHTML = "<input value='" + dataCours[i].textContent +"' type='" + typeInput + "' name='" + idInput + "'/>"
        }else if (i == 3){
            dataCours[i].innerHTML = "<select name='entraîneur_Change'><option value='' selected disabled hidden>" + dataCours[i].textContent + "</option>" + entraîneurs + "</select>"
        }else{
            dataCours[i].innerHTML = "<select name='user_edditing_" + (i - 3) + "' id='user_edditing_" + id + "_" + (i - 3) + "'><option value='' selected disabled hidden>" + dataCours[i].textContent + "</option></select><button type='button' onclick='del_user(this.parentElement.id)'>Supprimer</button>" //TODO
            add_user('user_edditing_' + id + "_" + (i - 3))
        }
    }
    document.getElementById("décalage_" + id).innerHTML = '<button type="button" onclick="annuler_change(' + id + ', [' + infosCours + '])">Annuler</button><input type="submit" value="Confirmer"></button><input name="id_planning_edit" value=' + id + ' hidden/>'
}
