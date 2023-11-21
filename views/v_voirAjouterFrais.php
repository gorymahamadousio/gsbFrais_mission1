<?php include_once 'v_ajoutFrais.php';?>
<h3 class="align-center">Frais insérer dans la base: </h3>
    <div class="encadre">
  	<table class="listeLegere">
    <tr>
                <th class=''> Id:</th>  
                <th class=''> ETP</th>  
                <th class=''> KM </th>   
                <th class=''> NUI </th> 
                <th class=''> REP</th> 
           
             </tr>
        <?php    foreach ( $ajout as $unAjout ): ?>
            <tr>
              
              <td><?=$unAjout['idVisiteur']?></td>
              <td><?=$unAjout['quantite']?></td>
              <td><?=$unAjout['quantite']?></td>
              <td><?=$unAjout['quantite']?></td>
              <td><?=$unAjout['quantite']?></td>
           </tr>
           <?php endforeach?>
          
    </table>
  </div>