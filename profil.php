<?php
    include("./inc/sidebar.php");
?>
    
    <div class="dash">
       
        <main class="dash">
           <div class="col-10 mx-auto profil">
                <div class="titre_p">
                    <h4>Profil </h4> <hr>
                </div>
            
                <div class="row info d-flex">
                    <div class="col-md-3 left_p  col-sm-12 center ">
                        <div class="img">
                            <img src="./inc/img/profile-pic.png" alt="">
                        </div>
                        <p>
                        <?php if(admin()) echo $_SESSION['admin']['Nom_admin'];
                            if(manager()) echo $_SESSION['manager']['Nom_manager'];
                        ?>
                        </p>
                        <button class="modifier_p">Edit</button>
                        <div class="soum">
                            <button class="anuller_p">Annuler</button>
                            <button class="enregistrer_p" onclick="if (valider_profil()) { document.getElementById('form_p').submit(); }">Envoyer</button>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 right_p">
                        
                       <form action="controller.php" method="post" class="col-12 h-100" id="form_p" onsubmit="return valider_profil()">
                            <input type="hidden" name="jeton" value="<?= $_SESSION['jeton'] ?>">
                            <input type="hidden" name="action" value="modifier_profil">
                           <?php
                                if(manager()){
                                    echo " <input type='hidden' name='Id_manager' value='".$_SESSION['manager']['Id_manager'] ."'>";
                                    $id_manager=$_SESSION['manager']['Id_manager'];
                                    $stmt=$pdo->prepare("SELECT * FROM manager where Id_manager=?");
                                    $stmt->execute([$id_manager]);
                                    $manager=$stmt->fetch();
                                    $nom_p = $manager['Nom_manager'];
                                    $date_nc_p =$manager['Date_manager'];
                                    $email_p = $manager['Email_manager'];
                                    $tel_p = $manager['Tele_manager'];
                                    $mdps_p = $manager['Mdps_manager'];
                                }
                                else{
                                    $id_admin=$_SESSION['admin']['Id_admin'];
                                    $stmt=$pdo->prepare("SELECT * FROM admin where Id_admin=?");
                                    $stmt->execute([$id_admin]);
                                    $admin=$stmt->fetch();
                                    $nom_p = $admin['Nom_admin'];
                                    $date_nc_p =$admin['Date_admin'];
                                    $email_p = $admin['Email_admin'];
                                    $tel_p = $admin['Tele_admin'];
                                    $mdps_p = $admin['Mdps_admin'];
                                }
                           ?>

                            <div class="row rew">
                                    <div class="div_input col-md-6 col-sm-12">
                                        <label for="nom_p" class="col-3">Nom :</label>
                                        <input type="text" id="nom_p" class="col-12" name="nom_p" value="<?php echo $nom_p; ?>" readonly>
                                    </div>
                                    <div class="div_input col-md-6 col-sm-12  ">
                                        <label for="date_nc_p" class="col-3">Naissance :</label>
                                        <input type="date" id="date_nc_p" class="col-12" name="date_nc_p" value="<?php echo $date_nc_p; ?>" readonly>
                                    </div>
                            </div>
                            <div class="row rew">
                                <div class="div_input col-md-6 col-sm-12 ">
                                        <label for="email_p" class="col-3">Email :</label>
                                        <input type="text" id="email_p" class="col-12" name="email_p" value="<?php echo $email_p; ?>" readonly>
                                    </div>
                                    <div class="div_input col-md-6 col-sm-12 ">
                                        <label for="tel_p" class="col-3">Téléphone :</label>
                                        <input type="text" id="tel_p" class="col-12" name="tel_p" value="<?php echo $tel_p; ?>" readonly>
                                    </div>
                            </div>
                            <div class="row rew f">
                                    <div class="div_input col-md-6 col-sm-12 ">
                                        <label for="mdps_p" class="col-3">Password :</label>
                                        <input type="password" id="mdps_p" class="col-12" name="mdps_p" value="<?php echo $mdps_p; ?>" readonly>
                                    </div>
                                    <div class="div_input col-md-6 col-sm-12 confirmer">
                                        <label for="mdps_p_c" class="col-3">Confirmer :</label>
                                        <input type="password" id="mdps_p_c" class="col-12" value="" readonly>
                                        <label for="mdps_p_c" id="label_err_confirmer" class="label_err"></label>
                                    </div>
                            </div>
                       </form>
                    </div>
                </div>
           </div>
            <div class="historique col-md-10 mx-auto">
                <div class="titre_p">
                    <h4>Historique </h4> <hr>
                </div>
                <div class="table-responsive">
                    <table class="table hist">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>

                        </thead>
                        <tbody>
                        <?php
                            if (admin()) {
                                // Si l'utilisateur est un administrateur, on affiche toutes les entrées de la table
                                $stmt = $pdo->prepare("SELECT a.Nom_admin, h.Action_his, h.Details_his, h.Date_his 
                                                    FROM historique h
                                                    JOIN admin a ON h.Id_user = a.Id_admin
                                                    where Type_user='admin'
                                                   ");
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["Nom_admin"] . "</td>";
                                    echo "<td>" . $row["Action_his"] . "</td>";
                                    echo "<td>" . $row["Details_his"] . "</td>";
                                    echo "<td>" . $row["Date_his"] . "</td>";
                                    echo "</tr>";
                                }
                                $stmt = $pdo->prepare("SELECT m.Nom_manager, h.Action_his, h.Details_his, h.Date_his
                                FROM historique h
                                JOIN manager m ON h.Id_user = m.Id_manager where Type_user='manager'");
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row["Nom_manager"] . "</td>";
                                echo "<td>" . $row["Action_his"] . "</td>";
                                echo "<td>" . $row["Details_his"] . "</td>";
                                echo "<td>" . $row["Date_his"] . "</td>";
                                echo "</tr>";
                            }
                            } else {
                                // Si l'utilisateur est un manager, on affiche seulement les entrées correspondant à son ID
                                $user_id = $_SESSION['manager']['Id_manager'];
                                $stmt = $pdo->prepare("SELECT m.Nom_manager, h.Action_his, h.Details_his, h.Date_his
                                                    FROM historique h
                                                    JOIN manager m ON h.Id_user = m.Id_manager
                                                    WHERE h.Id_user = ?
                                                    and  Type_user='manager'
                                                    ");
                                $stmt->execute([$user_id]);
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["Nom_manager"] . "</td>";
                                    echo "<td>" . $row["Action_his"] . "</td>";
                                    echo "<td>" . $row["Details_his"] . "</td>";
                                    echo "<td>" . $row["Date_his"] . "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>
         </main>
    </div>






<?php 
include("./inc/bas.inc.html");
?>