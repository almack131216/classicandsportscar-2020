<?php
	
	Class Featured {		
		
		//////////////////////////
		/// FUNCION // PRINT ITEMS
		function PrintFeatured($getAttributes){
			global $SEO,$SEO_links,$client,$clientCats,$CMSTextFormat,$SiteFunctions,$CMSShared,$gp_uploadPath;		
			
			$getTitleString = "Classic Cars For Sale...";
			$getStatus = 1;
			if($getAttributes['status']){
				$getStatus = $getAttributes['status'];
			}else{
				$getStatus = 1;
			}

			$getQuery = "SELECT * FROM catalogue";
			$getQuery .= " WHERE id_xtra=0 AND category=${clientCats['Classifieds']}";			
			$getQuery .= " AND status!=0 AND status=$getStatus";
			$getQuery .= " ORDER BY upload_date DESC";
			$getResult = mysql_query($getQuery);
			if($getResult) $StockCount = mysql_num_rows($getResult);

			if($getAttributes['title']) $getTitleString = $getAttributes['title'];
			if($getAttributes['query']){
				$CustomQuery=true;
				$getQuery = $getAttributes['query'];
			}
			
			if($getAttributes['limit']){
				$getLimit = $getAttributes['limit'];
			}else{
				$getLimit = 10;
			}
			
			$FeaturedBox = '';
			$FeaturedData = '';
			
			$itemQuery = $getQuery;
			if($getLimit) $itemQuery.=" LIMIT $getLimit";
			$itemResult = mysql_query($itemQuery);
			if($itemResult && mysql_num_rows($itemResult)>=1){
				
				for($i=0;$i<mysql_num_rows($itemResult);$i++){
					if($itemArray = mysql_fetch_array($itemResult)){
						$thisID = $itemArray['id'];
						$thisTitle = $itemArray['name'];
						$thisDesc = strip_tags($itemArray['description']);
						$thisThumb = $gp_uploadPath['thumbs'].$itemArray['image_large'];
						$thisLinkTitle = $itemArray['name'];
						if($itemArray['category']==$clientCats['Links']){
							$thisLink = $itemArray['detail_4'];
							if($CMSShared->ExternalLink($thisLink,$client['domain'])){
								$thisLink.='" target="_blank';
							}
							$thisLinkTitle.=" website";
						}else{
							$attributes = array('type'=>'item','id'=>$thisID,'name'=>$itemArray['name'],'categoryID'=>$itemArray['category']);
							$thisLink = $SEO_links->GenerateLink($attributes);
						}
						
						
	
						$FeaturedData.='<div class="featured">'."\r\n";
						if(!$CMSShared->FileExists($thisThumb)) $thisThumb = $gp_uploadPath['thumbs'].$client['missingimage'];
						$FeaturedData.='<div class="featured_left">';
							$FeaturedData.='<a href="'.$thisLink.'" title="Link to '.$thisLinkTitle.'"><img src="'.$thisThumb.'" alt="'.$thisTitle.'"></a>';
						$FeaturedData.='</div>';
						$FeaturedData.='<div class="featured_right">';
						$thisDesc = $CMSTextFormat->ReduceString($thisDesc,65);

						$FeaturedData.='<p><a href="'.$thisLink.'" title="Link to '.$thisLinkTitle.'">'.$thisTitle.'</a>';
						if($itemArray['status']==1){
							if($itemArray['price'] || $itemArray['price_details']){
								$FeaturedData.='<br>';
								if(!empty($itemArray['price'])) $FeaturedData.='<strong>'.$CMSTextFormat->Price_StripDecimal($itemArray['price']).'</strong>';
								if($itemArray['price_details']) $FeaturedData.=' <span class="price_details">'.$itemArray['price_details'].'</span>';
							}
						}else{
							$FeaturedData.='<br><strong>Sold:</strong> '.$CMSTextFormat->FormatDate($itemArray['upload_date'],"d/m/y");
						}
						$FeaturedData.='</p>';
						//if(!empty($itemArray['price_details'])) $FeaturedData.=' <span class="priceNote">'.$itemArray['price_details'].'</span>';
						//$FeaturedData.='<br><a href="'.$thisLink.'" title="View Details" class="NextBut">View Details</a></p>';
						$FeaturedData.='</div>';
						$FeaturedData.='</div>'."\r\n";					
					}
				}

				$FeaturedBox.='<div class="featureBox">'."\r\n";
				$FeaturedBox.='<h2>';
				if($getStatus==1 && $StockCount && !$CustomQuery){
					$FeaturedBox.=$StockCount.'&nbsp;';
				}elseif($getStatus==2 && $getLimit){
					$FeaturedBox.=$getLimit.'&nbsp;';
				}
				
				$FeaturedBox.='<a href="classic-cars-for-sale" title="Link to Classic Cars For Sale">'.$getTitleString.'</a></h2>'."\r\n";
					$FeaturedBox.=$FeaturedData;
				$FeaturedBox.='</div>';
				
			}			
			return $FeaturedBox;
		}
		/// END ///
		
		function HomepageRow(){
			global $SEO,$client,$Catalogue,$clientCats,$CMSTextFormat,$SiteFunctions,$CMSShared,$gp_uploadPath;
			
			$FeaturedRow = '<div class="contentbox" id="featuredRow">';
				$FeaturedRow .= '<h1>Latest Cars for Sale at '.$client['name'].', Malton, North Yorkshire</h1>'."\r\n";
				
				$query = "SELECT c.* FROM catalogue AS c WHERE c.status=1 AND c.category=${clientCats['Classifieds']} ORDER BY c.upload_date DESC LIMIT 5";
				$result = mysql_query($query);
				if($result && mysql_num_rows($result)>=1){
					$FeaturedRow .= '<ul class="itemBox">';
					for($i=0;$i<mysql_num_rows($result);$i++){
						$row = mysql_fetch_array($result);
						$attributes = array('itemArray'=>$row,'itemStyle'=>"li",'itemClass'=>$itemClass,'itemPage'=>$client['itemPage']);
						$FeaturedRow .= $Catalogue->ItemPreview($attributes);
					}
					$FeaturedRow .= '</ul>'."\r\n";
				}
				
			$FeaturedRow .= '</div>'."\r\n";
			
			return $FeaturedRow;
		}
			
	}
	
	$Featured = new Featured();

?>