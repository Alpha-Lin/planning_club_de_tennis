let nb_user_adding = 0
let nb_user_edditing = []
let infosCours = []
let nb_user_removed = []

function add_user(div_id, compteur, div_id_parent){ // Ajoute un dropdown de users
    let div_users = document.createElement('div')

    const nameDiv = div_id + "_" + compteur

    div_users.id = "div_" + nameDiv

    let users_show = document.createElement('select')
    users_show.name = nameDiv
    users_show.id = nameDiv
    utilisateurs.forEach(user => {
        let option = document.createElement('option')
        option.text = user['nom'] === null ? user['prénom'] : user['nom'] + ' ' + user['prénom']
        option.value = user['id']
        users_show.appendChild(option)
    })
    
    let label = document.createElement('label')
    label.htmlFor = nameDiv
    label.textContent = "Élève " + compteur + " : "

    div_users.appendChild(label)
    div_users.appendChild(users_show)
    if(typeof div_id_parent === 'undefined'){
        div_id_parent = div_id
    }
    document.getElementById(div_id_parent).appendChild(div_users)
    return div_users
}

function del_user(div_id_compteur){ // Supprimer un dropdown de users
    document.getElementById(div_id_compteur).remove()
}

function annuler_change(id){ // Annule le mode édition
    parentTr = document.getElementById("tr_planning_" + id)
    nb_user_removed[parentTr.id] = 0
    parentTr.innerHTML = infosCours[id]
}

function save_deleted_user(parentId, id, idPlanning){ // sauvegarde les ids des élèves supprimés
    if(typeof nb_user_removed[parentId.parentElement.id] === 'undefined'){
        nb_user_removed[parentId.parentElement.id] = 0
    }
    nb_user_removed[parentId.parentElement.id]++
    
    let hidden_removed_id_user = document.createElement('input')
    hidden_removed_id_user.value = id
    hidden_removed_id_user.type = 'hidden'
    hidden_removed_id_user.name = "removed_user_" + idPlanning + "_" + nb_user_removed[parentId.parentElement.id]

    document.getElementById(parentId.parentElement.id).appendChild(hidden_removed_id_user)
    del_user(parentId.id)
}

function add_length(){
    Array.prototype.forEach.call(document.getElementsByClassName("editable_length"), function (element) {
        element.colSpan += 1
    })
}

function décaler(id){ // Mode édition
    let dataCours = document.getElementsByClassName("planning_" + id)
    let typeInput = ""
    let idInput = ""
    infosCours[id] = document.getElementById("tr_planning_" + id).innerHTML
    nb_user_edditing[id] = 0
    for (let i = 0; i < dataCours.length + 1; i++) {
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
        }else if (i == dataCours.length){
            nb_user_edditing[id] = 0
            let button_add_user = document.createElement('button')
            button_add_user.type = 'button'
            button_add_user.onclick = function(){
                let new_User = document.createElement('td')
                nb_user_edditing[id]++
                new_User.id = "planning_" + id + "&_" + nb_user_edditing[id]
                
                document.getElementById("tr_planning_" + id).appendChild(new_User)

                add_user("planning_" + id + "&" , nb_user_edditing[id], new_User.id)

                // Supprime le label "Élève : n"
                new_User.getElementsByTagName('label')[0].remove()

                new_User.innerHTML += '<button type="button" onclick=\'del_user("' + new_User.id + '")\'>Supprimer</button>'
                document.getElementById("tr_planning_" + id).removeChild(button_add_user)
                document.getElementById("tr_planning_" + id).appendChild(button_add_user)
                add_length()
            }
            // Créer le boutton "Ajouter un utilisateur"
            button_add_user.textContent = 'Ajouter un utilisateur'
            document.getElementById("tr_planning_" + id).appendChild(button_add_user)
            add_length()
        }else{
            nb_user_edditing[id]++
            nom_élève = dataCours[i].textContent.replace(/'/g, '')
            idUserDefault = dataCours[i].id.replace("planning_" + id + "_", '')
            dataCours[i].innerHTML = ""
            let div_user_temp = add_user("planning_" + id, nb_user_edditing[id], dataCours[i].id)
            dataCours[i].getElementsByTagName('label')[0].remove()
            dataCours[i].getElementsByTagName('select')[0].innerHTML += "<option value='' selected disabled hidden>" + nom_élève + "</option>"

            div_user_temp.innerHTML += "<button type='button' onclick='save_deleted_user(this.parentElement.parentElement, " + idUserDefault + ", " + id +")'>Supprimer</button>"
        }
    }

    document.getElementById("décalage_" + id).innerHTML = '<button type="button" onclick=\'annuler_change(' + id + ')\'>Annuler</button><input type="submit" value="Confirmer"></button><input name="id_planning_edit" value=' + id + ' hidden/>'
}
