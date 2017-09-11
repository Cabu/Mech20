<?php

// Ce fichier recueille les parametres locaux d'environnement
// de l'application (systeme de fichier,sgbd,chargement librairies)

// *******************************
// Initialisation des constantes *
// *******************************

// Debug (Boolean : TRUE / FALSE)
define('_DEBUG_',FALSE);

// Si on a pas ces infos, rien ne peut fonctionner : die
if (!isset($_SERVER['CONTEXT_DOCUMENT_ROOT'])) die("impossible de determiner SERVER['CONTEXT_DOCUMENT_ROOT']");

// Define de la racine du site
define('_PATH_', $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/../');

// Define du dossier Coeur
define('_CORE_', _PATH_ . 'core/');

// Define du dossier des Controleurs
define('_CTRL_', _PATH_ . 'controllers/');

// Define du dossier des Actions (Controllers)
define('_ACTS_', _CTRL_ . 'actions/');

// Define du dossier des Pages (Controllers)
define('_PAGE_', _CTRL_ . 'pages/');

// Define du dossier des Configs
define('_CONF_', _PATH_ . 'config/');

// Define du dossier des TPL
define('_TPL_', _PATH_ . 'tpl/');

// Define du dossier des logs
define('_LOGS_', _PATH_ . 'logs/');

// Define du dossier des TOOLS
define('_TOOLS_', _PATH_ . 'tools/');

// Pour emploi dans Smarty ( e.g {$smarty.const._IMG_} )

// Define du dossier des IMG
define('_IMG_', 'web/img/');

// Pour Debug
if (_DEBUG_) {
echo '<B>DEBUG : Dump des constantes <br /></B>';
echo 'Const _PATH_ : ' . _PATH_ . '<br />';
echo 'Const _CORE_ : ' . _CORE_ . '<br />';
echo 'Const _CTRL_ : ' . _CTRL_ . '<br />';
echo 'Const _ACTS_ : ' . _ACTS_ . '<br />';
echo 'Const _PAGE_ : ' . _PAGE_ . '<br />';
echo 'Const _CONF_ : ' . _CONF_ . '<br />';
echo 'Const _TPL_  : ' . _TPL_ . '<br />';
echo 'Const _LOGS_ : ' . _LOGS_ . '<br />';
echo 'Const _TOOLS_ : ' . _TOOLS_ . '<br />';
echo 'Const _IMG_ : ' . _IMG_ . '<br />';
echo '<br />';
}

// *****************************
// Autoload (Tools,Core, etc.) *
// *****************************

// Remove Warning : flourish a des Warning
error_reporting(E_ALL ^ E_WARNING);

function myAutoload($class) {
	// Flourish
	if (file_exists(_TOOLS_ . 'flourish/' . $class . '.php')) {
		require_once _TOOLS_ . 'flourish/' . $class . '.php';
	}
	// Smarty
    elseif (file_exists(_TOOLS_ . 'smarty/' . $class . '.class.php')) {
        require_once _TOOLS_ . 'smarty/' . $class . '.class.php';
    }
	// Core
    elseif (file_exists(_CORE_ . $class . '.class.php')) {
        require_once _CORE_ . $class . '.class.php';
    }
}

spl_autoload_register('myAutoload');


// **************************
// Smarty (Template Engine) *
// **************************

$smarty = new Smarty();

$smarty->SetTemplateDir(_TPL_ . 'pages');
$smarty->SetCompileDir(_TPL_ . 'tpl_c');
$smarty->SetCacheDir(_TPL_ . 'cache');
$smarty->SetConfigDir(_CONF_);

// Pour Debug
if (_DEBUG_) {
echo '<B>DEBUG : Dump des chemins de Smarty</B><br />';
echo 'Smarty templates directory : ' . _TPL_ . 'pages<br />';
echo 'Smarty compiled templates directory : ' . _TPL_ . 'tpl_c<br />';
echo 'Smarty cache directory : ' . _TPL_ . 'cache<br />';
echo 'Smarty config directory : ' . _CONF_ . '<br />';
echo '<br />';
$smarty->testInstall();
echo '<br />';
}

// ***********************************************
// Connexion a la base de donnée (avec Flourish) *
// ***********************************************

try {
	// Definition des parametres de connexion (a externaliser)
	//$db = new fDatabase('postgresql','battletech','battletech','battletech','127.0.0.1');
	$db = new fDatabase('mysql','id440183_battletech','id440183_battletech','slew91rt','localhost');
	// Calling this method is not required but usefull
	// to manage Database connection errors
	$db->connect();
} catch (Exception $e) {
	echo "Probleme de connexion a la base de donnee Battletech...";
	die();
}

// Attacher la base de donnée a notre ORM Flourish
fORMDatabase::attach($db);

// *******************************
// Initialisation de la sécurité *
// *******************************

if (!isset($_SERVER['CONTEXT_PREFIX'])) die("impossible de determiner SERVER['CONTEXT_PREFIX']");

// Setup login page (en dur)
fAuthorization::setLoginPage($_SERVER['CONTEXT_PREFIX'] . '/?page=login');

// Set authorization levels
fAuthorization::setAuthLevels(
    array(
        'admin' => 8,
        'gm' => 6,
        'player' => 4,
        'guest' => 2
    )
)

?>
