<?php
	
	Class CatalogueDetails {
		
		////////////////
		/// Item Details		
		function ItemDetails($getID,$page){
			global $client,$gp_arr_details,$clientCats;
			global $gp_arr_details_cat5,$gp_arr_details_cat7,$gp_arr_details_cat10;
			global $gp_uploadPath;
			global $CMSShared,$CMSTextFormat,$SiteFunctions;
			global $PrintMainPic,$AppendPageTitle,$Images,$Catalogue,$ResultsPage;
			global $SEO,$SEO_links,$OnCarsPage,$OnTestimonialsPage;
			global $Crumbs,$CrumbPages,$currentpage,$ThisPageTitle,$ThisPageKeywords,$ThisPageDescription;
			
			$content = '';
			$ItemQuery = "SELECT c.*,cc.status AS categoryStatus,cc.category AS categoryName,csc.subcategory AS subcategoryName";
			$ItemQuery .= " FROM catalogue as c, catalogue_cats AS cc, catalogue_subcats AS csc WHERE c.category=cc.id AND c.subcategory=csc.id AND c.id=$getID LIMIT 1";


			$ItemResult = mysql_query($ItemQuery);
			if($ItemResult && mysql_num_rows($ItemResult)==1){
				//$imgArray = array();
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
				$my_desc			= $CMSTextFormat->SwapBreak($ItemArray['description']);
				if($my_detail_6){
					$ThisPageDescription = $my_detail_6;
					$my_brief_desc = '<p>'.$ThisPageDescription.'</p>';
				}else{
					$ThisPageDescription = $CMSTextFormat->GetFirstParagraph($ItemArray['description']);
				}
				$ThisPageKeywords = $ItemArray['keywords'];

				//if($my_category!=$clientCats['Testimonials'] && $my_category!=$clientCats['News'] && $my_category!=$clientCats['Press']){
				if($my_category==$clientCats['Classifieds']){
					$OnCarsPage = true;
				}elseif($my_category==$clientCats['Testimonials']){
					$OnTestimonialsPage = true;
				}


				//Crumbs
				if($OnCarsPage){
					$CrumbPages['category']=array();	
					if($ItemArray['status']==1){
						$CrumbPages['category']['title']='Classic Cars For Sale';
						$CrumbPages['category']['href']=$SEO_links->GenerateLink("classifieds");
					}elseif($ItemArray['status']==2){
						$currentpage = "archive";
						$CrumbPages['category']['title']='Classic Cars Sold';
						$CrumbPages['category']['href']=$SEO_links->GenerateLink("sold");
					}
					

					$attributes = array('type'=>'subcategory','id'=>$ItemArray['subcategory'],'subcategoryName'=>$ItemArray['subcategoryName'],'status'=>$ItemArray['status']);
					$CrumbPages['subcategory']=array();	
					$CrumbPages['subcategory']['title']=ucwords($ItemArray['subcategoryName']);
					$CrumbPages['subcategory']['href']=$SEO_links->GenerateLink($attributes);
					$CrumbPages['item']=array();	
					$CrumbPages['item']['title']=$Catalogue->addYearToName($my_detail_1,$my_name);
					$CrumbPages['item']['href']='#';
				}
				
				
				
				//$AppendPageTitle: Append Page Title with item name
				if(!empty($ItemArray['categoryName'])) $AppendPageTitle .= " &#124; ".$ItemArray['categoryName'];
				$AppendPageTitle .= " &#124; ";
				if(!empty($my_detail_1) && $OnCarsPage) $AppendPageTitle .= $my_detail_1." ";
				$AppendPageTitle .= $my_name;
				//END $AppendPageTitle			
				
				$attributes = array('itemID'=>$getID,'width'=>280,'height'=>210,'thumbs'=>true,'delay'=>5000,'fade'=>false,'showlarge'=>true);
				$Slideshow = $Images->SlideshowCMS($attributes);
				
				if($OnCarsPage){
					// IF TESTIMONIALS (ARE ASSIGNED TO THIS ITEM)
					//$query="SELECT id,name,description,upload_date FROM catalogue WHERE category=${clientCats['Testimonials']} AND detail_2 LIKE '%$getID%' LIMIT 1";
					$query="SELECT id,name,description,upload_date FROM catalogue WHERE category=${clientCats['Testimonials']} AND detail_2 IN ($getID) LIMIT 1";
					$result=mysql_query($query);
					
					if($result && mysql_num_rows($result)>=1){
						$Testimonial = '<div class="details">'."\r\n";
						$Testimonial .= '<h2>Testimonial from new owner...</h2>';
												
						//for($tmpcount=0;$tmpcount<=mysql_num_rows($result);$tmpcount++){
							$row = mysql_fetch_assoc($result);
							//$Testimonial .= '<p><strong>'.$row['name'].'</strong>, '.$row['upload_date'].'<br/>';
							$tmpDesc = $CMSTextFormat->stripCrap2_out($row['description']);
							//$tmpDesc = $row['description'];
							$Testimonial .= '<p><em>&quot;'.$CMSTextFormat->ReduceString($tmpDesc,210).'&quot;</em>';
							$Testimonial .= '<br><a href="'.$_SERVER['PHP_SELF'].'?uid='.$row['id'].'" title="'.$row['name'].'">...read FULL testimonial here</a></p>';
						//}
						
						$Testimonial .= '</div>'."\r\n";
					}
				}
			
				//$imgArray[] = array($ItemArray['image_large'],$my_name);
				// PRINT NAME / TITLE
				$buildTitle='<h1>'.$Catalogue->addYearToName($my_detail_1,$my_name).'</h1>';
				if($my_status==2){
					if($categoryID==2){
						$ThisPageTitle.='Archive &#124; ';
					}else{
						$ThisPageTitle.='SOLD &#124; ';
					}
				}else{
					$ThisPageTitle.=$ItemArray['categoryName'].' &#124; ';
				}
				if(!empty($my_detail_1) && $OnCarsPage) $ThisPageTitle.=$my_detail_1.' ';
				$ThisPageTitle.=$my_name;
								
				if(!empty(${"gp_arr_details_cat".$clientCats['Classifieds']})){//CLASSIFIEDS
					$myDetails = ${"gp_arr_details_cat".$clientCats['Classifieds']};
				}else{
					$myDetails = $gp_arr_details;
				}
				
				
				$buildDetails='';
				$buildDetails.='<div class="DetailsBox" id="DetailsBox">';					
					
					if($my_category==$clientCats['News']){
						$OnNewsPage = true;
						$source='<p><strong>Published:</strong> '.$CMSTextFormat->FormatDate($my_date,"");
						if($my_detail_4){
							$source.='&nbsp;&#124;&nbsp;<strong>Source:</strong> ';
							if($CMSTextFormat->StringContains($my_detail_4,"http://")){
								$my_detail_4_title = $my_detail_4;
								if(strlen($my_detail_4)>40) $my_detail_4_title = $CMSTextFormat->ReduceString($my_detail_4,40);
								
								$source.='<a href="'.$my_detail_4.'" title="Link to this story" target="_blank">'.$my_detail_4_title.'</a>';
							}else{
								$source.=$my_detail_4;
							}							
						}
						$source.='</p>';
					}
					if($OnCarsPage){

						if($my_status==2){
							$buildPrice = '<p class="Price"><strong>Price: </strong><span class="price_sold">&pound;SOLD</span></p>';
						}else{
							$buildPrice = '<p class="Price"><strong>Price: </strong>';
							if($my_price) $buildPrice.=$CMSTextFormat->Price_StripDecimal($my_price);
							if(!empty($my_price_details)) $buildPrice.='&nbsp;<span class="price_details">&#40;'.$my_price_details.'&#41;</span>';
							$buildPrice .= '</p>';
						}
						$buildDetails.=$buildPrice;
						//$buildDetails.='<br>';
						//if(!empty($my_detail_1)) $buildDetails .= '<strong>Year: </strong>'.$my_detail_1;
						//if(!empty($my_detail_5)) $buildDetails .= ' &#124; <strong>'.$myDetails[5]['name'] .': </strong>'. $my_detail_5.'<br>';
					
					}elseif($my_category==$clientCats['News'] && !empty($my_detail_2)){
						if($my_category==$clientCats['News']) $buildDetails .= '<p class="Price"><strong>Posted on: </strong>'.$CMSTextFormat->FormatDate($my_date,"").'</p>';

					}elseif($OnTestimonialsPage && !empty($my_detail_2)){
						$MorePhotos = '';
						$MoreLinks='';
						
						$purchased = trim($my_detail_2);//array of id's
						$purchased_arr = explode(",",$purchased);
						$purchased_count = count($purchased_arr);
		
						if($purchased_count>=1){
							$MorePhotos.='<div class="details">'."\r\n";
								$MorePhotos.='<h2>More Photos</h2>';								
								
								for($tmpcount=0;$tmpcount<$purchased_count;$tmpcount++){									
									
									$tmpID=trim($purchased_arr[$tmpcount]);
									$purchased_query = "SELECT id,name,image_large,detail_1 FROM catalogue WHERE category={$clientCats['Classifieds']} AND id=$tmpID";
									$purchased_result = mysql_query($purchased_query);
									
									if($purchased_result && mysql_num_rows($purchased_result)==1){
										$row = mysql_fetch_array($purchased_result);
										$name=$Catalogue->addYearToName($row['detail_1'],$row['name']);
										//Link to archive ads
										
										$MoreLinks.='<li><a href="'.$SEO_links->GenerateLink(array('type'=>'item','id'=>$row['id'],'name'=>$row['name'])).'" title="Archive Ad: '.$name.'">'.$name.'</a></li>';
										
										$MorePhotos.='<a href="'.$gp_uploadPath['large'].$row['image_large'].'" title="'.$name.'" rel="lightbox-journey"><img src="'.$gp_uploadPath['thumbs'].$row['image_large'].'" alt="'.$name.'"></a>'."\r\n"; //LARGE IMAGES
										$more_query="SELECT id,image_large FROM catalogue WHERE id_xtra=$tmpID";
										$more_result=mysql_query($more_query);
										if($more_result && mysql_num_rows($more_result)>=1){
																					
											for($tmpcount_more=0;$tmpcount_more<mysql_num_rows($more_result);$tmpcount_more++){
												$row_more = mysql_fetch_array($more_result);
												$MorePhotos.='<a href="'.$gp_uploadPath['large'].$row_more['image_large'].'" title="'.$name.'" rel="lightbox-journey" style="display:none;"><img src="'.$gp_uploadPath['thumbs'].$row_more['image_large'].'" alt="'.$name.'"></a>'."\r\n"; //(MORE)LARGE IMAGES												
											}
											
										}
									}
									
								}
								//$MorePhotos.='';						
								if($MoreLinks) $MorePhotos.='<p>View original listing:</p><ul>'.$MoreLinks.'</ul>';
								
							$MorePhotos.='</div>'."\r\n";
						}
					}
					
					$buildDetails .= '<ul>'."\r\n";
						if($OnCarsPage) $buildDetails .= '<li><a href="mailto:'.$client['email'].'?subject=Enquiry for '.$my_detail_1.' '.$my_name.' &#40;'.$getID.'&#41;" title="Enquire about this car" class="enquire">Enquire</a></li>';
						$attributes = array('type'=>'itemPhotos','id'=>$getID,'name'=>$my_name,'categoryID'=>$my_category,'status'=>$my_status);
						$buildDetails .= '<li><a href="'.$SEO_links->GenerateLink($attributes).'" title="Show LARGE images" class="slides" id="slides">LARGE images</a></li>'."\r\n";
						$buildDetails .= '<li><a href="#" title="Print this page" class="print" id="printPage"></a></li>'."\r\n";
						$buildDetails .= '<li><a href="#" title="Bookmark this page" class="favorites" id="addtofav"></a></li>'."\r\n";
						$buildDetails .= '<li><a href="#" title="Email this page to a friend" name="'.str_replace("&amp;","and",$client['name']).': A friend has sent you this link..." class="emailfriend" id="emailFriend"></a></li>'."\r\n";
					$buildDetails .= '</ul>';
				
				$buildDetails.='</div>';
				
				// Audio Content
				$AudioContent = '';
				$AudioQuery = "SELECT c.id AS itemID,c.name AS itemName,c.image_large AS itemFile FROM catalogue AS c, WHERE c.id_xtra=$getID AND c.image_large LIKE '%.mp3%' ORDER BY c.position_insubcat ASC";
				$AudioQuery = "SELECT c.id AS itemID,c.name AS itemName,c.image_large AS itemFile";
				$AudioQuery .= " FROM (catalogue c LEFT JOIN tbl_related_subcats rsc on rsc.itemID2=c.id)";
				$AudioQuery .= " WHERE c.image_large LIKE '%.mp3%' AND rsc.itemID=$getID GROUP BY rsc.itemID2 ORDER BY c.position_insubcat ASC";
				
				$AudioResult = mysql_query($AudioQuery);
				if($AudioResult && mysql_num_rows($AudioResult)>=1){			
					$FileNum = 1;
					for($i=0;$i<mysql_num_rows($AudioResult);$i++){
						$row = mysql_fetch_row($AudioResult);
						if(!$CMSTextFormat->StringContains($row[2],"http://")){							
							$AudioFile = $gp_uploadPath['large'].$row[2];
							$DownloadAudioFile = $AudioFile;
						}else{
							$AudioFile = $row[2];
							$DownloadAudioFile = false;
						}
						$AudioTitle = $row[1];
						//if($CMSShared->FileExists($AudioFile)){
							$AudioContent .= '<div class="AudioPlayer">';
							//if($DownloadAudioFile) $AudioContent .= '<h4><a href="'.$SiteFunctions->FileDownloadLink($AudioFile).'&itemID='.$row[0].'" title="Download this audio file" class="download">Download</a></h4>'."\r\n";
							
							$AudioContent .= <<<EOD
<h2>{$AudioTitle}</h2>
<p id="audioplayer_{$FileNum}">{$AudioTitle}</p>
<script type="text/javascript">  
AudioPlayer.embed("audioplayer_{$FileNum}", {
	soundFile: "{$AudioFile}",
	titles: "{$AudioTitle}",  
    artists: "{$itemName}",  
    autostart: "no"
});
</script>
</div>
EOD;
							//}
						$FileNum++;
					}
				}
				/*				
				if($_GET['test']){
					echo $AudioQuery;
				}else{
					$AudioContent = "";
				}
				*/
					
					
					
					
						
													
				//$content .= '<div class="DetailsBoxWrap">';			
					//if($PrintMainPic) $content .= '<div class="PrintMainPic">'.$buildPrice.'<br>'.$PrintMainPic.'</div>';
					$content .= '<div class="DetailsBoxWrapMedia" id="BlueBG">';
						$content .= '<a href="javascript:history.go(-1);" title="Return to previous page" class="BackBut">Back to previous page</a>';
						$content .= $buildPrice;
						if($CMSShared->FileExists($gp_uploadPath['large'].$ItemArray['image_large'])) $content .= $Slideshow;
						if($OnCarsPage) $content .= $Crumbs->BuildCrumbs($CrumbPages);
						if($AudioContent) $content.='<div class="AudioPlayerWrap">'.$AudioContent.'</div>';
					$content .= '</div>';
				//$content .= '</div>';
				
				$content .= '<div class="DetailsBoxWrap">';	
				
				$content .= $buildTitle;
				
				if($source) $content .= $source;
				if($my_brief_desc) $content .= $my_brief_desc;
				$content .= $my_desc;
				if($OnTestimonialsPage && $MorePhotos) $content .= $MorePhotos;
				if($Testimonial) $content .= $Testimonial;
				
				//110212: Search database for this car
				if(($OnTestimonialsPage || $OnNewsPage) && $my_detail_3){
					
					$searchQuery = "SELECT * FROM catalogue WHERE status=1 AND category=2 AND";
					$searchTerms = explode(",",$my_detail_3);
					if(sizeof($searchTerms)>1){
						$searchQuery .= " (";
						for($st=0;$st<sizeof($searchTerms);$st++){
							if($st>0 && $st<sizeof($searchTerms)) $searchQuery .= " OR";
							$searchQuery .= " name LIKE '%".$searchTerms[$st]."%'";
						}
						$searchQuery .= " )";
					}else{
						$searchQuery .= " name LIKE '%{$my_detail_3}%'";
					}
					
					$searchQuery .= " ORDER BY id DESC";
					//$content .= $searchQuery;
					$searchResult = mysql_query($searchQuery);
					
					if($searchResult && mysql_num_rows($searchResult)>=1){
						$numRows = mysql_num_rows($searchResult);
						$inStock='<div class="details">'."\r\n";
						$inStock.='<h2>For sale in our showroom</h2>'."\r\n";
						$inStock.='<ul>';
						for($i=0;$i<$numRows;$i++){
							$row = mysql_fetch_array($searchResult);
							$inStock.='<li>'.$row['detail_1'].' <a href="'.$SEO_links->GenerateLink(array('type'=>'item','id'=>$row['id'],'name'=>$row['name'])).'" title="Link to this '.$row['name'].'">'.$row['name'].'</a></li>';
						}
						$inStock.='</ul>'."\r\n";
						$inStock.='</div>'."\r\n";
						$content .= $inStock;
					}
				}
				
				$content .= $buildDetails;
			
				$content .= '</div>';

				return $content;
				
			}
		}/// END ///
		
		
		//
		// Need this function to populate the slideshow slimbox (hidden images in 'rel'
		function ShowExtraImages($getID,$getID_XTRA){
			global $gp_uploadPath,$CMSShared;
			// LOOK FOR EXTRA IMAGES
			$xtra_query = "SELECT name, image_large FROM catalogue WHERE id_xtra=$getID AND image_large!=''";
			$xtra_query.=" ORDER BY position_initem ASC";		
			
			$XtraImages = '';
			
			$xtra_result = mysql_query($xtra_query);
			if($xtra_result && mysql_num_rows($xtra_result)>=1){
				while($xtraRow = mysql_fetch_array($xtra_result, MYSQL_ASSOC)){
					$xtraName = $xtraRow['name'];
					$xtraImageSRC = $gp_uploadPath['large'].$xtraRow['image_large'];
					if($CMSShared->IsImage($xtraRow['image_large'])) $XtraImages .= '<a href="'.$xtraImageSRC.'" title="'.$xtraName.'" class="hidden" rel="lightbox-journey"><img src="'.$xtraImageSRC.'" alt="'.$xtraName.'"></a>';
				}
			}
			
			return $XtraImages;
		}
		//END
			
	}
	
	$CatalogueDetails = new CatalogueDetails();

?>