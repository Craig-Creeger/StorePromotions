<?php
/* This "page" is called via AJAX with HTTP GET method, and returns a JSON string.
*/
require_once '../includes/common.php';
require_once INCLUDE_ROOT . 'classes/Promotion.php';

makeDbConnection();

$gaa = array();
$ga = Promotion::getAll();
foreach ($ga as $p) {
	$promo = new stdClass();
	$promo->promoId = $p->promoId;
	$promo->promoDesc = $p->promoDesc;
	$gaa[] = $promo;
}

header('Content-Type: application/json');
echo json_encode($gaa);
?>
