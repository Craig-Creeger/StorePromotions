var products = new Array();
var userEnteredPercent; //Comes from the discount percent textbox.
var runningTotal = 0; //Comes from rebuildTable()

$('.addProduct').each(function(idx) {
	$(this).data('productId', idx + 1); //Set the key value for each of the products
});

//Default promotion to the first one
$('option').filter(':first').attr('selected', 'selected');

//Setup listeners on the product buttons
$('.horizontalList').on('click', '.addProduct', function(e) {
	//Increment the quantity.
	for (var i=0; i<products.length; i++) {
		if (products[i].productId == $(this).data('productId')) {
			products[i].cartQuantity++;
			break;
		}
	}
	rebuildTable();
});

//Setup listener on the [Clear Cart] button
$('#btnClearCart').on('click', emptyCart);

//Setup listener on discount percent
$('#discountPercent').on('change', function(e) {
	userEnteredPercent = parsePercent(this.value);
	if (typeof userEnteredPercent === 'string') {
		this.value = userEnteredPercent;
	} else {
		this.value = userEnteredPercent.stringVal;
	}
	updatePromoDesc();
}).trigger('change');

//Setup listener on [Apply Discount] button.
$('#btnApplyDiscount').on('click', function(e) {
	//Get the promotion information from the database
	getPromoDetails(false); //synchronous mode
	
	//Remove any previously applied discounts
	for (var j=0; j<products.length; j++) {
		products[j].discount = 0;
	}
	switch (Number($('#promoType').val())) {
		case 1: //buy x, get 1
			for (var i=0; i<products.length; i++) {
				if (products[i].productId == $('#conditionProductId').val() && products[i].cartQuantity >= Number($('#conditionUnits').val())) {
					products[i].cartQuantity++; //add another to the cart
					products[i].discount = -1 * products[i].unitPrice;
					break;
				}
			}
			break;
		case 2: //buy 1, get % off
			for (var i=0; i<products.length; i++) {
				if (products[i].productId == $('#conditionProductId').val() && products[i].cartQuantity > 0) {
					products[i].discount = -1 * ((products[i].unitPrice * products[i].cartQuantity) * userEnteredPercent.numericVal);
					break;
				}
			}
			break;
		case 3: //buy 1, get $ off
			for (var i=0; i<products.length; i++) {
				if (products[i].productId == $('#conditionProductId').val() && products[i].cartQuantity > 0) {
					products[i].discount = -1 * (document.getElementById('discountCurrency').value * products[i].cartQuantity);
					break;
				}
			}
			break;
		case 4: //buy 1, get % off whole order
			for (var i=0; i<products.length; i++) {
				if (products[i].productId == $('#conditionProductId').val() && products[i].cartQuantity > 0) {
					//Now discount all the products
					for (var j=0; j<products.length; j++) {
						products[j].discount = -1 * ((products[j].unitPrice * products[j].cartQuantity) * userEnteredPercent.numericVal);
					}
					break;
				}
			}
			break;
		case 5: //spend x$, get % off whole order
			rebuildTable();
			if (runningTotal >= Number($('#conditionCurrency').val())) {
				//Now discount all the products
				for (var j=0; j<products.length; j++) {
					products[j].discount = -1 * ((products[j].unitPrice * products[j].cartQuantity) * userEnteredPercent.numericVal);
				}
				break;
			}
			break;
	}
	rebuildTable();
});

