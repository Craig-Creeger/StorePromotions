<?php
define ('ENVIRONMENT', 'Dev'); //Dev || Prod
if (ENVIRONMENT==='Dev' && $_SERVER['HTTP_HOST']==='localhost') {
	define ('SERVER', 'Testing');
} else if (ENVIRONMENT==='Dev') {
	define ('SERVER', 'Remote');
} else {
	define ('SERVER', 'Production');
}
//Tweak these lines for your database server
$dbPort = '3306';
if (ENVIRONMENT==='Dev' && SERVER==='Remote') {
	$dbHost = 'mysql:host=localhost';
	$dbDb = 'pixelp12_store';
	$dbUserId = 'pixelp12_promo';
	$dbPassword = 'promo';
} else if (ENVIRONMENT==='Dev') { //Testing Dev server
	$dbHost = 'mysql:host=localhost';
	$dbDb = 'store';
	$dbUserId = 'promo';
	$dbPassword = 'promo';
} else if (ENVIRONMENT==='Prod') {
	$dbHost = 'mysql:host=lamphost0.ahc.umn.edu';
	$dbDb = '';
	$dbUserId = '';
	$dbPassword = '';
}

//Probably ought not touch these.
$dbDSN = "$dbHost;port=$dbPort;dbname=$dbDb";
$salt = 'L1ck'; //md5 salts for password encryption
$pepper = 'gr1nD3r';
$db;

if (ENVIRONMENT==='Dev' && SERVER==='Remote') {
	define('DOMAIN', 'http://' . $_SERVER['HTTP_HOST'] . '/');
} else if (ENVIRONMENT==='Dev') { //Testing Dev server
	define('DOMAIN', 'http://' . $_SERVER['HTTP_HOST'] . '/~Craig/StorePromotions');
} else if (ENVIRONMENT==='Prod') {
	define('DOMAIN', 'http://' . $_SERVER['HTTP_HOST'] . '/');
}
define('APP_ROOT', dirname(dirname(__FILE__)) . '/'); //double-pumping to go up one level since this file is in a sub-directory.
define('INCLUDE_ROOT', APP_ROOT . "includes/");
define('COURSES_PATH', 'courses/'); //hard-coded path of where courses are located

function sanitizeString($sz) {
	//Stripslashes is needed because jQuery AJAX calls will automatically escape the JSON data (with backslashes) that is sent to the server.
	$sz = stripslashes($sz); //unescapes quotes. Ex: Craig\'s car -- will become -- Craig's car.
	$sz = htmlentities($sz, ENT_QUOTES, 'UTF-8'); //converts special characters like &,<,>,', and " to their entity equivalents.
	//this next line probably won't ever do anything because htmlentities have already "hidden" the tags
	$sz = strip_tags($sz); //removes HTML tags so that <script> cannot be injected. It is possible to allow certain tags if you wish...
	return $sz;
}

function debug($msg, $clear=false) {
	//http://alanstorm.com/php_error_reporting
	//If the php.ini file leaves "error_log" blank and "log_errors" is On, the PHP will pass the error to the web server.
	//Enter a value for error_log if you would prefer to direct all PHP related errors to its own log.
	if (ENVIRONMENT === 'Dev') {
		if ($clear) {
			file_put_contents(APP_ROOT . "debugLog.txt", $date = date('Y-m-d H:i:s').'  '.$msg."\n");
		} else {
			file_put_contents(APP_ROOT . 'debugLog.txt', $date = date('Y-m-d H:i:s').'  '.$msg."\n", FILE_APPEND);
			//Sometimes the debug statements don't work. Uncomment the following line in
			//  those instances and then look for the debugLog2 file in all directories. 
			//file_put_contents('debugLog2.txt', $date = date('Y-m-d H:i:s').'  '.$msg."\n", FILE_APPEND);
		}
	} else {
		trigger_error('Store Promotions Application: ' . $msg, E_USER_ERROR);
	}
}

function destroySessionAndData() {
	session_start();
	$_SESSION = array();
	if (session_id() != "" || isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time() - 2592000, '/');
	}
	session_destroy();
}

function makeDbConnection() {
	global $dbDSN;
	global $dbUserId;
	global $dbPassword;
	global $db;
	
	try {
		$db = new PDO($dbDSN, $dbUserId, $dbPassword, array(
    		PDO::ATTR_PERSISTENT => true,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
		));
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Production and dev (errors are caught in PDOException when set this way)
		$db->exec("SET CHARACTER SET utf8");
	} catch(PDOException $e) {
		debug('common.php / makeDbConnection() / ' . $e->getMessage());
	}
}
?>