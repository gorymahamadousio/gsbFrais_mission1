<?php include_once 'v_cumulefrais.php';?>
<h3 class="align-center">Fiche du cumule des frais du mois : </h3>
    <div class="encadre">
  	<table class="listeLegere">
             <tr>
                <th class='nom'> Visiteur: </th>   
                <th class='prenom'> Type de frais:</th>   
                <th class='montant'>Montant cumulé: </th> 
                <th class='mois'> Mois: </th>

           
             </tr>
        <?php    foreach ( $montant as $unMontant ): ?>
            <tr>
              
              <td><?=$unMontant['idVisiteur']?></td>
              <td><?=$unMontant['idFraisForfait']?></td>
              <td><?=$unMontant['somme']?></td>
              <td><?=$unMontant['mois']?></td>
           </tr>
           <?php endforeach?>
          
    </table>
  </div>
