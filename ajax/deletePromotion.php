<?php
/* This "page" is called via AJAX with HTTP PUT method, and returns a JSON string.
*/
require_once '../includes/common.php';
require_once INCLUDE_ROOT . 'classes/Promotion.php';

makeDbConnection();

$promoId = (array_key_exists('promoId', $_POST)) ? trim(sanitizeString($_POST['promoId'])) : 'false';
if (is_numeric($promoId)) {
	$promoId = (integer) $promoId;
}

$goodDelete = false;
if ($promoId) {
	$goodDelete = Promotion::delete($promoId);
}

header('Content-Type: application/json');
echo json_encode($goodDelete);
?>
