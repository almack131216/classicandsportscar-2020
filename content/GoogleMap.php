<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

<?php

if(!$client){
	include("../inc/classes/ClientData.php");
}
echo <<<EOD

<title>${GoogleMap['title']}</title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=${GoogleMap['key']}" type="text/javascript"></script>	    

<script type="text/javascript">

    //<![CDATA[
    
    function initialize() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(${GoogleMap['latitude']}, ${GoogleMap['longitude']}), ${GoogleMap['zoom']});
		
		map.addControl(new GScaleControl());
		map.addControl(new GLargeMapControl3D());
		map.addControl(new GMapTypeControl());
   
        
        var marker = new GMarker(new GLatLng(${GoogleMap['latitude']}, ${GoogleMap['longitude']}));
        GEvent.addListener(marker, "click", function() {
           var html = '<div style="width:200px;padding-right:10px">Please make a note of our telephone number in case you cannot find us.<br /><strong>We look forward to seeing you.<\/strong><\/div>';
           marker.openInfoWindowHtml(html);
         });
         map.addOverlay(marker);
         /*GEvent.trigger(marker, "click");*/
      }
    }

    //]]>
    </script>
    <style type="text/css">
    #map {width:${GoogleMap['width']};height:${GoogleMap['height']};font:normal 10px/16px Verdana,Arial,Helvetica,sans-serif;}
    </style>
  </head>
  <body onload="initialize()" onunload="GUnload()">
    <div id="map"></div>
  </body>
</html>
EOD;

?>