<!-- Division pour le sommaire -->
<nav class="menuLeft">
    <ul class="menu-ul">
        <li class="menu-item"><a href="index.php">retour</a></li>

        <li class="menu-item">
            Visiteur :<br>
            <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes
                fiches de frais</a>
        </li>
        <li class="menu-item">
            <a href="index.php?uc=cumulefrais&action=cumulefrais" title="Consultation du cumule des frais">
            mission1a </a>
        </li>
        <li class="menu-item">
            <a href="index.php?uc=fraisVisiteur&action=fraisVisiteur" title="Consultation du cumule des frais">
                 mission1b</a>
        </li>
        <li class="menu-item">
            <a href="index.php?uc=fraisMois&action=fraisMois" title="Consultation du cumule des frais">
                 mission1c</a>
        </li>
        <li class="menu-item">
            <a href="index.php?uc=fraisVisiteurPoste&action=fraisVisiteurPoste" title="Consultation du cumule des frais">
                 mission1d</a>
        </li>
        <li class="menu-item">
            <a href="index.php?uc=ajoutFrais&action=ajoutFrais" title="Consultation du cumule des frais">
                 mission1e</a>
        </li>
        <li class="menu-item">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>
</nav>
<section class="content">


