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
	
	
	$ItemData = $Catalogue->GetItemData(8697);
	$FullStory = $Catalogue->GetItemData(8717);
	
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
			$Content .= '<h1>'.$FullStory['name'].'</h1>';
			if($FullStory['detail_2']) $Content .= '<h2>'.$FullStory['detail_2'].'</h2>';
			if($FullStory['detail_6']) $Content .= '<p>'.$FullStory['detail_6'].'</p>';
			$Content .= $FullStory['description'];
		$Content .= '</div>';
	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>