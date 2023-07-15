function validation() {
    var nom = document.getElementById("nom").value;
    var email = document.getElementById("email").value;
    var mdps = document.getElementById("mdps").value;

    var action = document.getElementById("form_action").value;
    
    label_erreur_mdps.textContent = "";
    label_erreur_email.textContent = "";
    label_erreur_nom.textContent = "";
    var aide = true;
        if (nom == "") {
            label_erreur_nom.textContent = "Veuillez saisir un nom.";
            aide = false;
        }
        if (email == "") {
            label_erreur_email.textContent = "Veuillez saisir une adresse e-mail.";
            aide = false;
        } 
        if (mdps == "") {
            label_erreur_mdps.textContent = "Veuillez saisir un mot de passe.";
            aide = false;
        } 
    var id_manager = document.getElementById("id").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'controller.php',false);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200 &&  xhr.responseText.trim() === "1") {
            label_erreur_email.textContent = "L'adresse e-mail saisie existe déjà.";
            aide = false;
        } 
    };
    if (action=='ajouter_manager') {
        xhr.send("Email_exist_ajout=" + email);
    }
    if (action=='modifier_manager') {
        xhr.send("Email_exist_modif=" + email + "&Id_manager=" + id_manager);
    }
  
    return aide;
}

function valide_connexion(){
    var email_con=document.getElementById("email_con").value;
    var mdps_con=document.getElementById("mdps_con").value;
    var label_err_email_con = document.getElementById("label_err_email_con");
    var label_err_mdps_con=document.getElementById("label_err_mdps_con");
    var aide=true;
    
    label_err_email_con.textContent="";
    label_err_mdps_con.textContent="";

    if(email_con==""){
        aide=false;
        label_err_email_con.textContent="Veuillez saisir votre adresse e-mail.";
    }
    if(mdps_con==""){
        aide=false;
        label_err_mdps_con.textContent="Veuillez saisir votre mot de passe.";
    }
    return aide;
}




function valider_profil(){
    var mdps_p=document.querySelector("#mdps_p").value;
    var mdps_p_c=document.querySelector("#mdps_p_c").value;
    var aide=true;
    if(mdps_p!=mdps_p_c){
        label_err_confirmer.textContent ="les mots de passe ne sont pas identiques";
        aide=false;
    }

    return aide;
}



