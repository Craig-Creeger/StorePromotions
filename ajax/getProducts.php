<?php
/* This "page" is called via AJAX with HTTP GET method, and returns a JSON string.
*/
require_once '../includes/common.php';
require_once INCLUDE_ROOT . 'classes/Product.php';

makeDbConnection();

$productId = (array_key_exists('productId', $_GET)) ? sanitizeString($_GET['productId']) : 'false';
$objAll = Product::getAll($productId);
header('Content-Type: application/json');
echo json_encode($objAll);
?>
