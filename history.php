<?php
	$currentpage = "about";
	$Content = '';
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");
	include("inc/classes/Images.php");
	include("inc/classes/Accordion.php");
	$PageBuild->AddLightBox();
	$Accordion->AddAccordion();
	
	//$PageBuild->AddTags("css/slideshow2.css");
	
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/tables.css");
	
	$ItemData = $Catalogue->GetItemData(10885);
	
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
				
				echo $Content;

				$RowNum = 0;				
				$query = "SELECT * FROM catalogue WHERE category=10 ORDER BY position_insubcat ASC";
				$result = mysqli_query($dbc, $query);
				if($result && mysqli_num_rows($result)>1){
					
					$AccordionContent = <<<EOD
			<div id="container">

<div class="heading">
	<a id="expand-all" href="#" title="Expand all" class="expand">expand all</a>&nbsp;<a id="collapse-all" href="#" title="Collapse all" class="collapse">collapse all</a>
</div>

<ul id="vertical" class="simple"><ul>
EOD;

					for($i=0;$i<mysqli_num_rows($result);$i++){
						$RowNum++;
						$row = mysqli_fetch_array($result);
						$AccordionContent .= '<li>';
						$ExpandTitle = $row['name'];
						$itemDate = $row['upload_date'];
						if($row['price_details']) $itemDate = $row['price_details'];
						$AccordionContent .= '<h3>'.$CMSTextFormat->FormatDate($itemDate,"M Y").' <a name="'.$RowNum.'" href="#'.$RowNum.'">'.$ExpandTitle.'</a> <acronym class="expand" title="Click to Expand / Collapse">&nbsp;</acronym></h3>';
						$AccordionContent .= '<div class="collapse">';
						$AccordionContent .= '<div class="collapse-container">';
						//$attributes = array('itemArray'=>$row,'itemStyle'=>"row",'itemPage'=>$itemPage);
						//$AccordionContent .= $Catalogue->ItemPreview($attributes);
						$imgALT = $row['name'];
						$AccordionContent .= '<a href="'.$gp_uploadPath['large'].$row['image_large'].'" title="'.$imgALT.'" rel="lightbox-journey" class="enlarge"><img src="'.$gp_uploadPath['primary'].$row['image_large'].'" class="BorderMe" alt="'.$imgALT.'"></a>';
						//$AccordionContent .= '<img src="'.$gp_uploadPath['primary'].$row['image_large'].'" class="BorderMe" alt="'.$imgALT.'">';
						if($row['detail_6']) $AccordionContent .= '<p><strong>'.$row['detail_6'].'</strong></p>';
						$AccordionContent .= $row['description'];
						
						$xtraQuery = "SELECT id,name,image_large,description FROM catalogue WHERE id_xtra=".$row['id']." ORDER BY position_initem ASC";
						$xtraResult = mysqli_query($dbc, $xtraQuery);
						if($xtraResult && mysqli_num_rows($xtraResult)>=1){
							
							for($ii=0;$ii<mysqli_num_rows($xtraResult);$ii++){
								$xtraRow = mysqli_fetch_array($xtraResult);
								
								$AccordionContent .= '<div class="collapse-container_xtra">';
								$AccordionContent .= '<a href="'.$gp_uploadPath['large'].$xtraRow['image_large'].'" title="'.$xtraRow['name'].'" rel="lightbox-journey" class="enlarge"><img src="'.$gp_uploadPath['primary'].$xtraRow['image_large'].'" class="BorderMe" alt="'.$xtraRow['name'].'"></a>';
								//$AccordionContent .= '<img src="'.$gp_uploadPath['primary'].$row['image_large'].'" class="BorderMe" alt="'.$imgALT.'">';
								if($xtraRow['name']) $AccordionContent .= '<p><strong>'.$xtraRow['name'].'</strong></p>';
								$AccordionContent .= $xtraRow['description'];
								$AccordionContent .= '</div>';
							}
						}
						
						$AccordionContent .= '</div>';
						$AccordionContent .= '</div>';
						$AccordionContent .= '</li>';
					}
				}
				$AccordionContent .= '</ul>';
				$AccordionContent .= '</ul></div>'."\r\n";
				
				if($AccordionContent) echo $AccordionContent;
				
			$Content = '</div>';
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