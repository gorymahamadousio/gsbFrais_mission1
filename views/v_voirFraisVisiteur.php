<h3 class="align-center">Cumul pour tous les mois des frais  : </h3>
    <div class="encadre">
  	<table class="listeLegere">
             <tr>
             <th class='mois'> Id: </th>
                <th class='mois'> Mois: </th>    
                <th class='montant'>Montant cumulé: </th> 
           
             </tr>
        <?php    foreach ( $montant as $unMontant ): ?>
            <tr>
            <td><?=$unMontant['idVisiteur']?></td>
              <td><?=$unMontant['mois']?></td>
              <td><?=$unMontant['somme']?>€</td>
           </tr>
           <?php endforeach?>
          
    </table>
  </div>
