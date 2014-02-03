<?php
/* This "page" is called via AJAX with HTTP GET method, and returns a JSON string.
*/
require_once '../includes/common.php';
require_once INCLUDE_ROOT . 'classes/Promotion.php';

makeDbConnection();

$promoId = (array_key_exists('promoId', $_GET)) ? sanitizeString($_GET['promoId']) : 'false';
$p = new Promotion($promoId);
$promo = new stdClass();
$promo->promoId = $p->promoId;
$promo->promoDesc = $p->promoDesc;
$promo->promoType = $p->promoType;
$promo->conditionUnits = $p->conditionUnits;
$promo->conditionCurrency = $p->conditionCurrency;
$promo->conditionProductId = $p->conditionProductId;
$promo->discountPercent = $p->discountPercent;
$promo->discountCurrency = $p->discountCurrency;

header('Content-Type: application/json');
echo json_encode($promo);
?>
