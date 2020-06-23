<?php
	$Content = '';

	$ThisPageTitle = '';
	$ThisPageKeywords = '';
	$ThisPageDescription = '';
	
	
	$currentpage = "catalogue";

	$itemID = $_GET['uid'];
	
	
	include("inc/classes/ClientData.php");	
	include("inc/classes/Press.php");
	include("inc/classes/PageBuild.php");	
	include("inc/classes/Images.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/CatalogueDetails.php");
	
	if($itemID){
		//$PageBuild->AddTags("js/xfade.js");
		$PageBuild->AddTags("css/slideshow2.css");
		$PageBuild->AddLightBox();
		$PageBuild->AddTags("js/addToFav.js");
		$PageBuild->AddTags("js/emailFriend.js");
		$PageBuild->AddTags("js/print.js");		
		$ItemData = $Catalogue->GetItemData($itemID);
	}
	if($ItemData['category']==$clientCats['Classifieds'] && $ItemData['status']==2) $currentpage = "archive";
	if($ItemData['category']==$clientCats['Testimonials']) $currentpage = "testimonials";
	if($ItemData['category']==$clientCats['News']) $currentpage = "news";
	if($ItemData['category']==$clientCats['Press']) $currentpage = "press";
	$MainNavContent = $SiteFunctions->PrintNav("Main",$arr_page[0]);
	
	
	$PageBuild->AddTags("css/catalogue2.css");
	if($_GET['showLarge']){
		$showLarge=true;
		//$PageBuild->AddTags("css/print.css");
	}
	
	
	
	
	
		
	
	
	
	if($ItemData['category']==$clientCats['Press']){
		$PrintItemData = $Press->PressItemDetails($itemID);
		$PageBuild->AddTags("css/press.css");
	}else{
		$PrintItemData = $CatalogueDetails->ItemDetails($itemID,"");
		$PageBuild->SetBodyTag("<body onload=\"LoadFeatureButtons();\">");
	}
	
	if($HasAudioFile) $PageBuild->AddAudioPlayer();

	$PageBuild->SetPageKeywords($ThisPageKeywords);
	$PageBuild->SetPageDescription($ThisPageDescription);
	$PageBuild->SetPageTitle($ThisPageTitle);
	$Content .= $PageBuild->StartMetaTags();

	if($currentpage == "press"){
		$Content .= '<div class="contentbox">';
	}else{
		$Content .= '<div class="contentbox" id="BlueBG2">';
	}
	
		
		$Content .= '<div class="contentLeft">';
			$Content .= $MainNavContent;
			
			//$Content .= $Catalogue->PrintNewsBox();
			$Content .= $ContactDetails->DetailsBox("");
			$Content .= $ContactDetails->DetailsBox("OpeningHours");
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="SpanRight">';
		
			$Content .= $PrintItemData;
	
		$Content .= '</div>'."\r\n";
		
	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>