<?php

// ***********************************
// Initialisation de l'environnement *
// ***********************************
require('./../config/config.ini.php');

// ******************************
// Initialisation de la session *
// ******************************
session_start();
header("Cache-control: no-cache");

// ***************************************************
// Validation des parametres de routing des requetes *
// ***************************************************

// Chargement des actions valides (presentes dans controllers/actions)
$validActions = ['nullAction'];
$scandir = scandir(_ACTS_);
foreach ($scandir as $file) {
	if (is_file(_ACTS_.$file)) {
		// recupérer la chaine de caractères jusqu'au '.php'
		$filename = explode('.php', $file,2);
		array_push($validActions,$filename[0]);
	}
}

// Chargement des actions valides (presentes dans controllers/pages)
$validPages = ['nullPage'];
$scandir = scandir(_PAGE_);
foreach ($scandir as $file) {
    if (is_file(_PAGE_.$file)) {
        // recupérer la chaine de caractères jusqu'au '.php'
        $filename = explode('.php', $file,2);
        array_push($validPages,$filename[0]);
    }
}

// Validation des parametres de la requete via Flourish
$action = fRequest::getValid('action',$validActions);
$page = fRequest::getValid('page',$validPages);

// ********************
// Securite des accès *
// ********************

// Si $page !== login alors il faut être loggué
// Si $page == login, on verifie $action
if (strcmp($page,'login') !== 0) {
	// Ce n'est pas une page de login
	// L'utilisateur doit être authentifié
	fAuthorization::requireLoggedIn();
} else {
	// La page requetée est login
	if ((strcmp($action,'login') !== 0) && 
		(strcmp($action,'logout') !== 0) &&
		(strcmp($action,'nullAction') !== 0)) { 
		// L'action est differente de login, logout ou nullAction
		// Ce n'est pas très catholique
		die("l'action n'est pas autorisée sur la page de login");
	} else {
		// L'action est autorisée sur la page de login
		// On laisse l'utilisateur tenter de se connecter
		// sur la page de login via un accès anonyme
	}
}

// **********************
// Routing des requetes *
// **********************

// On inclue les controllers demandés :
if (strcmp($action,'nullAction') !== 0) {
	// Une action a été requise
	include(_ACTS_.$action.'.php');
}

if (strcmp($page,'nullPage') !== 0) {
    // Une page a été requise
    include(_PAGE_.$page.'.php');
} else {
	// Aucune page n'a été requise
	echo '<br /><B>Aucune page n\'a été requise !</B><br /><br />';
	// Nous n'appelons aucun controlleur
	// Mais nous positionnons $page a 'missing' pour afficher
	$// le template Smarty par defaut
	$page='missing';
}

// ****************
// Appel des vues *
// ****************

// display header
$smarty->display(_TPL_ . 'header.tpl');

// display the page
$smarty->display(_TPL_ . 'pages/' . $page . '.tpl');

// display footer
$smarty->display(_TPL_ . 'footer.tpl');

// Pour Debug
/*
if (_DEBUG_) {
*/
	echo '<B>Routing des requetes</B><br />'; 
	echo 'L\'action est : ' . $action . '<br />';
	echo 'Les actions valides sont :'.print_r($validActions).'<br />';
	echo 'La page est : '. $page . '<br />';
	echo 'Les pages valides sont :'.print_r($validPages).'<br />';
	echo '<br />';
/*
}
*/

?>
