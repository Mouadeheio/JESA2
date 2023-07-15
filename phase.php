<?php
include("./inc/sidebar.php");
?>
<div class="dash">
    <main class="dash">
        <div class="col-12 mx-auto container_discipline">
            <ul class="nav nav-tabs d-none d-md-flex col-md-2 col-sm-0" id="tab_discipline" role="tablist">
                <?php
                    $rslt2 = $pdo->query("SELECT * FROM phase ORDER BY numero_phase");
                    $row2 = $rslt2->fetchAll();
                    foreach ($row2 as $row2) {
                        echo "<li class='nav-item' role='presentation'>
                                <button class='nav-link btn_phase' id='phase$row2[numero_phase]-tab' data-bs-toggle='tab' data-bs-target='#phase$row2[numero_phase]-tab-pane' type='button' role='tab' aria-controls='phase1-tab-pane' aria-selected='false'>Phase $row2[numero_phase]</button>
                              </li>";
                    }
                ?>
            </ul>
                    
            <div class="tab-content col-md-10 col-sm-12" id="tab_disciplineContent">

                <?php
                    $rslt2 = $pdo->query("SELECT * FROM phase ORDER BY numero_phase");
                    $row2 = $rslt2->fetchAll();

                    foreach ($row2 as $row2) {
                        echo "<div class='tab-pane fade' id='phase$row2[numero_phase]-tab-pane' role='tabpanel' aria-labelledby='phase$row2[numero_phase]-tab' tabindex='0'>
                                <div class='div_ajouter_discipline'><h2>$row2[Titre_phase]</h2> <button class='btn_ajouter_discipline'>Ajouter discipline</button></div>
                                <div class='tablee'>
                                    <table class='table' id='table_phase'>
                                        <thead>
                                            <tr>
                                                <th class='col-4' scope='col'>Discipline</th>
                                                <th class='col-5'  scope='col'>Numéro de discipline</th>
                                                <th class='col-3'  scope='col'>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class='tbody_table_phase'>";
                                        $rslt3 = $pdo->query("SELECT * FROM discipline WHERE Id_phase=$row2[Id_phase]");
                                        $row3 = $rslt3->fetchAll();
                                        foreach ($row3 as $row3) {
                                            echo "<tr>
                                                    <td>$row3[Titre_discipline]</td>
                                                    <td>$row3[numero_discipline]</td>
                                                    <td class='action'>
                                                            <button class='btn_modif_discipline center'>
                                                                <i class='fa-solid fa-pencil'></i>
                                                            </button>
                                                        </form>
                                                        <form action='controller.php' method='post'>
                                                            <input type='hidden' name='jeton' value='".$_SESSION['jeton']."'>
                                                            <input type='hidden' name='action' value='supprimer_discipline'>
                                                            <input type='hidden' name='Id_phase' value='" . htmlspecialchars($row3['Id_phase']) . "'>
                                                            <input type='hidden' name='numero_discipline' value='" . htmlspecialchars($row3['numero_discipline']) . "'>
                                                            <input type='hidden' name='Id_discipline' value='" . htmlspecialchars($row3['Id_discipline']) . "'>
                                                            <button type='submit' class='btn_suprm_discipline center' onclick=\"return confirm('Etes-vous sûr de vouloir supprimer cette discipline ?');\">
                                                                <i class='fa-solid fa-trash-can'></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                  </tr>";
                                        }                                    
                        echo "</tbody></table></div></div>";
                    }
                ?>

            </div>

        </div>
    </main>
</div>

<?php 
include("./inc/bas.inc.html");
?>