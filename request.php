<?php
	$currentpage = "request";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	
	
	
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/ImageBox.css");
	$PageBuild->AddTags("js/ImageBox.js");
	$PageBuild->AddTags("css/forms.css");
	//$PageBuild->AddTags("js/forms.js");
	
	$FormData = include("content/contactForm.php");
	$ItemData = $Catalogue->GetItemData(8694);
	
	$PageBuild->SetPageTitle($ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription($ItemData['detail_6']);	
	$Content .= $PageBuild->StartMetaTags();
	

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		$Content .= $ContactDetails->DetailsBox("");
		$Content .= $ContactDetails->DetailsBox("OpeningHours");
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="wide">';
			
			$Content.='<div class="innerPad2">';
			$Content.='<h1>'.$ItemData['name'].'</h1>';
			if(!empty($ErrorLog) || $PrintErrorMessage){
				$Content .= $PrintErrorMessage;
			}else{
				if(!$_POST['Submit']){
					if(!empty($ItemData['detail_2'])) $Content.='<h2>'.$ItemData['detail_2'].'</h2>';
					if(!empty($ItemData['detail_6'])) $Content.='<p>'.$ItemData['detail_6'].'</p>';
					if(!empty($ItemData['description'])) $Content.=$ItemData['description'];
				}
			}			
			
			$Content.=$FormData;
			$Content.='</div>';				

		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>