function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {  // if window.onload has already happened
		window.onload = func;
	} else { // if it's our FIRST function (window not yet loaded)
		window.onload = function() {
			oldonload();
			func();
		}
	}
}

function resetFields( whichform ){
	if(document.forms.FormRequest){
		for ( var i = 0; i < whichform.elements.length; i++ ){
			var element = whichform.elements[i];
			if ( element.type == "submit" ) continue;
			if ( !element.defaultValue ) continue;
			if (element.className!="readonly"){
			element.onfocus = function(){
				if ( this.value == this.defaultValue){
					this.value = "";
					}
				}
			element.onblur = function(){
				if ( this.value == ""){
					this.value = this.defaultValue;
				}
			}
		}
		}
	}
}

function prepForms(){
	//alert('prepForms');
	if(document.forms.FormRequest){
		for ( var i = 0; i < document.forms.length; i++ ){
			var thisform = document.forms[i];
			resetFields( thisform );
			/*thisform.onsubmit = function(){
				return validateForm(this);
			}*/
		}
	}
}

function clearDate(getID,getType){
	//alert(getID);
	var DateField = document.getElementById(getID);
	if(getType="datetime"){
		DateField.value = '';//0000-00-00 00:00:00
	}else{
		DateField.value = '';//0000-00-00
	}
	//0000-00-00 was used originally but when calender opens it opens at earliest(smalles) possible date as a second-guess
	//DateField.value = '';
}

function UpdateRadioValue(getID,getValue){
	//alert(getID+'?'+getValue);
	var element = document.getElementById(getID);	
	element.value = getValue;
}

addLoadEvent(prepForms);