<?php

	$catalogueData = "";

	$ResultsPage = $_SERVER['PHP_SELF'].'?currPage='.$currentpage;
	$AppendURL = '';
	if($_GET['PrevPage']){
		$AppendURL.='&amp;PrevPage='.$_GET['PrevPage'];
		$PrevPage = $_GET['PrevPage'];
	}
	if($_GET['status']) $AppendURL.='&amp;status='.$_GET['status'];
	if($_GET['subcategory']) $AppendURL.='&amp;subcategory='.$_GET['subcategory'];
	if($_GET['pg']) $AppendURL.='&amp;pg='.$_GET['pg'];
	if($_GET['currPage']) $AppendURL.='&amp;currPage='.$_GET['currPage'];
	if($_GET['qs_order']) $AppendURL.='&amp;qs_order='.$_GET['qs_order'];
	
	if(!empty($_GET['qs_keywords'])){
		//$ResultsPage = "javascript:updateHint();";
		$AppendURL.='&amp;qs=true';
		
		//$AppendURL.='&currPage='.$currentpage;		
		if($_GET['qs_keywords']) $AppendURL.='&amp;qs_keywords='.$_GET['qs_keywords'];		
		if($_GET['qs_price_min']) $AppendURL.='&amp;qs_price_min='.$_GET['qs_price_min'];
		if($_GET['qs_price_max']) $AppendURL.='&amp;qs_price_max='.$_GET['qs_price_max'];		

	}else{

		$AppendURL.='&amp;qs=false';		
		//$AppendURL.='&currPage='.$currentpage;
	}
	
	$ResultsPage.=$AppendURL;
	if(!$PrevPage) $PrevPage = $ResultsPage;


	if(!empty($_GET['uid'])){

		//$catalogueData .= '<p style="padding:0;margin:0;"><span id="ss_txtHint"></span></p>';
		//$catalogueData .= '<div id="ss_HideMe">';					
		$catalogueData .= $CatalogueDetails->ItemDetails($_GET['uid'],"moreinfo");
		//$catalogueData .= '</div>';		
		
	}else{		
		
		$catalogueItems = include("content/catalogue_show.php");
		
		$Title = '<h1>'.$totals.'&nbsp;Results Found</h1>';
		
		$catalogueData .= $Title;
		//$catalogueData .= require("inc/classes/ss/qs.php");
		$catalogueData .= '<p style="padding:0;margin:0;"><span id="ss_txtHint"></span></p>';
		$catalogueData .= '<div id="ss_HideMe">';
						
			$catalogueData .= $catalogueItems;
			
		$catalogueData .= '</div>';
		
	}
	
	return $catalogueData;	
	
?>