//Setup listener on the promotion type list
$('#promoType').on('change', function(e) {
	switch (Number(this.selectedIndex) + 1) {
		case 1: //buy x, get 1
			$('#cp').css('display','block');
			$('#cq').css('display','block');
			$('#cc').css('display','none');
			$('#dp').css('display','none');
			$('#dc').css('display','none');
			break;
		case 2: //buy 1, get % off
			$('#cp').css('display','block');
			$('#cq').css('display','none');
			$('#cc').css('display','none');
			$('#dp').css('display','block');
			$('#dc').css('display','none');
			break;
		case 3: //buy 1, get $ off
			$('#cp').css('display','block');
			$('#cq').css('display','none');
			$('#cc').css('display','none');
			$('#dp').css('display','none');
			$('#dc').css('display','block');
			break;
		case 4: //buy 1, get % off whole order
			$('#cp').css('display','block');
			$('#cq').css('display','none');
			$('#cc').css('display','none');
			$('#dp').css('display','block');
			$('#dc').css('display','none');
			break;
		case 5: //spend x$, get % off whole order
			$('#cp').css('display','none');
			$('#cq').css('display','none');
			$('#cc').css('display','block');
			$('#dp').css('display','block');
			$('#dc').css('display','none');
			break;
	}
	updatePromoDesc();
}).trigger('click');

$('#btnAddPromo').on('click', function(e) {
	$('#promoId').text('new promotion');
	$('#promoDesc').val('');
	$('#promoType option').each(function() {
		 if (this.value == 1) {
			 this.selected = true;
		 }
	});
	$('#conditionProductId option').each(function() {
		 if (this.value == 1) {
			 this.selected = true;
		 }
	});
	$('#conditionCurrency').val(100.00);		
	$('#conditionUnits').val(3);		
	$('#discountCurrency').val(15.0);		
	$('#discountPercent').val(0.2).trigger('change');
	$('#promoType').trigger('change');	
});

function getPromoDetails(async) {
	$.ajax({
		//Your request
		type: 'GET',
		url: 'ajax/getPromotion.php',
		data: {"promoId": $('#savedPromos').val()},
		cache: false,
		
		//Expected response
		dataType: 'json',
		async: async
	}).done(function(data, textStatus, jqXHR) {
		//Do this when successful
		$('#promoId').text(data.promoId);
		$('#promoDesc').val(data.promoDesc);
		$('#promoType option').each(function() {
			 if (this.value == data.promoType) {
				 this.selected = true;
			 }
		});
		$('#conditionProductId option').each(function() {
			 if (this.value == data.conditionProductId) {
				 this.selected = true;
			 }
		});
		$('#conditionCurrency').val(data.conditionCurrency);		
		$('#conditionUnits').val(data.conditionUnits);		
		$('#discountCurrency').val(data.discountCurrency);		
		$('#discountPercent').val(data.discountPercent).trigger('change');
		$('#promoType').trigger('change');	
	}).fail(function(jqXHR, textStatus, errorThrown) {
		//Do this on error
		alert(textStatus + ' / ' + dump(errorThrown));
	});
}

$('#btnEditPromo').on('click', function(e) {
	getPromoDetails(true);
});

$('#btnSavePromo').on('click', function(e) {
	userEnteredPercent = parsePercent($('#discountPercent').val());
	var params = {
		"promoId": $('#promoId').text(),
		"promoDesc": $('#promoDesc').val(),
		"promoType": $('#promoType').val(),
		"conditionProductId": $('#conditionProductId').val(),
		"conditionCurrency": $('#conditionCurrency').val(),
		"conditionUnits": $('#conditionUnits').val(),
		"discountPercent": userEnteredPercent.numericVal,
		"discountCurrency": $('#discountCurrency').val(),
	};
	$.ajax({
		//Your request
		type: 'POST',
		url: 'ajax/savePromotion.php',
		data: params,
		cache: false,
		
		//Expected response
		dataType: 'json',
		async: true
	}).done(function(data, textStatus, jqXHR) {
		//Do this when successful
		//Set promoId to the one you just saved.
		refreshSavedPromos(data.promoId);
		$.colorbox.close();
	}).fail(function(jqXHR, textStatus, errorThrown) {
		//Do this on error
		alert(textStatus + ' / ' + dump(errorThrown));
	});
});

