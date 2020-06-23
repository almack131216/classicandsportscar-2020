<?php
	$currentpage = "news";
	
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");

	$PageBuild->AddTags("css/catalogue2.css");
	$PageBuild->AddTags("css/paginator.css");
	
	$PageBuild->SetPageTitle($client['name']." News");
	$PageBuild->SetPageKeywords("");
	$PageBuild->SetPageDescription("");	
	$Content .= $PageBuild->StartMetaTags();
	
	$PaginatorAttributes = array('categoryID'=>$clientCats['News'],'status'=>1,'itemsPerPage'=>10,'itemStyle'=>"row",'status'=>1);

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		//$Content .= $Catalogue->PrintNewsBox();
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="wide">';
			$Content.='<div class="innerPad2">';
			
			$Content .= '<h1>'.$client['name'].' News</h1>'."\r\n";
			$Content .= '<p>&nbsp;</p>';
			//$Content .= '<h2>Would you like to sign-up for our Newsletter?</h2>'."\r\n";
			
			$Content .= include("content/catalogue_show.php");
			
			$Content .= '</div>'."\r\n";
			
		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>