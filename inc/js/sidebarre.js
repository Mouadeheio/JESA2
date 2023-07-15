$(document).ready(function() {
    $('.inptCheckDisc').change(function() {
        var Id_discipline = $(this).data('discipline');
        var Id_phase = $(this).data('phase');
        var Id_projet = $(this).data('id');
        $.ajax({
            url: './controller.php',
            type: 'POST',
            data: { Id_projet: Id_projet, Id_discipline: Id_discipline, Id_phase: Id_phase, updateDiscipline: true },
            dataType: 'json',
            success: function(response) {
                location.reload(true);
            },
            error: function() {
                alert('Une erreur s\'est produite');
            }
        });
    });
});
$(document).ready(function() {
    var table = $('#mytab').DataTable({
        searching: true,
        paging: true,
        pageLength: 6,
        responsive: true,
        language: {
            search: "",
            lengthMenu: "afficher _MENU_ ",
            zeroRecords: "Aucun résultat",
            info: "<span class='gras'> _START_ </span>&nbsp; à<span class='gras'>&nbsp; _END_</span>&nbsp; sur <span class='gras'>&nbsp; _TOTAL_ </span>&nbsp;",
            infoEmpty: "Aucune donnée disponible",
            infoFiltered: "",
            recordsTotal: "Nombre total d'entrées : _TOTAL_",
            recordsFiltered: "Nombre total d'entrées correspondantes : _MAX_",
            paginate: {
                previous: '<i class="fa-solid fa-chevron-left"></i>',
                next: '<i class="fa-solid fa-chevron-right"></i>'
            }
        }
    });
    
    $('#mytab').removeAttr('style');
    $('#mytab_filter label').prepend('<i class="fa-solid fa-magnifying-glass"></i>');
    $('#mytab_filter label input').attr('placeholder', 'Search for a project...');
    $('#mytab_wrapper').prepend('<div class="ajouter_projet"><button class="btn_ajt_prjt"  data-bs-toggle="modal" data-bs-target="#myModal">Ajouter projet</button></div>');
    
    $('#recherche-nom, #recherche-code, #recherche-phase, #recherche-discipline').on('input', function() {
        var nom = $('#recherche-nom').val();
        var code = $('#recherche-code').val();
        var phase = $('#recherche-phase').val();
        var discipline = $('#recherche-discipline').val();
        
        table.search('').columns().search('').draw();
        
        if (nom) {
            table.columns(2).search(nom).draw();
        }
        if (code) {
            table.columns(1).search(code).draw();
        }
        if (phase) {
            table.columns(4).search(phase).draw();
        }
        if (discipline) {
            table.columns(5).search(discipline).draw();
        }
    });
});


var label_erreur_email = document.getElementById("label_erreur_email");
var label_erreur_nom = document.getElementById("label_erreur_nom");
var label_erreur_mdps = document.getElementById("label_erreur_mdps");



$(document).ready(function() {
    $('#mytab_compte').DataTable({
        searching: true,
        paging: true,
        pageLength: 4,
        responsive: true,
        language: {
            search: "",
            lengthMenu: "afficher _MENU_ ",
            zeroRecords: "Aucun résultat",
            info: "<span class='gras'> _START_ </span>&nbsp; à<span class='gras'>&nbsp; _END_</span>&nbsp; sur <span class='gras'>&nbsp; _TOTAL_ </span>&nbsp;",
            infoEmpty: "Aucune donnée disponible",
            infoFiltered: "",
            recordsTotal: "Nombre total d'entrées : _TOTAL_",
            recordsFiltered: "Nombre total d'entrées correspondantes : _MAX_",
            paginate: {
                previous: '<i class="fa-solid fa-chevron-left"></i>',
                next: '<i class="fa-solid fa-chevron-right"></i>'
            }
        }
    });
    $('#mytab_compte_filter label').prepend('<i class="fa-solid fa-magnifying-glass"></i>');
    $('#mytab_compte_filter label input').attr('placeholder', 'Search for a project...');
    $('#mytab_compte_wrapper').prepend('<div class="ajouter_manager"><button type="button" class="btn btn-primary btn_ajt_mngr" data-bs-toggle="modal" data-bs-target="#exampleModal"  >Ajouter manager</button></div>');
    $('.btn_ajt_mngr').on('click', function() {
        $('#form_manager').trigger('reset');
        $('#form_manager').find('#btn_submit').val('Ajouter');
        $('#form_manager').find('#form_action').val('ajouter_manager');
        label_erreur_mdps.textContent = "";
        label_erreur_email.textContent = "";
        label_erreur_nom.textContent = "";
    });
});



// Vérifier si la phase actuelle est stockée dans le local storage
if(localStorage.getItem('currentPhase')){
    // Récupérer la phase actuelle depuis le local storage
       var currentPhase = localStorage.getItem('currentPhase');
       // Supprimer la classe 'active' de l'onglet par défaut
       if (currentPhase != '#details') {
            $('#details-tab-pane').removeClass('active show');
            $('#details-tab').removeClass('active');
        }
        
       // Activer l'onglet de la phase actuelle
       $('#phase' + currentPhase + '-tab').addClass('active');
       // Afficher le contenu de la phase actuelle
       $('#phase' + currentPhase + '-tab-pane').addClass('show active');
}

