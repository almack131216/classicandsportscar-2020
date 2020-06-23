<?php
	$currentpage = "press";
	
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	
	$PageBuild->AddTags("css/catalogue2.css");
	$PageBuild->AddTags("css/paginator.css");
	
	$ItemData = $Catalogue->GetItemData(8710);
	
	$PageBuild->SetPageTitle($ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription($ItemData['detail_6']);
	$Content .= $PageBuild->StartMetaTags();
	
	$PaginatorAttributes = array('categoryID'=>$clientCats['Press'],'status'=>1,'itemsPerPage'=>10,'itemStyle'=>"row");

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		//$Content .= $Catalogue->PrintNewsBox();
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="wide">';
			$Content.='<div class="innerPad2">';
			
				$Content .= '<h1>'.$ItemData['name'].'</h1>'."\r\n";
				if($ItemData['detail_2']) $Content .= '<h2>'.$ItemData['detail_2'].'</h2>'."\r\n";
	
				$Content .= '<div id="PressList">';
				$Content .= include("content/catalogue_show.php");
				$Content .= '</div>';
			
			$Content .= '</div>';
		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>