function admin_pannel(nav){
    if (nav){ // On met au premier plan l'interface de gestion des entraînements
        document.getElementById("admin_nav1").style.zIndex = 1
        document.getElementById("admin_nav2").style.zIndex = 0

        document.getElementById("gestion_users").hidden = true
        document.getElementById("gestion_entraînements").hidden = false
    }else{ // On met au premier plan l'interface de gestion d'utilisateurs
        document.getElementById("admin_nav1").style.zIndex = 0
        document.getElementById("admin_nav2").style.zIndex = 1

        document.getElementById("gestion_users").hidden = false
        document.getElementById("gestion_entraînements").hidden = true
    }
}