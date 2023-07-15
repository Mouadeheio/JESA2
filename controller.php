
<?php

// des fichiers d'initialisation
require_once './inc/init.inc.php';
// connexion
try {
 
    if(isset($_POST['Con'])) {
        $email = trim($_POST['Email_con']);
        $mdps = $_POST['Mdps_con'];
        setcookie('email',$email,time()+60);
        $rslt1 = $pdo->prepare("SELECT * FROM admin WHERE Email_admin = ?");
        $rslt1->execute([$email]);
        $row1=$rslt1->fetch();
        if(!empty($row1)) {
            $rslt1 = $pdo->prepare("SELECT * FROM admin WHERE Email_admin = ? and Mdps_admin = ?");
            $rslt1->execute([$email, $mdps]);
            $row2=$rslt1->fetch();
            if(!empty($row2)) {
                foreach($row2 as $indice => $element) {
                    if($indice != 'Mdps_admin') {
                        $_SESSION['admin'][$indice] = $element; 
                    }
                }
                
                $_COOKIE['email']=$_SESSION['admin']['Email_admin'];
                $_SESSION['jeton'] = bin2hex(random_bytes(32));

                $hostname = gethostname();
                if(!empty($_SESSION['admin'])) {
                    $id_user = $_SESSION['admin']['Id_admin'];
                    $action = "Connexion";
                    $type_user = "admin";
                }
                $details = "" . $email . " s'est connecté depuis l'appareil $hostname";
                $date = date('Y-m-d H:i:s');
                $rslt3 = $pdo->prepare("INSERT INTO historique (Id_user, Type_user, Action_his, Details_his, Date_his) VALUES (?, ?, ?, ?, ?)");
                $rslt3->execute([$id_user, $type_user, $action, $details, $date]);
                header("Location: accueil.php");
                exit;
            } else {
                $_SESSION['erreurMdps'] = "Mot de passe incorrect";
                header("Location: index.php");
                exit;
            }
        } else{
            $rslt2=$pdo->prepare("SELECT * FROM manager WHERE Email_manager=?");
            $rslt2->execute([$email]);
            $row1=$rslt2->fetch();
            if(!empty($row1)) {
                $rslt2=$pdo->prepare("SELECT * FROM manager WHERE Email_manager=? and Mdps_manager=?");
                $rslt2->execute([$email,$mdps]);
                $row2=$rslt2->fetch();
                if(!empty($row2)) {
                    foreach($row2 as $indice => $element){
                        if($indice != 'Mdps_manager'){
                            $_SESSION['manager'][$indice] = $element;
                        }
                    }
                    $_COOKIE['email']=$_SESSION['manager']['Email_manager'];
                    $_SESSION['jeton'] = bin2hex(random_bytes(32));
                    $hostname = gethostname();
                    if(!empty($_SESSION['manager'])) {
                        $id_user = $_SESSION['manager']['Id_manager'];
                        $action = "Connexion";
                        $type_user = "manager";
                    }
                    $details = "" . $email . " s'est connecté depuis l'appareil $hostname";
                    $date = date('Y-m-d H:i:s');
                    $rslt3 = $pdo->prepare("INSERT INTO historique (Id_user, Type_user, Action_his, Details_his, Date_his) VALUES (?, ?, ?, ?, ?)");
                    $rslt3->execute([$id_user, $type_user, $action, $details, $date]);
                    header("Location: accueil.php");
                    exit;
                }
                else{
                    $_SESSION['erreurMdps'] = "Mot de passe incorrecte";
                    header("location:index.php");
                    exit;
                }
            }
            else{
                setcookie('Email',$email,time()-1);
                $_SESSION['erreurEmail'] = "Email incorrecte";
                header("location:index.php");
                exit;
            }
        }
    }
} catch(PDOException $e) {
    $_SESSION['erreurlog'] = "Erreur lors de la connexion : " . $e->getMessage();
    header("Location: index.php");
    exit;
}
// déconnexion
if(isset($_GET['déconnexion'])){
    // Récupération du nom de l'appareil connecté
    $hostname = gethostname();

    // Insertion de l'action de déconnexion dans la table "historique"
    if(admin()) {
        $id_user = $_SESSION['admin']['Id_admin'];
        $action = "Déconnexion";
        $type_user = "admin";
    }
    else if(manager()) {
        $id_user = $_SESSION['manager']['Id_manager'];
        $action = "Déconnexion";
        $type_user = "manager";
    }
    $details = "" . $_COOKIE['email'] ." s'est déconnecté depuis l'appareil " . $hostname;
    $date = date('Y-m-d H:i:s');
    $rslt3 = $pdo->prepare("INSERT INTO historique (Id_user, Type_user, Action_his, Details_his, Date_his) VALUES (?, ?, ?, ?, ?)");
    $rslt3->execute([$id_user, $type_user, $action, $details, $date]);

    // Suppression des données de session et des cookies
    unset($_SESSION['jeton']);
    session_unset();
    $_SESSION = array();
    setcookie(session_name(), ' ', time()-1);
    session_destroy();
    header('Location:index.php');
    exit;
}
//suppression et modification de manager
if (isset($_POST['jeton']) && $_POST['jeton'] == $_SESSION['jeton']) {
    switch ($_POST['action']) {
        case 'supprimer_manager':
            $id_manager = $_POST['Id_manager'];
            $stmt = $pdo->prepare("DELETE FROM projet WHERE Id_manager = ?");
            $stmt->execute([$id_manager]);
            $stmt = $pdo->prepare("DELETE FROM manager WHERE Id_manager = ?");
            $stmt->execute([$id_manager]);
            
            $user_id=$_SESSION['admin']['Id_admin'];
            $user_type='admin';
            $action="Suppression manager $id_manager";
            $details='manager ';
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            header("location: compte.php");
            exit;
            break;

        case 'modifier_manager':
            $id_manager = trim($_POST['Id_manager']);
            $nom_manager = trim($_POST['Nom_manager']);
            $email_manager = trim($_POST['Email_manager']);
            $mdps_manager = $_POST['Mdps_manager'];
            $stmt = $pdo->prepare("UPDATE manager SET Nom_manager = ?, Email_manager = ?, Mdps_manager = ? WHERE manager.Id_manager = ?");
            $stmt->execute([$nom_manager, $email_manager, $mdps_manager, $id_manager]);

            $user_id=$_SESSION['admin']['Id_admin'];
            $user_type='admin';
            $action="Modification manager $id_manager";
            $details="manager";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            

            header("location: compte.php");
            exit;
            break;

        case 'ajouter_manager':
            $nom_manager = trim($_POST['Nom_manager']);
            $email_manager = trim($_POST['Email_manager']);
            $mdps_manager = $_POST['Mdps_manager'];
            $stmt=$pdo->prepare("INSERT INTO manager value (null,?,null,null,?,?)");
            $stmt->execute([$nom_manager,$email_manager,$mdps_manager]);
            
            $id_manager = $pdo->lastInsertId();
            $user_id=$_SESSION['admin']['Id_admin'];
            $user_type='admin';
            $action="Ajout manager $id_manager";
            $details="manager ";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            
            header("location: compte.php");
            exit;
            break;
        
        case 'ajouter_projet':
            $id_manager=$_POST['Id_manager'];    
            $nom_projet=$_POST['Nom_projet'];  
            $code_projet=$_POST['Code_projet']; 
            $date_projet=$_POST['Date_projet']; 
            $estimated_projet=$_POST['Estimated_projet']; 
            $burned_projet=$_POST['Burned_projet']; 
            $stmt=$pdo->prepare("INSERT INTO projet VALUE(NULL,?,?,?,?,1,1,?,?)");
            $stmt->execute([$id_manager,$code_projet,$nom_projet,$date_projet,$estimated_projet,$burned_projet]);

            $id_projet = $pdo->lastInsertId();
            $action="Ajout projet $id_projet";
            $user_id=admin() ? $_SESSION['admin']['Id_admin']:$_SESSION['manager']['Id_manager'];
            $user_type=admin() ? "admin":'manager';
            $details='';
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
         
            header("location: projet.php");
                exit;
                break;

        case 'modifier_projet':
            $id_projet = $_POST['id_projet'];
            $id_manager=$_POST['manager_pr'];    
            $nom_projet=$_POST['nom_pr'];  
            $code_projet=$_POST['code_pr']; 
            $date_projet=$_POST['date_pr']; 
            $id_phase=$_POST['phase_pr'];
            $Id_discipline=$_POST['discipline_pr'];
            $estimated_projet=$_POST['estimated_pr']; 
            $burned_projet=$_POST['burned_pr']; 
            $stmt = $pdo->prepare("UPDATE projet SET Id_manager=?, Code_projet=?, Nom_projet=?, Date_projet=?, Id_phase=?, Id_discipline=?, Estimated=?, Burned=? WHERE Id_projet=?");
            $stmt->execute([$id_manager, $code_projet, $nom_projet, $date_projet, $id_phase, $Id_discipline, $estimated_projet, $burned_projet, $id_projet]);
            
            $action="Modification projet $id_projet";
            $user_id=admin() ? $_SESSION['admin']['Id_admin']:$_SESSION['manager']['Id_manager'];
            $user_type=admin() ? "admin":'manager';
            $details='';
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            header("location: projet.php");
                exit;
                break;

        case 'supprimer_projet':
            $id_projet = $_POST['Id_projet'];
            $stmt = $pdo->prepare("DELETE FROM projet WHERE Id_projet = ?");
            $stmt->execute([$id_projet]);
            
            $action="Suppression projet $id_projet";
            $user_id=admin() ? $_SESSION['admin']['Id_admin']:$_SESSION['manager']['Id_manager'];
            $user_type=admin() ? "admin":'manager';
            $details='';
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            header("location: projet.php");
            exit;
            break;

        case 'modifier_profil':
            $id_manager=$_POST['Id_manager'];    
            $nom=$_POST['nom_p'];
            $date=$_POST['date_nc_p'];
            $email=$_POST['email_p'];
            $tel=$_POST['tel_p'];
            $mdps=$_POST['mdps_p'];
            if(admin()){
                $stmt = $pdo->prepare("UPDATE admin SET Nom_admin = ?, Date_admin = ?, Tele_admin = ?, Email_admin = ?, Mdps_admin = ?");
                $stmt->execute([$nom, $date, $tel, $email, $mdps, ]);

            }
            if(manager()){
                $stmt = $pdo->prepare("UPDATE manager SET Nom_manager = ?, Date_manager = ?, Tele_manager = ?, Email_manager = ?, Mdps_manager = ? WHERE Id_manager = ?");
                $stmt->execute([$nom, $date, $tel, $email, $mdps, $id_manager]);
            }
            header("location: profil.php");
                exit;
                break;
            

        case 'supprimer_discipline':
            $id_discipline=$_POST['Id_discipline'];
            $numero_discipline = $_POST['numero_discipline'];
            $id_phase = $_POST['Id_phase'];
            $stmt = $pdo->prepare("DELETE FROM discipline WHERE Id_discipline = ?");
            if($stmt->execute([$id_discipline])){
                $stmt = $pdo->prepare("UPDATE discipline SET numero_discipline = numero_discipline - 1 WHERE Id_phase = ? AND numero_discipline > ?");
                $stmt->execute([$id_phase, $numero_discipline]);
            }
            
    }
}
if(isset($_GET['id_recu'])){
    $id_manager = $_GET['id_recu'];
    $stmt = $pdo->prepare("SELECT * FROM manager WHERE Id_manager = ?");
    $stmt->execute([$id_manager]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json'); // Ajouter cette ligne pour spécifier que la réponse est en JSON
    echo json_encode($row);
}
if(isset($_POST['id_projet_voir'])){
    $id_projet=$_POST['id_projet_voir'];
    $stmt=$pdo->prepare("SELECT p.* , ph.* , d.* , m.*
    FROM projet p 
    inner join manager m on p.Id_manager=m.Id_manager
    left join phase ph on p.Id_phase=ph.Id_phase 
    left JOIN discipline d ON p.Id_discipline= d.Id_discipline
    where Id_projet=?");
    $stmt->execute([$id_projet]);
    $projet_row = $stmt->fetch(PDO::FETCH_ASSOC);

// Generate HTML code for the project information
if(admin()){
    $html = "<div><strong>Manager:</strong> " . htmlspecialchars($projet_row['Nom_manager']) . "</div>";
    $html .= "<div><strong>Code projet:</strong> " . htmlspecialchars($projet_row['Code_projet']) . "</div>"
      . "<div><strong>Nom projet:</strong> " . htmlspecialchars($projet_row['Nom_projet']) . "</div>"
      . "<div><strong>Date projet:</strong> " . htmlspecialchars($projet_row['Date_projet']) . "</div>"
      . "<div><strong>Phase:</strong> " . htmlspecialchars($projet_row['Titre_phase']) . "</div>"
      . "<div><strong>Discipline:</strong> " . htmlspecialchars($projet_row['Titre_discipline']) . "</div>"
      . "<div><strong>Estimated:</strong> " . htmlspecialchars($projet_row['Estimated']) . "</div>"
      . "<div><strong>Burned:</strong> " . htmlspecialchars($projet_row['Burned']) . "</div>";

}
if(manager()){
    $html = "<div><strong>Code projet:</strong> " . htmlspecialchars($projet_row['Code_projet']) . "</div>"
      . "<div><strong>Nom projet:</strong> " . htmlspecialchars($projet_row['Nom_projet']) . "</div>"
      . "<div><strong>Date projet:</strong> " . htmlspecialchars($projet_row['Date_projet']) . "</div>"
      . "<div><strong>Phase:</strong> " . htmlspecialchars($projet_row['Titre_phase']) . "</div>"
      . "<div><strong>Discipline:</strong> " . htmlspecialchars($projet_row['Titre_discipline']) . "</div>"
      . "<div><strong>Estimated:</strong> " . htmlspecialchars($projet_row['Estimated']) . "</div>"
      . "<div><strong>Burned:</strong> " . htmlspecialchars($projet_row['Burned']) . "</div>";

}
// Return the HTML code
echo $html;
}



if (isset($_POST['Email_exist_modif'])) {
    $email = trim($_POST['Email_exist_modif']);
    $id_manager = trim($_POST['Id_manager']);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM manager WHERE Email_manager = :email AND Id_manager != :id_manager");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_manager', $id_manager);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        echo "0"; // l'email n'existe pas dans la base de données
    } else {
        echo "1"; // l'email existe déjà dans la base de données
    }
}
if (isset($_POST['Email_exist_ajout'])) {
    $email = trim($_POST['Email_exist_ajout']);
    // $id_manager = trim($_POST['Id_manager']);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM manager WHERE Email_manager = :email ");
    $stmt->bindParam(':email', $email);
    // $stmt->bindParam(':id_manager', $id_manager);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        echo "0"; // l'email n'existe pas dans la base de données
    } else {
        echo "1"; // l'email existe déjà dans la base de données
    }
}

