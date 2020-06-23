<?php
	if($_GET['map']==true) $ShowMap=true;
	$currentpage = "contact";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	include("inc/classes/Featured.php");
	
	
	//$PageBuild->AddTags("js/xfade.js");
	//$PageBuild->AddTags("js/replacement.js");
	$PageBuild->AddTags("css/featurebox2.css");
	$ItemData = $Catalogue->GetItemData(8696);
	
	$PageBuild->SetPageTitle("Contact ".$client['name'].", Malton, North Yorkshire, UK");
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription($ItemData['detail_6']);
	
	$Content .= $PageBuild->StartMetaTags();
	
	

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
			$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
			$Content .= $ContactDetails->DetailsBox("");
			$Content .= $ContactDetails->DetailsBox("OpeningHours");			
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle">';
			if($ShowMap){
				$Content .= '<iframe src="content/GoogleMap.php" id="GoogleMap" class="BorderTop" style="margin:0;padding:0;" width="'.$GoogleMap['width'].'" height="'.$GoogleMap['height'].'" scrolling="no" noresize="noresize" marginwidth="0" marginheight="0" frameborder="0">';
				$Content .= '<p>Your browser is not able to show the Google Map within this window... please <a href="content/GoogleMap.php" title="View Google Map" target="_blank">View Google Map</a> in a new window.</p>';
				$Content .= '</iframe>';
			}else{
				$Content .= '<img src="stat/map-north-yorkshire.gif" width="360" height="270" alt="Malton, North Yorkshire" class="BorderTop">';
			}

			$Content .= '<div class="innerPad">';
			
			$Content .= '<h1>'.$ItemData['name'].'</h1>';
			$Content .= '<h2>'.$ItemData['detail_2'].'</h2>';
			if($ItemData['detail_6']) $Content .= '<p>'.$ItemData['detail_6'].'</p>';
			if($ItemData['description']) $Content .= $ItemData['description'];		
			$Content .= $ContactDetails->PrintContactDetails("");

		$Content .= '</div>';

		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentRight">';
			$Content .= '<div class="featureBox">';
				$tmpQuery = "SELECT * FROM catalogue WHERE status=1 AND category=${clientCats['Links']} AND subcategory=92 ORDER BY position_insubcat ASC";
				$attributes = array('query'=>$tmpQuery,'title'=>"Local Attractions",'limit'=>10);
				$Content .= $Featured->PrintFeatured($attributes);
			$Content .= '</div>'."\r\n";
		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>