$('#btnDeletePromo').on('click', function(e) {
	var params = {
		"promoId": $('#promoId').text()
	}
	$.ajax({
		//Your request
		type: 'POST',
		url: 'ajax/deletePromotion.php',
		data: params,
		cache: false,
		
		//Expected response
		dataType: 'json',
		async: true
	}).done(function(data, textStatus, jqXHR) {
		//Do this when successful
		//Set promoId to the one you just saved.
		refreshSavedPromos();
		$.colorbox.close();
	}).fail(function(jqXHR, textStatus, errorThrown) {
		//Do this on error
		alert(textStatus + ' / ' + dump(errorThrown));
	});
	
});

function refreshSavedPromos(promoId) {
	if (typeof promoId == 'undefined') {
		promoId = 0;
	}
	$.ajax({
		//Your request
		type: 'GET',
		url: 'ajax/getPromotions.php',
		data: null,
		cache: false,
		
		//Expected response
		dataType: 'json',
		async: true
	}).done(function(data, textStatus, jqXHR) {
		//Do this when successful
		//remove all existings promotions from the Select list
		var sp = document.getElementById('savedPromos');
		var i;
		while (i = sp.options.length) {
			sp.remove(i - 1)
		}
		//insert new promotions into the list
		var opt;
		var selectedOne = false;
		for (i = 0; i < data.length; i++) {
			opt = document.createElement('option');
			opt.value = data[i].promoId;
			opt.text = data[i].promoDesc;
			if (data[i].promoId == promoId) {
				opt.selected = true;
				selectedOne = true;
			}
			sp.add(opt);
		}
		if (!selectedOne) {
			//Perhaps a delete was just done, so default the first item in the list
			if (sp.options.length > 0) {
				sp.options[0].selected = true;
			}
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		//Do this on error
		alert(textStatus + ' / ' + dump(errorThrown));
	});
}

$('#conditionProductId').on('change', updatePromoDesc);
$('#conditionCurrency').on('blur', updatePromoDesc);
$('#conditionUnits').on('blur', updatePromoDesc);
$('#discountCurrency').on('blur', updatePromoDesc);

function updatePromoDesc() {
	var desc = '';
	var cp = document.getElementById('conditionProductId');
	switch (Number($('#promoType').val())) {
		case 1: //buy x, get 1
			desc = "Buy " + $('#conditionUnits').val() + ' ' + cp.options[cp.selectedIndex].text + 's and get 1 free';
			break;
		case 2: //buy 1, get % off
			desc = "Get " + $('#discountPercent').val() + ' off ' + cp.options[cp.selectedIndex].text;
			break;
		case 3: //buy 1, get $ off
			desc = 'Get $' + $('#discountCurrency').val() + ' off ' + cp.options[cp.selectedIndex].text;
			break;
		case 4: //buy 1, get % off whole order
			desc = "Buy a " + cp.options[cp.selectedIndex].text + ' and get ' + $('#discountPercent').val() + ' off whole order';
			break;
		case 5: //spend x$, get % off whole order
			desc = "Spend $" + $('#conditionCurrency').val() + ' and get ' + $('#discountPercent').val() + ' off whole order';
			break;
	}
	$('#promoDesc').val(desc);
}

function rebuildTable() {
	var tableBody = '';
	runningTotal = 0;
	var runningDiscount = 0;
	var totalCost = 0;
	for (var i=0; i<products.length; i++) {
		if (products[i].cartQuantity > 0) {
			tableBody += "<tr><td>" + products[i].productName + '</td>\n';
			tableBody += '<td>' + products[i].cartQuantity + '</td>\n'; 
			tableBody += '<td>' + accounting.formatMoney(products[i].unitPrice) + '</td>\n'; 
			tableBody += '<td>' + accounting.formatMoney(products[i].discount) + '</td>\n'; 
			totalCost = Number(products[i].cartQuantity) * Number(products[i].unitPrice);
			runningTotal += totalCost;
			runningDiscount += products[i].discount;
			tableBody += '<td>' + accounting.formatMoney(totalCost + products[i].discount) + '</td></tr>\n'; 
		}
	}
	$('tbody').html(tableBody);
	$('tfoot').html('<tr><td></td><td></td><td></td><td></td><td>' + accounting.formatMoney(runningTotal + runningDiscount) + '</td></tr>');
}

function emptyCart() {
	$.ajax({
		//Your request
		type: 'GET',
		url: 'ajax/getProducts.php',
		data: '',
		cache: false,
		
		//Expected response
		dataType: 'json',
		async: true
	}).done(function(data, textStatus, jqXHR) {
		//Do this when successful
		products = data; //An array of objects
		for (var i=0; i<products.length; i++) {
			products[i].cartQuantity = 0;
			products[i].discount = 0;
		}
		rebuildTable();
	}).fail(function(jqXHR, textStatus, errorThrown) {
		//Do this on error
		//$('table[data="course"] tbody').html('<tr><td colspan="4">Error: ' + textStatus + ' / ' + dump(errorThrown));
		rebuildTable();
	});
}
emptyCart();

$('.colorbox').colorbox({
	inline:true,
	opacity:0.7,
	width:'90%'
});

// Setting up a loading indicator using Ajax Events
$(document).ajaxStart(function() {
	$('#loadingIndicator').show();
}).ajaxStop(function() {
	$('#loadingIndicator').hide();
});

function dump(arr,level) {
var dumped_text = "";
if(!level) level = 0;

//The padding given at the beginning of the line.
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { //Array/Hashes/Objects
 for(var item in arr) {
  var value = arr[item];
 
  if(typeof(value) == 'object') { //If it is an array,
   dumped_text += level_padding + "'" + item + "' ...\n";
   dumped_text += dump(value,level+1);
  } else {
   dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
  }
 }
} else { //Stings/Chars/Numbers etc.
 dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
}

function parsePercent(possible) {
/*	Pass in a string such as "0.50" or "30%"
	This function will normalize the string, or return Error.
	Returns object {"numericVal", "stringVal"} or string "errMsg"
	The first non-white space characters must [.|0-9]
	If any subsequent characters are not % then make the number a percent (multiply by 100) */
	
	var errMsg = 'Parse error';
	var operation;
	var numberIntegral = '';
	var numberFractional = '';
	var curChar = '';
	var buildingFractional = false;
	var finalNum = '';
	var tempNum = '';

	possible = possible.replace(/^\s+|\s+$/gm, ''); //trims leading and trailing spaces
	if (possible.length == 0) {
		return '';
	}
	//Locate the number
	for (var i = 0; i < possible.length; i++) {
		curChar = possible.substr(i, 1);
		if (curChar == '%') break; //Hit the last valid character
		if (curChar == '.') {
			buildingFractional = true;
			continue;
		}
		if (String(curChar) >= "0" && String(curChar) <= "9") {
			if (buildingFractional) {
				numberFractional += String(curChar);
			} else {
				numberIntegral += String(curChar);
			}
		} else {
			return errMsg;
		}
	}
	finalNum = '';
	if (curChar == "%") {
		//User entered a percent
		finalNum += ((numberIntegral.length == 0) ? "0" : numberIntegral);
		if (numberFractional.length > 0 && numberFractional != 0) {
			finalNum += "." + numberFractional;
		}
		return {
			"numericVal": finalNum / 100,
			"stringVal": finalNum + "%"
		};
	} else {
		tempNum = ((numberIntegral.length == 0) ? "0" : numberIntegral);
		if (numberFractional.length > 0 && numberFractional != 0) {
			tempNum += "." + numberFractional;
		}
		if ($.isNumeric(tempNum)) {
			finalNum += (tempNum * 100) + "%";
			return {
				"numericVal": tempNum,
				"stringVal": finalNum
			};
		} else {
			return errMsg;
		}
	}
}