if (isset($_POST['updateDiscipline'])) {
    $Id_discipline = $_POST['Id_discipline'];
    $Id_phase = $_POST['Id_phase'];
    $Id_projet = $_POST['Id_projet'];

    // Chercher la discipline actuelle
    $rslt1 = $pdo->query("SELECT * FROM discipline WHERE Id_discipline = '$Id_discipline'");
    $row1 = $rslt1->fetch();

    // Chercher la phase actuelle
    $rslt3 = $pdo->query("SELECT * FROM phase WHERE Id_phase = '$Id_phase'");
    $row3 = $rslt3->fetch();

    // Trouver le numéro de la prochaine discipline
    $num = ++$row1['numero_discipline'];

    // Chercher la prochaine discipline dans la même phase
    $rslt2 = $pdo->query("SELECT * FROM discipline WHERE numero_discipline = '$num' AND Id_phase = '$Id_phase'");
    $row2 = $rslt2->fetch();

    if ($row2) {
        // Mettre à jour le projet avec la prochaine discipline
        $rslt = $pdo->exec("UPDATE projet SET Id_discipline = '$row2[Id_discipline]', Id_phase = '$Id_phase' WHERE Id_projet = '$Id_projet'");

    } else {
        // Si la prochaine discipline n'existe pas dans la même phase, chercher la prochaine phase
        $phase = ++$row3['numero_phase'];
        $rslt4 = $pdo->query("SELECT * FROM phase WHERE numero_phase = '$phase'");
        $row4 = $rslt4->fetch();

        if($row4){
            // Chercher la première discipline de la prochaine phase
            $rslt5 = $pdo->query("SELECT * FROM discipline WHERE numero_discipline = '1' AND Id_phase = '$row4[Id_phase]'");
            $row5 = $rslt5->fetch();
            $rslt = $pdo->exec("UPDATE projet SET Id_discipline = '$row5[Id_discipline]', Id_phase = '$row5[Id_phase]' WHERE Id_projet = '$Id_projet'");
        }
        else{
            // Si la prochaine phase n'existe pas, mettre à jour le projet avec la discipline et la phase actuelles
            $rslt = $pdo->exec("UPDATE projet SET Id_discipline = '$_POST[Id_discipline]', Id_phase = '$_POST[Id_phase]' WHERE Id_projet = '$Id_projet'");

            // Vérifier si la dernière discipline de la dernière phase est sélectionnée
            $rslt_last_discipline = $pdo->query("SELECT * FROM discipline WHERE Id_phase = (SELECT MAX(Id_phase) FROM phase) ORDER BY numero_discipline DESC LIMIT 1");
            $last_discipline = $rslt_last_discipline->fetch();
            if ($last_discipline['Id_discipline'] == $_POST['Id_discipline']) {
                // Désactiver et cocher la dernière discipline
                echo "<script>$('#last_discipline').prop('disabled', true); $('#last_discipline').prop('checked', true);</script>";
            }
        }
    }
    // Retourner le résultat de la mise à jour
    echo $rslt;
}
?>