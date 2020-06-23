<?php

	//include "access.php";
	$BuildPage = '';
	$CrumbPages=array();
	
	// start output buffering and initialise a session
	//ob_start();
	// "session_start" must FOLLOW "ob_start" as nothing has been sent to browser yet!
	//session_start();
	// time page load (continued in footer)
	/*
	$time = microtime();
	$time = explode(" ", $time);
	$time = $time[1] + $time[0];
	$start = $time;*/
	// check for PageTitle value(set if not already)
	
	
	
	/////////// CONNECT TO DATABASE	
	if(!$SiteAdmin) require_once("inc/connect_2_db.php");
	require_once("inc/classes/SEO.php");
	require_once("inc/classes/SEO_links.php");
	require_once("inc/nav_elements.php");
	require_once("inc/classes/SiteFunctions.php");
	require_once("inc/classes/ContactDetails.php");
	require_once("inc/classes/Crumbs.php");
	
	if($currentpage!="members" && $currentpageSub!="signin") include($SiteAdmin."includes/classes/CMSTextFormat.php");
	include($SiteAdmin."includes/classes/CMSShared.php");
	include($SiteAdmin."includes/classes/CMSSelectOptions.php");
	include($SiteAdmin."includes/classes/CMSDebug.php");
	include($SiteAdmin."prefs/csc_prefs.php");
	

	$PageTitle = '';
		
	
	Class PageBuild {
		
		////////////////////////
		/// Start to build page
		function StartMetaTags(){
			global $echo,$client,$BodyTag,$SiteRoot,$PageTitle,$PageKeywords,$PageDescription,$SiteFunctions,$arr_page;
			global $currentpageSub,$ContactDetails,$GoogleMapTags,$cssTags,$jsTags;
			
			$MetaTags = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\r";
			$MetaTags .= '<html lang="en_gb">'."\r";
			$MetaTags .= '<head>'."\r";
				$MetaTags .= '<title>'.$PageTitle.'</title>'."\r";
				$MetaTags .= '<base href="'.$SiteRoot.'">'."\r";
				$MetaTags .= '<meta http-equiv="Content-Type" content="text/htm; charset=iso-8859-1">'."\r";
				$MetaTags .= '<meta name="Description" content="'.$PageDescription.'">'."\r";
				$MetaTags .= '<meta name="Keywords" content="'.$PageKeywords.'">'."\r";
				$MetaTags .= '<meta name="Author" content="Alex Mackenzie, amactive.co.uk 2010">'."\r";
				$MetaTags .= '<link rel="stylesheet" href="css/global.css?v=180815" type="text/css">'."\r";
				$MetaTags .= '<link rel="stylesheet" href="css/menu.css" type="text/css">'."\r";
				//$MetaTags .= '<link rel="stylesheet" href="css/print.css" type="text/css" media="print">'."\r";
				//$MetaTags .= '<link href="dtr/headings.css" type="text/css" rel="stylesheet">'."\r";
				if($cssTags) $MetaTags .= $cssTags;
				$MetaTags .= '<script type="text/javascript" src="js/common.js"></script>'."\r";
				if($jsTags) $MetaTags .= $jsTags;
				if($GoogleMapTags) $MetaTags .= $GoogleMapTags;
				$MetaTags .= '<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">'."\r";
			$MetaTags .= '</head>'."\r";
			
			if(!$BodyTag){
				$MetaTags .= '<body>'."\r";
			}else{
				$MetaTags .= $BodyTag."\r";
			}

			//if($echo) $MetaTags .= '<p style="background:#f30;color:#fff;padding:10px;">'.$echo.'</p>';
			$MetaTags .= '<div class="wrap_all">'."\r";
			$MetaTags .= '<div class="wrap_content">'."\r";			
			$MetaTags .= '<div class="header">'."\r\n";
				//$MetaTags .= $SiteFunctions->PrintNavMiniLinks("Main",$arr_page[0]);				
				$MetaTags .= '<a href="'.$client['siteroot'].'" title="'.$client['name'].' homepage"><img';
				if($currentpageSub=="photos"){
					$MetaTags .= ' class="logo_topcenter"';
				}else{
					$MetaTags .= ' class="logo_topleft"';
				}
				$MetaTags .= ' src="stat/logo.gif" width="440px" height="90px" border="0" alt="'.$client['name'].'"></a>'."\r\n";
				
				//$MetaTags .= $ContactDetails->PrintContactDetails("header_left")."\r\n";
				if($currentpageSub!="photos")$MetaTags .= $ContactDetails->PrintContactDetails("header_right")."\r\n";
			$MetaTags .= '</div>'."\r\n";			
						
			
			return $MetaTags;
		}
		/// END ///
		
		////////////////////////
		/// Set body tag
		function SetBodyTag($getBodyTag){
			global $BodyTag;			
			if(!empty($getBodyTag)) $BodyTag = $getBodyTag;
		}
		/// END ///
		
		////////////////////////
		/// Start to build page
		function SetPageTitle($getPageTitle){
			global $client,$PageTitle,$CMSTextFormat;			
			if(!empty($getPageTitle)){
				//$PageTitle .= $CMSTextFormat->ReduceString($getPageTitle,90);
				$PageTitle .= $getPageTitle;
			}else{
				$PageTitle .= $client['name'].' &#124; '.$client['pagetitleBasic'];
			}
		}
		/// END ///

				////////////////////////
		/// Start to build page
		function SetPageKeywords($getPageKeywords){
			global $client,$PageKeywords,$CMSTextFormat;			
			if(!empty($getPageKeywords)){
				$PageKeywordsFormatted = str_replace(" &#124;", ",", $getPageKeywords);
				$PageKeywords .= $client['name'].$CMSTextFormat->ReduceString($PageKeywordsFormatted,100);
			}else{
				$PageKeywords .= $client['keywordsBasic'];
			}
		}
		/// END ///

		////////////////////////
		/// Start to build page
		function SetPageDescription($getPageDescription){
			global $client,$PageDescription,$CMSTextFormat;			
			if(!empty($getPageDescription)){
				//$PageDescriptionFormatted = str_replace("&#124;",",", $getPageDescription);
				$PageDescription .= $CMSTextFormat->ReduceString($getPageDescription,220);
			}else{
				$PageDescription .= $client['descriptionBasic'];
			}
		}
		/// END ///
		
		/////////////////////
		/// ADD TAGS (CSS/JS)
		function AddTags($getPath){
			global $cssTags,$jsTags;
			global $CMSShared;			
			
			if(is_array($getPath)){
				if($getFile['file']) $getPath = $getFile['getPath'];
				if($getFile['media']) $media = 'media="'.$getFile['media'].'"';
				$getType = $CMSShared->GetFileType($file);
			}else{
				$getType = $CMSShared->GetFileType($getPath);
			}
			if(!$media) $media = '';
			
			switch($getType){
				case "js": $jsTags .= '<script type="text/javascript" src="'.$getPath.'"></script>'."\r\n";break;
				case "css": $cssTags .= '<link rel="stylesheet" href="'.$getPath.'" type="text/css"'.$media.'>'."\r\n";break;
			}
	
		}
		/// END ///
		
		////////////////////////
		/// third-party Lightbox / Thickbox
		/// Slimbox2 FROM: http://www.digitalia.be/software/slimbox2#download
		/// Thickbox FROM: http://jquery.com/demo/thickbox/
		function AddLightBox(){
			global $cssTags,$jsTags;
			
			$jsTags .= '<script type="text/javascript" src="slimbox2/js/jquery.min.js"></script>'."\r\n";
			$jsTags .= '<script type="text/javascript" src="slimbox2/js/slimbox2.js"></script>'."\r\n";
			$cssTags .= '<link rel="stylesheet" href="slimbox2/css/slimbox2.css" type="text/css" media="screen">'."\r\n";

		}
		/// END ///
		
		////////////////////////
		/// add Google Map Tags
		function AddGoogleMagTags(){
			global $GoogleMapTags,$GoogleMap;
			$GoogleMapTags = '';			
			$GoogleMapTags .= '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$GoogleMap['key'].'" type="text/javascript"></script>';
			$GoogleMapTags .= '<script src="http://www.google.com/uds/api?file=uds.js&v=1.0&key='.$GoogleMap['key'].'" type="text/javascript"></script>';
			$GoogleMapTags .= '<script type="text/javascript" src="control/js/gmap.js"></script>'."\r\n";
		}
		/// END ///
		
		///////////////////
		// Google Analytics
		function GoogleAnalytics(){
			$Code = <<<EOD
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12902280-1");
pageTracker._trackPageview();
} catch(err) {}</script>
EOD;
			return $Code;
		}
		/// END ///
		
		/////////////////////////
		/// add icon
		function AddIconTag($getFile){
			$IconTag = '<link rel="shortcut icon" href="'.$getFile.'" />';			
			return $IconTag;
		}
		/// END ///
		
		
		function BuildFTR(){
			global $PhotoPage,$PageBuild,$ContactDetails;
			
			$ftr = '';

			$ftr .= '</div>'."\r\n";
			
			$ftr .= '<div class="footer">'."\r\n";
			if($PhotoPage) $ftr .= '<div class="DoNotPrint">';
			$ftr .= $ContactDetails->PrintContactDetails("footer");
			if($PhotoPage) $ftr .= '</div>';
			$ftr .= '</div>'."\r\n";
		
			
			$ftr .= '</div>'."\r\n";
			if($_SERVER['HTTP_HOST']!="localhost") $ftr .= $PageBuild->GoogleAnalytics();

			$addThis = <<<EOD
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_floating_style addthis_32x32_style" style="right:0px;top:50%;margin-top:-100px;border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53879fae19b71292"></script>
<!-- AddThis Button END -->
EOD;

			if($_REQUEST['test_addThis']) $ftr .= $addThis;

			$ftr .= '</body>'."\r\n";
			$ftr .= '</html>'."\r\n";	

			return $ftr;
		}

		
		function SS_StartStop($getState){
			if($getState=="start"){
				return '<p style="padding:0;margin:0;line-height:0.0em;"><span id="ss_txtHint"></span></p><div id="ss_HideMe">';
			}elseif($getState=="stop"){
				//return '</div>';
			}
		}
		
		function AddThis(){
			$btn = <<<EOD
<script type="text/javascript">var addthis_pub="csc";</script>
<a href="http://www.addthis.com/bookmark.php?v=20" onMouseOver="return addthis_open(this, '', '[URL]', '[TITLE]')" onMouseOut="addthis_close()" onClick="return addthis_sendto()" class="addthis">
<img src="http://s7.addthis.com/static/btn/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
<script type="text/javascript" src="http://s7.addthis.com/js/200/addthis_widget.js"></script>
EOD;
break;
			return $btn;
		}
		
		/////////////////////////
		/// add audio player
		function AddAudioPlayer(){
			global $jsTags;
			
			$jsTags .= <<<EOD
<script type="text/javascript" src="audio-player/audio-player.js"></script>  
<script type="text/javascript">  
    AudioPlayer.setup("audio-player/player.swf", {  
        width: 230,
        initialvolume: 100,  
        transparentpagebg: "yes",
        leftbg: "263762",  
        lefticon: "ffffff",
        voltrack: "ff3300",
        volslider: "ffffff",
        rightbg: "263762",  
        righticon: "ffffff",
        rightbghover: "ff3300",
    });  
</script>
EOD;
		}
		/// END ///
	
	}
	$PageBuild = new PageBuild();

?>