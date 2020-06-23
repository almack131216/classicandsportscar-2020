<?php
	$currentpage = "testimonials";
	
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	
	$PageBuild->AddTags("css/catalogue2.css");
	$PageBuild->AddTags("css/paginator.css");
	
	$PaginatorAttributes = array('categoryID'=>$clientCats['Testimonials'],'status'=>1,'itemsPerPage'=>10,'itemStyle'=>"row");
	$testimonialsContent = include("content/catalogue_show.php");
	
	$PageBuild->SetPageTitle("Customer Testimonials : Read what our customers have to say");
	$PageBuild->SetPageKeywords("");
	$PageBuild->SetPageDescription("");	
	$Content .= $PageBuild->StartMetaTags();

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		//$Content .= $Catalogue->PrintNewsBox();
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="spanRight">';
			$Content .= '<div class="innerPad2">';
			
			$Content .= '<h1>Customer Testimonials</h1>'."\r\n";
			$Content .= '<h2>Read what our customers have to say</h2>'."\r\n";
			$Content .= $testimonialsContent;
			
			$Content .= '</div>'."\r\n";
		$Content .= '</div>'."\r\n";

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>