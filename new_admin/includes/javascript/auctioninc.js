function displayactionincshippingmethod(val) {
	if(val=='C' || val=='CI'){
	   document.getElementById('actioninc_fixedfee').style.display='none';
		document.getElementById('actioninc_length').value='';
		document.getElementById('actioninc_width').value='';
		document.getElementById('actioninc_height').value='';
		document.getElementById('actioninc_package').value='T';
		document.getElementById('actioninc_origincode').value='';
		document.getElementById('actioninc_handlingcode').value='';
		document.getElementById('actioninc_fixeddollaramount').value='';
		document.getElementById('actioninc_handlingcode').disabled=false;
		document.getElementById('actioninc_fixeddollaramount').disabled=false;
		document.getElementById('actioninc_calculationmethod').style.display='block';
	}
	else if(val=='F'){
		document.getElementById('actioninc_fixedfeecode').value='';
		document.getElementById('actioninc_fixedfee1').value='';
		document.getElementById('actioninc_fixedfee2').value='';
		
		document.getElementById('actioninc_calculationmethod').style.display='none';
		document.getElementById('actioninc_fixedfee').style.display='block';
	}
	else {
		document.getElementById('actioninc_calculationmethod').style.display='none';
		document.getElementById('actioninc_fixedfee').style.display='none';
	}
	
}

function handlingcode(id) {
	if(id=='actioninc_handlingcode' && document.getElementById(id).value) {
		document.getElementById('actioninc_fixeddollaramount').disabled=true;
	}
	else if(id=='actioninc_fixeddollaramount' && document.getElementById(id).value) {
	document.getElementById('actioninc_handlingcode').disabled=true;
	}
	else {
		document.getElementById('actioninc_fixeddollaramount').disabled=false;
		document.getElementById('actioninc_handlingcode').disabled=false;
	}
}

function fixedfee(id) {
	if(id=='actioninc_fixedfeecode' && document.getElementById(id).value) {
		document.getElementById('actioninc_fixedfee1').disabled=true;
		document.getElementById('actioninc_fixedfee2').disabled=true;
	}
	else if((id=='actioninc_fixedfee1' || id=='actioninc_fixedfee2') && (document.getElementById('actioninc_fixedfee1').value || document.getElementById('actioninc_fixedfee2').value)) {
		document.getElementById('actioninc_fixedfeecode').disabled=true;
	}
	else {
		document.getElementById('actioninc_fixedfeecode').disabled=false;
		document.getElementById('actioninc_fixedfee1').disabled=false;
		document.getElementById('actioninc_fixedfee2').disabled=false;
	}
}

function onblurtextstatuslength(id) {
	if (document.getElementById('actioninc_length').value == '' || document.getElementById('actioninc_length').value=='undefined') { 
		document.getElementById('actioninc_length').value = 'Length'; 
	}	
}

function onblurtextstatuswidth(id) {
	if (document.getElementById('actioninc_width').value == '' || document.getElementById('actioninc_width').value=='undefined') { 
		document.getElementById('actioninc_width').value = 'Width'; 
	}	
}

function onblurtextstatusheight(id) {
	if (document.getElementById('actioninc_height').value == '' || document.getElementById('actioninc_height').value=='undefined') { 
		document.getElementById('actioninc_height').value = 'Height'; 
	}	
}

function onblurtextstatusorigincode(id) {
	if (document.getElementById('actioninc_origincode').value == '' || document.getElementById('actioninc_origincode').value=='undefined') { 
		document.getElementById('actioninc_origincode').value = 'default'; 
	}	
}

function onblurtextstatusfixeddollaramt(id) {
	if (document.getElementById('actioninc_fixeddollaramount').value == '' || document.getElementById('actioninc_height').value=='undefined') { 
		document.getElementById('actioninc_fixeddollaramount').value = 'Fixed dollar amount'; 
	}	
}

function onblurtextstatushandlingcode(id) {
	if (document.getElementById('actioninc_handlingcode').value == '' || document.getElementById('actioninc_handlingcode').value=='undefined') { 
		document.getElementById('actioninc_handlingcode').value = 'Handling code'; 
	}	
}

function onfocustextstatuslength(id) {
	if (document.getElementById('actioninc_length').value == 'Length') { 
		document.getElementById('actioninc_length').value = ''; 
	}	
}

function onfocustextstatuswidth(id) {
	if (document.getElementById('actioninc_width').value == 'Width') { 
		document.getElementById('actioninc_width').value = ''; 
	}	
}

function onfocustextstatusheight(id) {
	if (document.getElementById('actioninc_height').value == 'Height') { 
		document.getElementById('actioninc_height').value = ''; 
	}	
}

function onfocustextstatusorigincode(id) {
	if (document.getElementById('actioninc_origincode').value == 'default') { 
		document.getElementById('actioninc_origincode').value = ''; 
	}	
}

function onfocustextstatusfixeddollaramt(id) {
	if (document.getElementById('actioninc_fixeddollaramount').value == 'Fixed dollar amount') { 
		document.getElementById('actioninc_fixeddollaramount').value = ''; 
	}	
}

function onfocustextstatushandlingcode(id) {
	if (document.getElementById('actioninc_handlingcode').value == 'Handling code') { 
		document.getElementById('actioninc_handlingcode').value = ''; 
	}	
}

function AuctionIncpopupWindow(url) {
	window.open(url,'Guide to AuctionInc Shipping Settings','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=650,height=400,screenX=150,screenY=150,top=150,left=250')
}

function extractNumber(obj, decimalPlaces, allowNegative)
{
	var temp = obj.value;
	
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9]*';
	if (decimalPlaces > 0) {
	reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
	} else if (decimalPlaces < 0) {
	reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;
	
	// first replace all non numbers
	var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');
	
	if (allowNegative) {
	// replace extra negative
	var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
	var reg2 = /-/g;
	temp = temp.replace(reg2, '');
	if (hasNegative) temp = '-' + temp;
	}
	
	if (decimalPlaces != 0) {
	var reg3 = /\./g;
	var reg3Array = reg3.exec(temp);
	if (reg3Array != null) {
	// keep only first occurrence of .
	// and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
	var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
	reg3Right = reg3Right.replace(reg3, '');
	reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
	temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
	}
	}
	
	obj.value = temp;
}
function blockNonNumbers(obj, e, allowDecimal, allowNegative)
{
 var key;
 var isCtrl = false;
 var keychar;
 var reg;

 if(window.event) {
 key = e.keyCode;
 isCtrl = window.event.ctrlKey
 }
 else if(e.which) {
 key = e.which;
 isCtrl = e.ctrlKey;
 }

 if (isNaN(key)) return true;

 keychar = String.fromCharCode(key);

 // check for backspace or delete, or if Ctrl was pressed
 if (key == 8 || isCtrl)
 {
 return true;
 }

 reg = /\d/;
 var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
 var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;

 return isFirstN || isFirstD || reg.test(keychar);
}

//Validating the Weight Field.
function validateweight(){
	var calcmethod=document.getElementById('actioninc_calcmethod').value;
	
	var lbs=document.getElementById('actioninc_lbs').value;
	var oz=document.getElementById('actioninc_oz').value;
	if(calcmethod=='C' || calcmethod=='CI') {
		if(lbs!='' || oz!='') {
			return true;
		}
		else{
			alert('Please enter product weight.');
			return false;
		}
	}
	
}
