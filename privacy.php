<?php
	$currentpage = "about";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Featured.php");
	include("inc/classes/Images.php");
	$PageBuild->AddTags("css/catalogue2.css");
	$PageBuild->AddTags("css/box-offers.css");
	$imageBox = include("imageBox.php");
	
	
	$ItemData = $Catalogue->GetItemData(37006);
	
	$PageBuild->SetPageTitle($ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription("");
	
	$Content .= $PageBuild->StartMetaTags();
	

	$Content .= '<div class="contentbox">';
		
		//$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		//$Content .= $Catalogue->PrintNewsBox();
		//$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="SpanRight">';
			//$Content .= '<img src="'.$gp_uploadPath['large'].$ItemData['image_large'].'" alt="'.$ItemData['name'].'" class="BorderTop">';
			$Content .= $imageBox;
		$Content .= '</div>'."\r\n";
		
		
		
	$Content .= '</div>'."\r\n";
	
	
	//$Content .= $Featured->HomepageRow("homepage");

	
	$Content .= '<div class="contentbox">';
		$Content .= '<div class="mainPad">';
			$Content .= '<h1>'.$ItemData['name'].'</h1>';
			$Content .= $ItemData['description'];
		$Content .= '</div>';
	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>