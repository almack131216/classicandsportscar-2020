<?php
	$currentpage = "filmTV";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/ImageBox.css");
	$PageBuild->AddTags("js/ImageBox.js");
	
	$PageBuild->AddTags("css/box-offers.css");
	
	$itemID = 13675;
	$imageBox = include("imageBox.php");
	$ItemData = $Catalogue->GetItemData($itemID);
	
	$PageBuild->SetPageTitle($ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription($ItemData['detail_6']);	
	$Content .= $PageBuild->StartMetaTags();
	

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		$Content .= $ContactDetails->DetailsBox("");
		//$Content .= $Catalogue->PrintNewsBox();
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle filmTV" id="SpanRight">';

			//$Content.=$Images->ImageBox($ItemData['id']);
			
			$Content.='<div class="fullWidth floatLeft marginBottom">';
			$Content.=$imageBox;
			
			$Content.='<div class="filmTVContact">';
			$Content.='<h2>Contact Us</h2>';
			$Content.='<p>If you are interested in discussing availability of vehicles and pricing, please contact:<br><br><a href="mailto:andrew@classicandsportscar.ltd.uk" title="Contact Andrew Welham by email">Andrew Welham</a><br><br>or call 01944 758000.</p>';
			$Content.='</div>';
			$Content.='</div>';
	
			$Content.='<div class="padRight">';
			$Content.='<h1>'.$ItemData['name'].'</h1>';
			if(!empty($ItemData['detail_2'])) $Content.='<h2>'.$ItemData['detail_2'].'</h2>';
			if(!empty($ItemData['detail_6'])) $Content.='<p>'.$ItemData['detail_6'].'</p>';
			$Content.=$ItemData['description'];
			$Content.='</div>';				

		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>