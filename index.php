<?php
require_once 'includes/common.php';
require_once INCLUDE_ROOT . 'classes/PromoType.php';
require_once INCLUDE_ROOT . 'classes/Product.php';
require_once INCLUDE_ROOT . 'classes/Promotion.php';

if (ENVIRONMENT == 'Dev' && SERVER==='Testing') {
	debug('',true);
}
makeDbConnection();
?>
<!DOCTYPE html>
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title></title>
<meta name="description" content="">
<meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=yes">
<link rel="stylesheet" href="css/normalize.min.css">
<link href='http://fonts.googleapis.com/css?family=Sintony:400,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
<link href="css/colorbox-basic.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/main.css">

<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
<![endif]-->
</head>
<body>
<header role="banner">
	<h1>Proof-of-Concept </h1>
	<p>An online shopping cart that can have various discounts applied to individual products or the entire order. <a href="#learnMore" class="colorbox button" style="background-color:#ffcc33;">Learn More</a></p>
</header>
<div role="main">
	<form name="form1" method="post" action="" onSubmit="return false;">
		<div class="clearfix">
			<h2>Step 1: Choose the type of Discount</h2>
			<div class="promoSetup"><label for="savedPromos">Saved Promotions</label><br>
				<select name="savedPromos" id="savedPromos">
					<?php
			$ga = Promotion::getAll();
			foreach ($ga as $promo) {
				echo '<option value="' . $promo->promoId . '">' . $promo->promoDesc . '</option>' . PHP_EOL;
			}
		?>
				</select>
			</div>
			<div class="promoSetup">
				<p><a href="#promoPanel" id="btnAddPromo" class="colorbox button">Create New</a> <a href="#promoPanel" id="btnEditPromo" class="colorbox button">Edit</a></p>
			</div>
		</div>
		<h2 style="clear:both;">Step 2: Add items to your shopping cart</h2>
		<p>Each click will add one more...</p>
		<ul class="horizontalList">
			<li><button class="addProduct"><img src="img/magicMouse.jpg" alt="Magic Mouse"></button></li>
			<li> <button class="addProduct"><img src="img/gfxTablet.jpg" alt="Graphics Tablet"></button></li>
			<li><button class="addProduct"><img src="img/keyboard.jpg" alt="Keyboard"></button></li>
			<li><button class="addProduct"><img src="img/macbook.jpg" alt="Macbook Pro"></button></li>
		</ul>
		<p class="pushLeft"><button id="btnClearCart">Clear Cart</button></p>
		<table class="dataTable pushLeft">
			<caption>
			Your Shopping Cart
			</caption>
			<thead>
				<tr>
					<th>Product</th>
					<th>Quantity</th>
					<th>Unit Price</th>
					<th>Discount</th>
					<th>Total Cost</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>
			<tbody>
			</tbody>
		</table>
		<h2 style="clear:both;">Step 3: Apply Promotion to Cart</h2>
		<p><button id="btnApplyDiscount">Apply Discount</button></p>
	</form>
</div>
<footer>
	<p>Demo site created by Craig Creeger</p>
</footer>
<!-- AP Divs -->
<div id="loadingIndicator">
	<p id="loadingGfx" class="ir">Loading...</p>
</div>

<div style="display:none;">
	<div id="promoPanel" style="padding:0 1em;">
		<p>Promotion Id: <span id="promoId"></span></p>
		<div class="promoSetup"> <label for="promoType">Promotion Type</label><br><form name="form2" method="" action="" onSubmit="return false;">
				<select name="promoType" size="5" id="promoType" style="width:25em;">
					<?php
			$promoTypes = PromoType::getAll();
			foreach ($promoTypes as $pt) {
				echo '<option value="' . $pt->promoType . '">' . $pt->promotion . '</option>' . PHP_EOL;
			}
		?>
				</select>
			</div>
			<div class="promoSetup" id="cp"><label for="conditionProductId">Which Product?</label><br>
				<select id="conditionProductId" name="conditionProductId">
					<?php
			$ga = Product::getAll();
			foreach ($ga as $p) {
				echo '<option value="' . $p->productId . '">' . $p->productName . '</option>' . PHP_EOL;
			}
			?>
				</select>
			</div>
			<div class="promoSetup" id="cc"><label for="conditionCurrency">Spend $</label><br>
				<input name="conditionCurrency" type="text" id="conditionCurrency" value="100.00" size="7"> </div>
			<div class="promoSetup" id="cq"><label for="conditionUnits">How many?</label><br>
				<input name="conditionUnits" type="text" id="conditionUnits" value="3" size="7"> </div>
			<div class="promoSetup" id="dp"><label for="discountPercent">Discount %</label><br>
				<input name="discountPercent" type="text" id="discountPercent" value="40%" size="7"> </div>
			<div class="promoSetup" id="dc"><label for="discountCurrency">Discount $</label><br>
				<input name="discountCurrency" type="text" id="discountCurrency" value="15.00" size="7"> </div>
			<div class="promoSetup" style="clear:left;"><label for="promoDesc">Description</label><br>
			<input type="text" name="promoDesc" id="promoDesc" readonly style="width:24em;"></div>
			<p style="clear:both;"><button id="btnSavePromo">Save</button> <button id="btnDeletePromo">Delete</button></p>
		</form>
	</div>
	<div id="learnMore" style="padding:0 1em;">
		<h1>About</h1>
		<p>This is a demo page that features several different concepts and technologies. This is page is NOT a good starting point for a real shopping cart as it does not contain  necessary security features nor is the code optimized for an actual shopping cart.</p>
		<h1>Features</h1>
		<p>As you play with this page, you will notice:</p>
		<ul>
			<li>The site was constructed as a Responsive Web page. Make your browser window narrower and wider, and you’ll see how the content flows to fit the width.</li>
			<li><code>@media</code> queries are used to dynamically change the look of the page based on the width of the browser window.</li>
			<li>The four product buttons are responsive images – they automatically shrink when the browser width is narrow.</li>
			<li>The products and their prices are stored in a MySQL database. The structure of the database can be seen in the <a href="src/Promotions-PDM.png" target="_blank">Physical Database Model</a>.</li>
			<li>The promotions (discount rules) are also stored in the database.</li>
			<li>AJAX is used in several places to make the user experience cleaner and faster.</li>
			<li>jQuery is used extensively to make the code easier to read and cross-browser compatible.</li>
			<li>This “on-page” pop-up box that you see now is a jQuery plugin.</li>
			<li>The site is tablet-friendly (such as iPads); in other words, it is touch-compatible (no mouse needed) and the content will fit to both landscape and portrait orientations.</li>
			<li>The backend is object-oriented PHP that uses PDO to access the MySQL database.</li>
			<li>All the typography is using <code>@font-face</code> and Google Webfonts to give the page a unique feel.</li>
			<li>Fields that display currency are using a Javascript library to format the number for US dollars.</li>
			<li>The source code for this site can be view on <a href="https://github.com/Craig-Creeger/StorePromotions" target="_blank">my Github page</a>.</li> 
		</ul>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script> 
<script src="js/vendor/accounting.js"></script> 
<script src="js/vendor/jquery.colorbox-min.js"></script> 
<script src="js/main.js"></script>
</body>
</html>
