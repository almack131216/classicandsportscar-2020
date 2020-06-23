<?php
	$currentpage = "home";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Featured.php");
	include("inc/classes/Images.php");
	$PageBuild->AddTags("css/catalogue2.css");
	$PageBuild->AddTags("css/box-offers.css");
	$itemID = 8697;
	$imageBox = include("imageBox.php");	
	$ItemData = $Catalogue->GetItemData($itemID);
	
	
	if($tmpPageTitle){
		$PageBuild->SetPageTitle("Classic and Sportscar Centre &#124; ".$tmpPageTitle);
	}else{
		$PageBuild->SetPageTitle("Classic and Sportscar Centre &#124; Malton, North Yorkshire");
	}
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription("");
	
	$Content .= $PageBuild->StartMetaTags();
	

	


	$Content .= '<div class="contentbox">';
		
		//$Content .= '<div class="contentLeft">';
		$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
		//$Content .= $Catalogue->PrintNewsBox();
		//$Content .= '</div>'."\r\n";
		
		
		$Content .= '<div class="contentMiddle" id="SpanRight">';
			//$Content .= '<a href="Jaguar-E_Type-turns-50/news/10383" title="Link to this story: Jaguar E-Type turns 50"><img src="tmp/jaguar_e-type_50th_anniversary.jpg" alt="Jaguar E-Type turns 50" class="BorderTop"></a>';
			//$Content .= '<a href="New-Workshop-Opens/news/21887" title="Link to this story: new Workshop Opens"><img src="tmp/new-workshop-opens.jpg" alt="New Workshop Opens" class="BorderTop"></a>';
			
			//$Content .= '<a href="press" title="Harold Wilson\' 1967 Vanden Plas Princess R"><img src="tmp/harold-wilson-vanden-plas.jpg" alt="Harold Wilson\' 1967 Vanden Plas Princess R" class="BorderTop"></a>';
			
			if($_GET['testTV']){			
				$Content.='<div class="fullWidth floatLeft marginBottom">';
				$Content .= '<a href="press" title="Hire a classic car for Film and TV"><img src="tmp/film-and-tv-hire.jpg" alt="Hire a classic car for Film and TV" style="float:left;width:360px;height:270px;"></a>';
				$Content.='<div class="filmTVContact">';
				$Content.='<h2>Classic Car hire for photo shoots, Film & TV</h2>';
				$Content.='<p>If you are interested in discussing availability of vehicles and pricing, please contact:<br><br><a href="mailto:andrew@classicandsportscar.ltd.uk" title="Contact Andrew Welham by email">Andrew Welham</a><br><br>or call 01944 758000.</p>';
				$Content.='</div>';
				$Content.='</div>';
			}else{
				//$Content .= $imageBox;
				$Content .= '<iframe width="580" height="270" src="https://www.youtube.com/embed/Kr_iy580Zsw?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>';				
			}

		$Content .= '</div>'."\r\n";
		
		
		
	$Content .= '</div>'."\r\n";

	
	/*
	$Content .= '<div class="contentbox" id="Christmas">';
		$Content .= '<p>We would like to wish all of our friends & customers a very Happy Christmas and Prosperous New Year.</p>'."\r\n";
		$Content .= '<p>Please see our <a href="'.$SEO_links->GenerateLink("contact").'" title="Link to Christmas Opening Hours">Christmas opening hours</a></p>';
		$Content .= '<p>Christmas Offer: Free Nationwide Delivery (including Eire & Northern Ireland)<br>on all Classic Car purchases made in December.</p>'."\r\n";
	$Content .= '</div>'."\r\n";
	*/

	$Content .= $Featured->HomepageRow("homepage");

	
	$Content .= '<div class="contentbox">';
		$Content .= '<div class="mainPad">';
			$Content .= '<h1>'.$ItemData['name'].'</h1>';
			if($ItemData['detail_2']) $Content .= '<h2>'.$ItemData['detail_2'].'</h2>';
			if($ItemData['detail_6']) $Content .= '<p>'.$ItemData['detail_6'].'</p>';
			$Content .= $ItemData['description'];
		$Content .= '</div>';
	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>