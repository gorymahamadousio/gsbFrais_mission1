<?php
/**
 * Classe d'accès aux données.

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsbfrais';
      	private static $user='root' ;
      	private static $mdp='' ;
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     * @return null L'unique objet de la classe PdoGsb
     */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;
	}

    /**
     * Retourne les informations d'un visiteur
     * @param $login
     * @param $mdp
     * @return mixed L'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp){
        $req = "select id, nom, prenom from visiteur where login='$login' and mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
     
    * @param $madate au format  jj/mm/aaaa
    * @return la date au format anglais aaaa-mm-jj
    */
    public function dateAnglaisVersFrancais($maDate){
        @list($annee,$mois,$jour)=explode('-',$maDate);
        $date="$jour"."/".$mois."/".$annee;
        return $date;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments
     * La boucle foreach ne peut être utilisée ici, car on procède
     * à une modification de la structure itérée - transformation du champ date-
     * @param $idVisiteur
     * @param $mois 'sous la forme aaaamm
     * @return array 'Tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur,$mois){
        $req = "select * from lignefraishorsforfait where idvisiteur ='$idVisiteur' 
		and mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i=0; $i<$nbLignes; $i++){
            $date = $lesLignes[$i]['date'];
            //Gestion des dates
            @list($annee,$mois,$jour) = explode('-',$date);
            $dateStr = "$jour"."/".$mois."/".$annee;
            $lesLignes[$i]['date'] = $dateStr;
        }
        return $lesLignes;
    }


    /**
     * Retourne les mois pour lesquels, un visiteur a une fiche de frais
     * @param $idVisiteur
     * @return array 'Un tableau associatif de clé un mois - aaaamm - et de valeurs l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur){
        $req = "select mois from  fichefrais where idvisiteur ='$idVisiteur' order by mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois =array();
        $laLigne = $res->fetch();
        while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $lesMois["$mois"]=array(
                "mois"=>"$mois",
                "numAnnee"  => "$numAnnee",
                "numMois"  => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donn�
     * @param $idVisiteur
     * @param $mois 'sous la forme aaaamm
     * @return mixed 'Un tableau avec des champs de jointure entre une fiche de frais et la ligne d'�tat
     */
    public function getLesInfosFicheFrais($idVisiteur,$mois){
        $req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idVisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();
        return $laLigne;
    }


    //mission 1a
    public function getCumuleEtatFrais($idVisiteur, $mois, $idFraisForfait) {
        $req = "SELECT li.idVisiteur, li.idFraisForfait, li.mois, v.nom, v.prenom, li.quantite * ff.montant as somme 
                FROM visiteur v 
                INNER JOIN fichefrais fi ON fi.idVisiteur = v.id 
                INNER JOIN lignefraisforfait li ON li.idVisiteur = v.id AND li.mois = fi.mois 
                INNER JOIN fraisforfait ff ON ff.id = li.idFraisForfait 
                WHERE v.id = ? AND fi.mois = ? AND ff.id = ?";
            
        $res = PdoGsb::$monPdo->prepare($req);
        $res->execute([$idVisiteur, $mois, $idFraisForfait]);
        $resultats = $res->fetchAll(PDO::FETCH_ASSOC);
        return $resultats;
    }
    


    //mission 1b
    public function getFraisVisiteur($idVisiteur,$idFraisForfait){
        $req = "SELECT li.idVisiteur,li.mois,v.nom, v.prenom, li.quantite * ff.montant as somme 
        from visiteur v 
        INNER JOIN fichefrais fi on fi.idVisiteur = v.id 
        INNER JOIN lignefraisforfait li on li.idVisiteur = v.id and li.mois = fi.mois 
        INNER JOIN fraisforfait ff on ff.id = li.idFraisForfait 
        WHERE v.id = '$idVisiteur' and ff.id='$idFraisForfait';";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();

        return $laLigne;
    }

      //mission 1c  

   public function getFraisMois($idVisiteur,$mois){
        $req = "SELECT idVisiteur, 
        sum((CASE WHEN li.idFraisForfait = 'ETP' then (li.quantite * ff.montant) END)) as 'ETP', 
        sum((CASE WHEN li.idFraisForfait = 'KM' then (li.quantite * ff.montant) END)) as 'KM', 
        sum((CASE WHEN li.idFraisForfait = 'NUI' then (li.quantite * ff.montant) END)) as 'NUI', 
        sum((CASE WHEN li.idFraisForfait = 'REP' then (li.quantite * ff.montant) END)) as 'REP' 
        from lignefraisforfait li inner join fraisforfait ff on li.idFraisForfait=ff.id 
        where li.mois='$mois' 
        GROUP BY idVisiteur";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();

        return $laLigne;
    }

    //mission 1d
    public function getFraisVisiteurPoste($idVisiteur){
        $req = "SELECT mois,
        sum((CASE WHEN li.idFraisForfait = 'ETP' then (li.quantite * ff.montant) END)) as 'ETP', 
        sum((CASE WHEN li.idFraisForfait = 'KM' then (li.quantite * ff.montant) END)) as 'KM', 
        sum((CASE WHEN li.idFraisForfait = 'NUI' then (li.quantite * ff.montant) END)) as 'NUI', 
        sum((CASE WHEN li.idFraisForfait = 'REP' then (li.quantite * ff.montant) END)) as 'REP' 
        from lignefraisforfait li 
        inner join fraisforfait ff on li.idFraisForfait=ff.id 
        where li.idVisiteur='$idVisiteur' 
        GROUP BY mois";
        echo 'la requête'.$req;      
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();

        return $laLigne;
    }


        //mission 1e
       public function getAjouterFrais1($idVisiteur,$mois,$idFraisForfait,$quantiteREP){
            try{
            $sql = "INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) 
            VALUES (:idVisiteur, :mois, :idFraisForfait, :quantite) 
            ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)";
            $stmt=PdoGsb::$monPdo->prepare($sql);

            $stmt->bindParam(':idVisiteur',$idVisiteur);
            $stmt->bindParam(':mois',$mois);
            $stmt->bindParam(':idFraisForfait',$idFraisForfait);
            $stmt->bindParam(':quantite',$quantiteREP);
            $laLigne = $stmt->execute();
            echo 'la requête'.$sql; 
                    
                    // Si l'insertion a réussi
                if ($laLigne) {
                    return PdoGsb::$monPdo->execute();
                } else {
                    // Sinon, renvoyez un indicateur d'échec 
                    return false;
                    echo'La connexion a échouer allez recommence!!!';
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
                // En cas d'erreur, renvoyez un indicateur d'échec personnalisé
                return false;
            }
    }


    public function getAjouterFrais2($idVisiteur,$mois,$idFraisForfait,$quantiteNUI){
        try{
        $sql = "INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) 
        VALUES (:idVisiteur, :mois, :idFraisForfait, :quantite) 
        ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)";
        $stmt=PdoGsb::$monPdo->prepare($sql);

        $stmt->bindParam(':idVisiteur',$idVisiteur);
        $stmt->bindParam(':mois',$mois);
        $stmt->bindParam(':idFraisForfait',$idFraisForfait);
        $stmt->bindParam(':quantite',$quantiteNUI);
        $laLigne = $stmt->execute();
        echo 'la requête'.$sql; 
                
                // Si l'insertion a réussi
            if ($laLigne) {
                return PdoGsb::$monPdo->execute();
            } else {
                // Sinon, renvoyez un indicateur d'échec 
                return false;
                echo'La connexion a échouer allez recommence!!!';
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            // En cas d'erreur, renvoyez un indicateur d'échec personnalisé
            return false;
        }
}

