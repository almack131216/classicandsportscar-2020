
var map;
var geocoder;

////////////////////////////////////////////////////////
//****************************************************//
//* Shouldn't need to change much beyond this point! *//
//****************************************************//
////////////////////////////////////////////////////////
function load(getLat,getLon) {
	if (GBrowserIsCompatible()) {
		geocoder = new GClientGeocoder();
		map = new GMap2(document.getElementById("LargeMap"));
		map.addControl(new GScaleControl()); 		
		//map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GLargeMapControl3D());
		var marker = new GMarker(new GLatLng(getLat,getLon));
		map.addOverlay(marker);
		map.setCenter(new GLatLng(getLat,getLon), 16);
		map.setMapType(G_HYBRID_MAP);
		
	}
}

//function to allow more than one window.onload event
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}


//OpenClose
function OpenClose(id) {
	var element = document.getElementById(id);
	
	if (element.className != 'Expanded') {
		element.setAttribute("class","Expanded");
	}else{
		element.setAttribute("class","ExpandMe");
	}
}

function LoadFeatureButtons() {
	if(!document.getElementById("DetailsBox")) return;
	initiatePrintLink();
	initiateMailFriend();
	initiateFavLink();	
}