<?php

define( '__SITE_PATH', realpath( dirname( __FILE__ ) ) );
define( '__SITE_URL', dirname( $_SERVER['PHP_SELF'] ) );

if(session_id() === "")
	session_start();

// Provjeri je li postavljena varijabla rt; kopiraj ju u $route
if( isset( $_GET['rt'] ) && isset($_SESSION['username']))
	$route = $_GET['rt'];
elseif(isset($_GET['rt']) && $_GET['rt'] === 'login/finishRegistration')
	$route = 'login/finishRegistration';
elseif( isset($_SESSION['username']))
	$route = 'tables';
else
	$route = 'login';

// Ako je $route == 'con/act', onda rastavi na $controllerName='con', $action='act'
$parts = explode( '/', $route );

$controllerName = $parts[0] . 'Controller';
if( isset( $parts[1] ) )
	$action = $parts[1];
else
	$action = 'index';

// Controller $controllerName se nalazi poddirektoriju controller
$controllerFileName = 'controller/' . $controllerName . '.php';

// // Includeaj tu datoteku
// if( !file_exists( $controllerFileName ) )
// {
// 	$controllerName = '_404Controller';
// 	$controllerFileName = 'controller/' . $controllerName . '.php';
// }

require_once $controllerFileName;

// Stvori pripadni kontroler
$con = new $controllerName; 

// Ako u njemu nema tražene akcije, stavi da se traži akcija index
if( !method_exists( $con, $action ) )
	$action = 'index';

// Pozovi odgovarajuću akciju
$con->$action();

?>