public function getAjouterFrais3($idVisiteur,$mois,$idFraisForfait,$quantiteETP){
    try{
    $sql = "INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) 
    VALUES (:idVisiteur, :mois, :idFraisForfait, :quantite) 
    ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)";
    $stmt=PdoGsb::$monPdo->prepare($sql);

    $stmt->bindParam(':idVisiteur',$idVisiteur);
    $stmt->bindParam(':mois',$mois);
    $stmt->bindParam(':idFraisForfait',$idFraisForfait);
    $stmt->bindParam(':quantite',$quantiteETP);
    $laLigne = $stmt->execute();
    echo 'la requête'.$sql; 
            
            // Si l'insertion a réussi
        if ($laLigne) {
            return PdoGsb::$monPdo->execute();
        } else {
            // Sinon, renvoyez un indicateur d'échec 
            return false;
            echo'La connexion a échouer allez recommence!!!';
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        // En cas d'erreur, renvoyez un indicateur d'échec personnalisé
        return false;
    }
}

public function getAjouterFrais4($idVisiteur,$mois,$idFraisForfait,$quantiteKM){
    try{
    $sql = "INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) 
    VALUES (:idVisiteur, :mois, :idFraisForfait, :quantite) 
    ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)";
    $stmt=PdoGsb::$monPdo->prepare($sql);

    $stmt->bindParam(':idVisiteur',$idVisiteur);
    $stmt->bindParam(':mois',$mois);
    $stmt->bindParam(':idFraisForfait',$idFraisForfait);
    $stmt->bindParam(':quantite',$quantiteKM);
    $laLigne = $stmt->execute();
    echo 'la requête'.$sql; 
            
            // Si l'insertion a réussi
        if ($laLigne) {
            return PdoGsb::$monPdo->execute();
        } else {
            // Sinon, renvoyez un indicateur d'échec 
            return false;
            echo'La connexion a échouer allez recommence!!!';
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        // En cas d'erreur, renvoyez un indicateur d'échec personnalisé
        return false;
    }
}


    public function getAjouterFicheFrais($idVisiteur, $mois) {
        $req = "INSERT INTO fichefrais (idVisiteur, mois) VALUES (:idVisiteur, :mois) 
                ON DUPLICATE KEY UPDATE idVisiteur = VALUES(idVisiteur), mois = VALUES(mois)";
        $stmt = PdoGsb::$monPdo->prepare($req);
        
        //on vérifie que la préparation de la reqête n'a pas échouer (même pricipe que le try catch)
        if($stmt){
            $stmt->bindValue(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
            $stmt->bindValue(':mois', $mois, PDO::PARAM_INT);
        
            //echo 'Requête : ' . $req; // Cela affiche la requête SQL 
            $res = $stmt->execute();
            if($res){
                return true;
            }
        }else {
            // Gestion de l'erreur de préparation de la requête
            print_r(PdoGsb::$monPdo->errorInfo());
            return false;
        }
    }
    


    public function getTypeDeFrais(){
        $req = "SELECT id from fraisforfait";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();
        return $laLigne;
    }

    public function getIdVisiteur(){
        $req = "SELECT id,nom,prenom from visiteur";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchAll();
        return $laLigne;
    }




}

