<?php
	$currentpage = "plates";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/tables.css");
	
	$ItemData = $Catalogue->GetItemData(8709);
	
	$PageBuild->SetPageTitle($ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageDescription($ItemData['detail_6']);
	$Content .= $PageBuild->StartMetaTags();
	

	$Content .= '<div class="contentbox">';
		
		$Content .= '<div class="contentLeft">';
			$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);
			$Content .= $ContactDetails->DetailsBox("Contact");
			//$Content .= $ContactDetails->DetailsBox("OpeningHours");
		$Content .= '</div>'."\r\n";
		
		$Content .= '<div class="contentMiddle" id="SpanRight">';
			$Content .= '<div class="innerPad2">';
				$Content .= '<h1>'.$ItemData['name'].'</h1>';
				if(!empty($ItemData['detail_2'])) $Content.='<h2>'.$ItemData['detail_2'].'</h2>';
				if(!empty($ItemData['detail_6'])) $Content.='<p>'.$ItemData['detail_6'].'</p>';
				if(!empty($ItemData['description'])) $Content.=$ItemData['description'];
				
				$query = "SELECT id,name,price,price_details FROM catalogue WHERE category=${clientCats['Plates']} ORDER BY position_insubcat ASC";
				$result = mysql_query($query);
				if($result && mysql_num_rows($result)>=1){
					$Content .= '<div class="table">';
					$Content .= '<div class="tr" id="titles">';
					$Content .= '<div class="td">Plate</div>';
					$Content .= '<div class="td">Price</div>';
					$Content .= '</div>';
					for($i=0;$i<mysql_num_rows($result);$i++){
						$row = mysql_fetch_array($result);
						if (is_float($i / 2)) {
							$Content .= '<div class="tr_shade">';
						}else{
							$Content .= '<div class="tr">';
						}
						$Content .= '<div class="td">'.$row['name'].'</div>';
						$Content .= '<div class="td">'.$CMSTextFormat->Price_StripDecimal($row['price']).'</div>';
						$Content .= '</div>';
					}
					
					$Content .= '</div>';
				}
				
			$Content .= '</div>';
		$Content .= '</div>'."\r\n";
		
		/*
		$Content .= '<div class="contentRight">';
		$Content .= $Catalogue->PrintSubCats("");
		$Content .= '</div>'."\r\n";
		*/

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>