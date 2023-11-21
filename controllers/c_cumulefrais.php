<?php
/** @var PdoGsb $pdo */
include 'views/v_sommaire.php';
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch($action){
	case 'selectionnerMois':{
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste,
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		include("views/v_listeMois.php");
		break;
	}
	case 'voirEtatFrais':{
		$leMois = $_REQUEST['lstMois'];
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		$moisASelectionner = $leMois;
		include("views/v_listeMois.php");
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$libEtat = $lesInfosFicheFrais['libEtat'];
		$montantValide = $lesInfosFicheFrais['montantValide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
		$dateModif =  $lesInfosFicheFrais['dateModif'];
		
		//Gestion des dates
		@list($annee,$mois,$jour) = explode('-',$dateModif);
		$dateModif = "$jour"."/".$mois."/".$annee;

		//$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("views/v_etatFrais.php");
	}
	
    case 'cumulefrais':{
		$typeFrais=$pdo->getTypeDeFrais();
		//$leMois = $_REQUEST['lstMois'];
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		//$moisASelectionner = $leMois;
		include("views/v_cumulefrais.php");
		break;
	}

	case 'voirCumuleFrais':{
		$typeFrais=$pdo->getTypeDeFrais();
		$leMois = $_REQUEST['lstMois'];
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		//$moisASelectionner = $leMois;
		include("views/v_cumulefrais.php");
		$idFraisForfait=$_REQUEST['tfrais'];
		$mois = $_REQUEST['lstMois'];
		//$idFraisForfait=$pdo->getTypeDeFrais();
		$montant=$pdo->getCumuleEtatFrais($idVisiteur,$mois,$idFraisForfait);
		//$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("views/v_voirCumuleFrais.php");
		break;
	}

	case 'fraisVisiteur':{
		$visiteurs=$pdo->getIdVisiteur();
		$typeFrais=$pdo->getTypeDeFrais();
		//$moisASelectionner = $leMois;
		include("views/v_fraisVisiteur.php");
		break;
	}

	case 'voirFraisVisiteur':{
		$visiteurs=$pdo->getIdVisiteur();
		$leVisiteur=$_REQUEST['num'];
		$typeFrais=$pdo->getTypeDeFrais();
		include("views/v_fraisVisiteur.php");
		$idFraisForfait=$_REQUEST['tfrais'];
		//$mois = $_REQUEST['lstMois'];
		$montant=$pdo->getFraisVisiteur($leVisiteur,$idFraisForfait);
		//$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("views/v_voirFraisVisiteur.php");
		break;
	}

	case 'fraisMois':{
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);;
		//$moisASelectionner = $leMois;
		include("views/v_fraisMois.php");
		break;
	}

	case 'voirFraisMois':{
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);;
		//$moisASelectionner = $leMois;
		include("views/v_fraisMois.php");
		$mois = $_REQUEST['lstMois'];
		$montant=$pdo->getFraisMois($idVisiteur,$mois);
		//$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("views/v_voirFraisMois.php");
		break;
	}

	case 'fraisVisiteurPoste':{
		$visiteurs=$pdo->getIdVisiteur();
		include("views/v_fraisVisiteurPoste.php");
		break;
	}

	case 'voirFraisVisiteurPoste':{
		$visiteurs=$pdo->getIdVisiteur();
		$leVisiteurs=$_REQUEST['num'];
		include("views/v_fraisVisiteurPoste.php");
		$montant=$pdo->getFraisVisiteurPoste($leVisiteurs);
		//$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("views/v_voirFraisVisiteurPoste.php");
		break;
	}

	case 'ajoutFrais':{
		$visiteurs=$pdo->getIdVisiteur();
		include("views/v_ajoutFrais.php");
		break;
	}

	case 'voirAjouterFrais':{
		//récupération des valeur depuis le formulaire
		$visiteurs=$pdo->getIdVisiteur();
		//si le boutton submit à était actionné par la methode post alors on récupére les données
		if(isset($_POST['submit'])){
		$idVisiteur=$_REQUEST['num'];
		$mois=$_REQUEST['mois'];
		$annee=$_REQUEST['annee'];
		$date=$annee.$mois;
		$repas=$_REQUEST['rep'];
		$nuit=$_REQUEST['nui'];
		$etape=$_REQUEST['etp'];
		$km=$_REQUEST['km'];
		$ajtff=$pdo->getAjouterFicheFrais($idVisiteur,$date);
		$ajouterREP=$pdo->getAjouterFrais1($idVisiteur,$date,'REP',$repas);
		$ajouterNUI=$pdo->getAjouterFrais2($idVisiteur,$date,'NUI',$nuit);
		$ajouterETP=$pdo->getAjouterFrais3($idVisiteur,$date,'ETP',$etape);
		$ajouterKM=$pdo->getAjouterFrais4($idVisiteur,$date,'KM',$km);

		//ajout des valeur en utilisant une seul et même requête avec ds if
		/*if(isset($repas)){
			$quantiteREP=$repas;
			$ajout=$pdo->getAjouterFrais1($idVisiteur,$date,'REP',$quantiteREP);
		}
		if(isset($nuit)){
			$quantiteNUI=$nuit;
			$ajout=$pdo->getAjouterFrais1($idVisiteur,$date,'NUI',$quantiteNUI);
		}
		if(isset($etape)){
			$quantiteETP=$etape;
			$ajout=$pdo->getAjouterFrais1($idVisiteur,$date,'ETP',$quantiteETP);
		}
		if(isset($km)){
			$quantiteKM=$km;
			$ajout=$pdo->getAjouterFrais1($idVisiteur,$date,'KM',$quantiteKM);
		}*/
		
		include("views/v_voirAjouterFrais.php");
		break;
		}
		
	}





}
