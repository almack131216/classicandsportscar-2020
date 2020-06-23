<?php

	Class Press {
	
		////////////////
		/// Item Details		
		function PressItemDetails($getID){
			global $client,$gp_arr_details,$clientCats;
			global $gp_uploadPath;
			global $CMSShared,$CMSTextFormat,$SiteFunctions;
			global $ThisPageTitle,$AppendPageTitle,$Images,$Catalogue,$ResultsPage,$PrevPage,$catid_testimonials;
			
			$content = '';
			//$content .= '<div class="frame_main_featurebox">';			
			
			$ItemQuery = "SELECT c.*,cc.status AS categoryStatus, cc.category AS CategoryName FROM catalogue as c, catalogue_cats AS cc WHERE c.category=cc.id AND c.id=$getID LIMIT 1";

			$ItemResult = mysql_query($ItemQuery);
			if($ItemResult && mysql_num_rows($ItemResult)==1){

				$ItemArray = mysql_fetch_array($ItemResult);
				$my_name		= stripslashes($ItemArray['name']);				
				$my_status		= $ItemArray['status'];
				$my_detail_1	= $ItemArray['detail_1'];
				$my_detail_2	= $ItemArray['detail_2'];
				$my_detail_3	= $ItemArray['detail_3'];
				$my_detail_4	= $ItemArray['detail_4'];
				$my_detail_5	= $ItemArray['detail_5'];
				$my_detail_6	= $ItemArray['detail_6'];
				$my_detail_7	= $ItemArray['detail_7'];
				$my_price		= $ItemArray['price'];
				$my_price_details	= $ItemArray['price_details'];
				$my_category		= $ItemArray['category'];//Category
				$my_subcat			= $ItemArray['subcategory'];//Category
				$my_date			= $ItemArray['upload_date'];//Publication Date
				$my_desc			= $ItemArray['description'];
				
				$imgSRC = $gp_uploadPath['large'].$ItemArray['image_large'];
				
				//Get PDF attached to this item
				$pdfQuery = "SELECT name, image_large FROM catalogue WHERE id_xtra=$getID LIMIT 1";
				$pdfResult = mysql_query($pdfQuery);
				if($pdfResult && mysql_num_rows($pdfResult)==1){
					$pdfRow = mysql_fetch_array($pdfResult);
					$pdfSRC = $gp_uploadPath['large'].$pdfRow['image_large'];
				}
				//(end) Get PDF
				
				//$AppendPageTitle: Append Page Title with item name
				if(!empty($ItemArray['CategoryName'])) $AppendPageTitle .= " &#124; ".$ItemArray['CategoryName'];
				$AppendPageTitle .= " &#124; ";
				if(!empty($my_detail_2)) $AppendPageTitle .= $my_detail_2." &#124; ";
				$AppendPageTitle .= $my_name;
				//END $AppendPageTitle			

			
				// PRINT NAME / TITLE
				$buildTitle='<h1>'.$my_name.'</h1>';
				$buildTitle.='<h2>'.$my_detail_2;
				if(!empty($my_price_details)) $buildTitle.=', '.$CMSTextFormat->FormatDate($my_price_details,"M Y");
				$buildTitle.='</h2>';
				$ThisPageTitle.=$my_name;
				$ThisPageTitle.=' &#124; '.$my_detail_2;
				$ThisPageTitle.=' &#124; '.$ItemArray['CategoryName'];
				
		
				$content .= '<div class="innerPad2">';									
				//$content .= '<div class="DetailsBoxWrap">';
					$PrevPage = "javascript:history.go(-1);";
					$content .= '<p><a href="'.$PrevPage.'" title="Return to previous page" class="BackBut">Return to previous page</a></p>';			
				//$content .= '</div>';
				
				//$content .= '<div class="DetailsBoxWrap">';				
				
				
				$content .= '<div class="PressBox">';
				if($CMSShared->FileExists($imgSRC)){
					//$content .= '<img src="thumb.php?file='.$imgSRC.'&amp;size=165&amp;quality=65&amp;nocache=0" alt="'.$my_name.'">';
					$content .= '<img src="timthumb.php?src='.$imgSRC.'&amp;w=158&amp;q=65" alt="'.$my_name.'">';
				}
				

				if($CMSShared->FileExists($pdfSRC)){
					$filesize = filesize($pdfSRC);					
					$content .= '<div class="PressBoxBut">';
					$content .= '<a class="DownloadFile" href="'.$pdfSRC.'" title="Read this article" target="_blank"><span>Read / Download</span></a>';
					$content .= '<p><a href="'.$pdfSRC.'" title="Read this article" target="_blank">Read / Download</a><br/>'.strtoupper($CMSShared->GetFileType($pdfSRC)).', '.round(($filesize / 1048576), 2).'MB</p>';
					$content .= '</div>';
				}

				
				$content .= '</div>';
				$content .= $buildTitle;
				$content .= $my_desc;
			
				$content .= '</div>';//(end) innerPad2
				//$content .= '</div>';//(end) DetailsBoxWrap

				//$content .= '</div>';//(end) frame_main_featurebox
				return $content;
				
			}
		}/// END ///
			
	}
	
	$Press = new Press();

?>