// Enregistrerla phase actuelle dans le local storage lorsque l'utilisateur change de phase
$('.nav-link').on('click', function(){
    // Récupérer l'identifiant de la phase actuelle
    var currentPhase = $(this).attr('data-bs-target').replace('#phase', '').replace('-tab-pane', '');
    // Supprimer la phase précédemment stockée dans le local storage et enregistrer la nouvelle phase
    var previousPhase = localStorage.getItem('currentPhase');
    if(previousPhase){
        localStorage.removeItem('phase' + previousPhase);
    }
    localStorage.setItem('currentPhase', currentPhase);
    localStorage.setItem('phase' + currentPhase, true);
    
    // Supprimer la classe 'active' de l'onglet par défaut
    if ($(this).attr('data-bs-target') !== '#details-tab_pane') {
        $('#details-tab-pane').removeClass('show active');
        $('#details-tab').removeClass('active');
    }
    // Mettre à jour les classes 'active' des onglets de phase
    $('.nav-link, .tab-pane').removeClass('show active');
    $(this).addClass('active');
    $($(this).attr('data-bs-target')).addClass('show active');
});


$(document).ready(function() {
    // Click event handler for the "view-projet" button
    $('.btn_voir_prjt').click(function() {
      var projetId = $(this).closest('tr').find('td:first').text();
  
      // Make an AJAX call to retrieve the project information
      $.ajax({
        url: 'controller.php',
        type: 'POST',
        data: {id_projet_voir: projetId},
        success: function(response) {
          // Display the project information in the modal
          $('#projetModal .modal-body').html(response);
  
          // Show the modal
          $('#projetModal').modal('show');
        },
        error: function() {
          alert('Error retrieving project information.');
        }
      });
    });
  });


$('.btn_modif_mngr').click(function() {
    var id_manager = $(this).closest('tr').find('.id').text();

    $.ajax({
        url: './controller.php',
        type: 'GET',
        data: { id_recu: id_manager },
        dataType: 'JSON',
        success: function(responce) {

            $('#id').val(responce.Id_manager);
            $('#nom').val(responce.Nom_manager);
            $('#email').val(responce.Email_manager);
            $('#mdps').val(responce.Mdps_manager);
            $('#form_manager').find('#btn_submit').val('Modifier');
            $('#form_manager').find('#form_action').val('modifier_manager');
            label_erreur_mdps.textContent = "";
            label_erreur_email.textContent = "";
            label_erreur_nom.textContent = "";
        },
        error: function(xhr, status, error) {
            console.log("Erreur AJAX : " + status + " " + error);
            console.log(xhr.responseText);
        }
    });
});

// var div_display_action_manager = document.querySelector('.div_display_action_manager');
// var exit_form_action_manager = document.querySelector('.exit_form_action_manager');
// var div_display_form_action_manager = document.querySelector('.div_display_form_action_manager');

// exit_form_action_manager.addEventListener('click', function(event) {
//     event.preventDefault();  
//     div_display_action_manager.classList.add('cacher');  
// });


var btn_ajouter_discipline = document.querySelector(".btn_ajouter_discipline");
var tbody_table_phase = document.querySelector(".tbody_table_phase");

btn_ajouter_discipline.addEventListener("click", () => {
  tbody_table_phase.innerHTML += "<tr> <td colspan='3'><form class='form_ajouter_discipline d-flex'><div class='td col-4'><input type='text' name='titre_discipline' id=''></div><div class='td col-5'><input type='number' name='numero_discipline' id=''></div><div class='td col-3'><button class='reset_discipline' type='reset'><i class='fa-solid fa-xmark'></i></button> <button class='valider_discipline' type='submit'> <i class='fa-solid fa-check'></i></button></div></form></td> </tr>";
});


let iconEye = document.querySelectorAll('.fa-solid.fa-eye.mdps');
let inptMdps = document.querySelectorAll('.inpt_mdps');

iconEye.forEach(icon => {
    icon.addEventListener('click', function() {
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
        inptMdps.forEach(input => {
            input.type = (input.type === 'password') ? 'text' : 'password';
        });
    });
});




var changer = document.querySelector(".changer");
var  body = document.querySelector("#body");
var  sidebar = document.querySelector(".sidebar");
var  header = document.querySelector(".fix");
changer.addEventListener("click", () => {
    changer.classList.toggle("fa-xmark");
    body.classList.toggle("body");
    sidebar.classList.toggle("voir");
    header.classList.toggle("body");
});






var inputs_profile = document.querySelectorAll(".div_input input");
var modifier_p = document.querySelector(".modifier_p");
var anuller_p = document.querySelector(".anuller_p");
var soum = document.querySelector(".soum");
var enregistrer_p = document.querySelector(".enregistrer_p");
var form_p = document.querySelector("#form_p");
var label_err_confirmer=  document.getElementById("label_err_confirmer") ;
var confirmer = document.querySelector(".confirmer");
if(modifier_p){
    modifier_p.addEventListener("click", () => {
        soum.style.display="block";
        modifier_p.style.display='none';
        confirmer.style.display='block';
        inputs_profile.forEach((input) => {
            input.classList.add("not_readonly");
            input.removeAttribute("readonly");
            
        });
    });
}

if(anuller_p){
anuller_p.addEventListener("click", () => {
    soum.style.display="none";
    modifier_p.style.display='block';
    confirmer.style.display='none';
    label_err_confirmer.textContent="";
    inputs_profile.forEach((input) => {
        input.classList.remove("not_readonly");
        input.setAttribute("readonly",true);
    });
});

}

