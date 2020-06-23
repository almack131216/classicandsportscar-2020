<?php
	$currentpage = "selling";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Featured.php");
	include("inc/classes/Images.php");
	
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/ImageBox.css");
	$PageBuild->AddTags("js/ImageBox.js");
	
	$ItemData = $Catalogue->GetItemData(8695);
	
	$PageBuild->SetPageTitle($ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription($ItemData['detail_6']);
	$Content .= $PageBuild->StartMetaTags();
	

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
			$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
			$Content .= $ContactDetails->DetailsBox("");
			//$Content .= $ContactDetails->DetailsBox("OpeningHours");
		//$Content .= $Catalogue->PrintNewsBox();
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="SpanRight">';

			//$Content.=$Images->ImageBox($ItemData['id']);
			
			$Content .= '<div class="innerPad2">';
				$Content.='<h1>'.$ItemData['name'].'</h1>';
				if(!empty($ItemData['detail_6'])) $Content.='<h2>'.$ItemData['detail_6'].'</h2>';
				$Content.=$ItemData['description'];
			$Content.='</div>';				

		$Content .= '</div>'."\r\n";
		
		/*
		$Content .= '<div class="contentRight">';		
		$attributes = array('status'=>2,'title'=>"Recently Sold Cars",'limit'=>10);
		$Content .= $Featured->PrintFeatured($attributes);
		$Content .= '</div>'."\r\n";
		*/

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>