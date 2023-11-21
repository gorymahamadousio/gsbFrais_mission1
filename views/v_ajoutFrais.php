<div id="contenu">
      <form action="index.php?uc=ajoutFrais&action=voirAjouterFrais" method="post">
        <div class="corpsForm">
        <h3>Saisie </h3>

        <ul>
        <li>  
            <label for="lstMois">Visiteur:</label>
            <select id="lstMois" name="num">
            <?php   foreach ($visiteurs as $unVisiteur)
                {
                    ?>
                    <option selected value="<?php echo $unVisiteur['id'] ?>"><?php echo  $unVisiteur['nom'].'  '. $unVisiteur['prenom']?> </option>
                    <?php 
                    }
            
            ?>    
        </li></br></br>
        </select>
        <li>
        <label for="date">Periode d'engagement:</label></br>
        mois(2 chiffres):
        <input type="text" name="mois" id="date"></br>
        année(4 chiffres):
        <input type="text" name="annee">
        </li></br>

        <h3>Frais au forfait:</h3>

        <li>
        <label for="rep">Repas midi:</label>
        <input type="text" name="rep" id="rep">
        </li></br>

        <li>
        <label for="nui">Nuitées:</label>
        <input type="text" name="nui" id="nui">
        </li></br>

        <li>
        <label for="etp">Etape:</label>
        <input type="text" name="etp" id="etp">
        </li></br>

        <li>   
        <label for="km">Km:</label>
        <input type="text" name="km" id="km">
        </li></br>

        </ul>
        <button type="submit" name="submit" class="btn btn-primary">Valider</button>
    </form>
</div>