<div id="contenu">
      <form action="index.php?uc=fraisVisiteur&action=voirFraisVisiteur" method="post">
      <div class="corpsForm">
      <h2>Visiteur</h2>
      <p>
        <label for="num" accesskey="n">Numéro: </label>
        <select id="num" name="num">
            <?php
			foreach ($visiteurs as $unVisiteur)
			{
				?>
				<option selected value="<?php echo $unVisiteur['id'] ?>"><?php echo  $unVisiteur['id'] ?> </option>
				<?php 
				}
           
		   ?>    
        </select>

        <label for="tfrais" accesskey="n">Type de frais: </label>
        <select id="tfrais" name="tfrais">
            <?php
			foreach ($typeFrais as $unfrai)
			{?>
				<option value="<?php echo $unfrai['id'] ?>"><?= $unfrai['id'] ?></option>
      <?php
			}
		  ?>    
        </select>
      </p>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>
  