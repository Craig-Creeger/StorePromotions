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

$promoDesc = (array_key_exists('promoDesc', $_POST)) ? trim(sanitizeString($_POST['promoDesc'])) : 'false';

$promoType = (array_key_exists('promoType', $_POST)) ? trim(sanitizeString($_POST['promoType'])) : 1;
if (is_numeric($promoType)) {
	$promoType = (integer) $promoType;
} else {
	$promoType = 1;
}

$conditionUnits = (array_key_exists('conditionUnits', $_POST)) ? trim(sanitizeString($_POST['conditionUnits'])) : 3;
if (is_numeric($conditionUnits)) {
	$conditionUnits = (integer) $conditionUnits;
} else {
	$conditionUnits = 0;
}

$conditionCurrency = (array_key_exists('conditionCurrency', $_POST)) ? trim(sanitizeString($_POST['conditionCurrency'])) : 100.0;
if (is_numeric(trim($conditionCurrency))) {
	$conditionCurrency = (float) $conditionCurrency;
} else {
	$conditionCurrency = 0.0;
}

$conditionProductId = (array_key_exists('conditionProductId', $_POST)) ? trim(sanitizeString($_POST['conditionProductId'])) : 1;
if (is_numeric($conditionProductId)) {
	$conditionProductId = (integer) $conditionProductId;
} else {
	$conditionProductId = 1;
}

$discountPercent = (array_key_exists('discountPercent', $_POST)) ? trim(sanitizeString($_POST['discountPercent'])) : 0.2;
if (is_numeric($discountPercent)) {
	$discountPercent = (float) $discountPercent;
} else {
	$discountPercent = 0.0;
}

$discountCurrency = (array_key_exists('discountCurrency', $_POST)) ? trim(sanitizeString($_POST['discountCurrency'])) : 15.0;
if (is_numeric($discountCurrency)) {
	$discountCurrency = (float) $discountCurrency;
} else {
	$discountCurrency = 0.0;
}

$p = new Promotion($promoId);
$p->promoDesc = $promoDesc;
$p->promoType = $promoType;
$p->conditionUnits = $conditionUnits;
$p->conditionCurrency = $conditionCurrency;
$p->conditionProductId = $conditionProductId;
$p->discountPercent = $discountPercent;
$p->discountCurrency = $discountCurrency;
$promoId = $p->save();

header('Content-Type: application/json');
echo json_encode(array("promoId"=>$promoId));
?>
