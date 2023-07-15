<?php
    include("./inc/sidebar.php");
?>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered ">
	  <div class="modal-content">
		<div class="modal-header bg-primary text-white">
		  <h5 class="modal-title">Manager</h5>
		  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
		  <div class="row">
			<div class="col-md-6 img">
			  <img src="./inc/img/user1.png" class="img-fluid">
			</div>
			<div class="col-md-6">
			  <form action="controller.php" class="col-12" method="post" onsubmit="return validation()" id="form_manager">
				<input type='hidden' name='jeton' value="<?php  echo"$_SESSION[jeton]"?>">
				<input type='hidden' name='action' value='modifier_manager' id="form_action">
				<input type="hidden" name="Id_manager" id="id">
				<div class="div_inpt">
				  <input type="text" name="Nom_manager"  id="nom" placeholder="Nom">
				</div>
				<label id="label_erreur_nom" for="nom"></label>
				<div class="div_inpt">
				  <input type="text" name="Email_manager"  id="email" placeholder="Email">
				</div>
				<label id="label_erreur_email" for="email"></label>
				<div class="div_inpt">
				  <input type="password" name="Mdps_manager" id="mdps" class="inpt_mdps" placeholder="Password">
				  <i class="fa-solid fa-eye mdps"></i>
				</div>
				<label id="label_erreur_mdps" for="mdps"></label>
				<div class="button">
				  <input type="reset" class="effacer_form_ajout_user" value="Effacer">
				  <input type="submit" class="btn_submit_action_mngr" id="btn_submit" >
				</div>
			  </form>
			</div>
		  </div > 
		</div>    
	  </div>
	</div>
  </div>



    <div class="dash">
        <main class="dash">
            <div class="table_compte table-responsive">
                <table id="mytab_compte"  class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nom manager</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th class="action">Action</th>
                        </tr>
                            

                    </thead>
                    <tbody class="tbody">
                        <?php
                            $stm=$pdo->prepare("SELECT * from manager");
                            $stm->execute();
                            while ($manager_row=$stm->fetch(pdo::FETCH_ASSOC)) {
                                echo "
                                <tr>
                                        <td ><span class='id'>".$manager_row['Id_manager']."</span></td> 
                                        <td class='name'>".htmlspecialchars($manager_row['Nom_manager'])."</td>
                                        <td>".htmlspecialchars($manager_row['Email_manager'])."</td>
                                        <td>".htmlspecialchars($manager_row['Mdps_manager'])."</td>
                                        <td class='action'>
                                            <div class='btn_modif_mngr center' type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                                                <i class='fa-solid fa-pencil'></i>
                                            </div>
                                            <form action='controller.php' method='post'>
                                                <input type='hidden' name='jeton' value='".$_SESSION['jeton']."'>
                                                <input type='hidden' name='action' value='supprimer_manager'>
                                                <input type='hidden' name='Id_manager' value='".htmlspecialchars($manager_row['Id_manager'])."'>
                                                <button type='submit' name='btn_suprm_mngr' class='btn_suprm_mngr' onclick=\"return confirm('vous ete sur');\">
                                                    <i class='fa-solid fa-trash-can'></i>
                                                </button>
                                            </form>
                                            
                                        </td>
                                </tr>                              
                                ";
                            }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </main>
    </div>

        <!-- Main content -->
    
    </div>
</div>

<?php 
include("./inc/bas.inc.html");
?>