<?php
	$currentpage = "transport";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/ImageBox.css");
	$PageBuild->AddTags("js/ImageBox.js");
	
	$ItemData = $Catalogue->GetItemData(6513);
	
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
		
		$Content .= '<div class="contentMiddle">';

			$Content.=$Images->ImageBox($ItemData['id']);
			
			$Content.='<div class="padRight">';
			$Content.='<h1>'.$ItemData['name'].'</h1>';
			if(!empty($ItemData['detail_2'])) $Content.='<h2>'.$ItemData['detail_2'].'</h2>';
			if(!empty($ItemData['detail_6'])) $Content.='<p>'.$ItemData['detail_6'].'</p>';
			$Content.=$ItemData['description'];
			$Content.='</div>';				

		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentRight">';

		$Content .= $Catalogue->PrintSubCats("");
		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>