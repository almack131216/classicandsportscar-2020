<?php

	$PhotoPage = true;
	$currentpage = $_GET['currPage'];
	if(!$currentpage) $currentpage = "catalogue";
	$currentpageSub = "photos";
	$itemID = $_GET['uid'];
	
	
	include("inc/classes/ClientData.php");
	$Content = '';
	include("inc/classes/Catalogue.php");
	include("inc/classes/CatalogueDetails.php");
	include("inc/classes/PageBuild.php");
	if($_SERVER['HTTP_HOST']!="localhost") include("inc/classes/Zip.php");
	if($_SERVER['HTTP_HOST']!="localhost") include("zip.php");
	$PageBuild->AddTags("css/photos.css");
	$PageBuild->AddTags("js/photos.js");
	$PageBuild->AddTags("js/print.js");

	$PageBuild->SetPageKeywords("");
	$PageBuild->SetPageDescription("");	
	
	
	$ItemData = $Catalogue->GetItemData($itemID);
	
	if($ItemData['status']==2) $currentpage = "archive";
	if($ItemData['category']==$clientCats['Testimonials']) $currentpage = "testimonials";
	if($ItemData['category']==$clientCats['News']) $currentpage = "news";

	$itemFullName = $Catalogue->addYearToName($ItemData['detail_1'],$ItemData['name']);
	$PageBuild->SetPageKeywords($ItemData['keywords']);
	$PageBuild->SetPageTitle($itemFullName);
	$Content .= $PageBuild->StartMetaTags();

	$Content .= '<div class="contentbox" id="FullPhotos">';
	
		$Photos = '';

		$Photos .= '<h1>'.$itemFullName.'</h1>';		

		if($ItemData['category']==$clientCats['Classifieds'] && $ItemData['status']==1){
			$Photos.='<p><strong>Price: </strong>';
			if($ItemData['price']) $Photos.=$CMSTextFormat->Price_StripDecimal($ItemData['price']);
			if(!empty($ItemData['price_details'])) $Photos.='&nbsp;<span class="price_details">&#40;'.$ItemData['price_details'].'&#41;</span>';
			$Photos.= '</p>';
		}			

		
		
		$query = "SELECT id,name,image_large FROM catalogue WHERE id=$itemID OR id_xtra=$itemID ORDER BY id ASC";
		$result = mysql_query($query);
		if($result && mysql_num_rows($result)>=1){
			$PhotoTitle = '';
			$PrimaryPhotoTitle = '';
			$files_to_zip = array();
			$ii=0;
			for($i=0;$i<mysql_num_rows($result);$i++){
				$row = mysql_fetch_row($result);
				
				$imgSRC = $gp_uploadPath['large'].$row[2];
				if($CMSShared->FileExists($imgSRC)){
					$ii++;
					$files_to_zip[] = $imgSRC;
					$PhotoID = 'FullPhoto_'.$i;
					if($row[1]){
						$PhotoTitle = $row[1];
						if(!$PrimaryPhotoTitle) $PrimaryPhotoTitle = $PhotoTitle;
					}else{
						$PhotoTitle = $PrimaryPhotoTitle;
					}
					
					if($ii>0 && !is_float($ii/2)) $Photos.= '<div class="PageBreak"><span>Page Break</span></div>';
					$Photos.= '<div class="PhotoBox">';
						$Photos.= '<img src="'.$imgSRC.'" alt="'.$PhotoTitle.'" id="'.$PhotoID.'">';
						$Photos.= '<div class="PhotoBoxFooter">';
							if($row[1]) $Photos.= '<p>'.$PhotoTitle.'</p>';
							$Photos.= '<ul class="PhotoOptions">';
							//$Photos.= '<li><a href="javascript:printImg(\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
							$Photos.= '<li><a href="javascript:printme(\''.$PhotoTitle.'\',\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
							$Photos.= '<li><a href="'.$SiteFunctions->FileDownloadLink($gp_uploadPath['large'].$row[2]).'" class="disk" title="Download - Save this photo">Save Photo</a></li>';
							$Photos.= '</ul>';
						$Photos.= '</div>';
					

						$Photos .= '<noscript><p class="noscript">You have JavaScript turned off. So the \'Print Photo\' feature will not work.</p></noscript>';

					$Photos.= '</div>';
					if($ii==1) $Photos.= '<div class="PrintHidden"><p>&nbsp;</p>'.$ContactDetails->PrintContactDetails("footer").'</div>';
				}
				
			}
		}
		
		$PageOptions = '<div class="PhotoBox">';		
		$PageOptions.= '<ul>';
		
		$PrevPage = "javascript:history.go(-1);";
		$PageOptions .= '<li><a href="'.$PrevPage.'" title="Return to previous page" class="BackBut">Back</a></li>';
		
		if($_SERVER['HTTP_HOST']!="localhost"){			

			$tmpName = trim($Catalogue->addYearToName($ItemData['detail_1'],$ItemData['name']));

			$ZipSRC = $SEO_links->GenerateLink(array('type'=>'zip','name'=>$tmpName,'itemID'=>$itemID));
			//echo $ZipSRC;
			//if true, good; if false, zip creation failed	
			//print_r($files_to_zip);
			
			$result = $ZipImages->create_zip($files_to_zip,$ZipSRC);
			$PageOptions.= '<li><a href="force-download.php?file='.$ZipSRC.'" class="disk" title="Download - Save ALL Photos">Save ALL Photos to zip file</a></li>';
		}
		$PageOptions.= '<li><a href="JavaScript:window.print();" class="print" title="Print ALL Photos">Print ALL Photos</a></li>';
		$PageOptions.= '</ul>';
		$PageOptions.= '</div>';
		
		$Content .= $PageOptions;
		$Content .= $Photos;
		$Content .= $PageOptions;
		
	$Content .= '</div>'."\r\n";
	
	
	
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>