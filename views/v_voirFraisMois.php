<?php include_once 'v_fraisMois.php';?>
<h3 class="align-center">Fiche du cumule des frais du mois : </h3>
<link rel="stylesheet" href="../css/style2.css">
    <div class="encadre">
  	<table class="listeLegere">
             <tr>
                <th class=''> Id:</th>  
                <th class=''> ETP</th>  
                <th class=''> KM </th>   
                <th class=''> NUI </th> 
                <th class=''> REP</th> 
           
             </tr>
        <?php    foreach ( $montant as $unMontant ): ?>
            <tr>
              
              <td><?=$unMontant['idVisiteur']?></td>
              <td><?=$unMontant['ETP']?></td>
              <td><?=$unMontant['KM']?></td>
              <td><?=$unMontant['NUI']?></td>
              <td><?=$unMontant['REP']?></td>
           </tr>
           <?php endforeach?>
          
    </table>
  </div>