<?php
	
	Class Catalogue {		
		
		//////////////////////////
		/// FUNCION // PRINT ITEMS
		function ItemPreview($getAttributes){
			global $MainKeywordsBuild,$MainDescriptionBuild,$qs_keywordsArr,$SEO,$SEO_links,$Images,$currentpage,$AppendPageTitle,$Catalogue,$client,$clientCats,$SiteFunctions,$CMSShared,$gp_uploadPath,$CMSTextFormat,$Reviews;
			
			if(is_array($getAttributes['itemArray'])){
				$getItemArray = $getAttributes['itemArray'];
			}else{
				$getArrayQuery = "SELECT * FROM catalogue WHERE id=${getAttributes['itemArray']} LIMIT 1";
				$getArrayResult = mysql_query($getArrayQuery);
				if($getArrayResult && mysql_num_rows($getArrayResult)==1) $getItemArray = mysql_fetch_array($getArrayResult);
			}
			
			$getStyle = $getAttributes['itemStyle'];
			$getClass = $getAttributes['itemClass'];
			$itemPage = $getAttributes['itemPage'];
			$show_rating = $getAttributes['show_rating'];
			
			$ItemImage = $gp_uploadPath['primary'].$getItemArray['image_large'];
			if(!$CMSShared->FileExists($ItemImage) && $getStyle!="ss") $ItemImage = $gp_uploadPath['primary'].$client['missingimage'];
			
			$AppendedPrice = $CMSTextFormat->Price_StripDecimal($getItemArray['price']);
			if(empty($getItemArray['price']) && strtolower($getItemArray['price_details'])=="poa") $AppendedPrice = '&pound;POA';
			
			$attributes = array('type'=>'item','id'=>$getItemArray['id'],'name'=>$getItemArray['name'],'categoryID'=>$getItemArray['category'],'categoryName'=>$getItemArray['categoryName'],'subcategoryName'=>$getItemArray['subcategoryName'],'status'=>$getItemArray['status']);
			$AppendedUrl = $SEO_links->GenerateLink($attributes);
			if($show_rating) $AppendedUrl = "";

			//for more information...
			$AppendedLinkTitle = 'Link to '.$getItemArray['name'];
			if($getItemArray['status']==1) $AppendedLinkTitle .= ' for sale';

			$AppendPageTitle .= " &#124; ".$getItemArray['name'];
			
			$SiteRoot = $client['siteroot'];//ServerSpecific
			$content = '';
			$MainKeywordsBuild .=', '.$getItemArray['name'];
			
			if(empty($MainDescriptionBuild)){
				$MainDescriptionBuild .= $getItemArray['name'];
			}else{
				$MainDescriptionBuild .=', '.$getItemArray['name'];
			}
			
			// Style : Grid
			if($getStyle=="li"){
				$content .= '<li>';
					$content.='<div class="imgArea"><a href="'.$AppendedUrl.'" title="'.$AppendedLinkTitle.'">';
						//echo '<br><img src="'.$SiteRoot.$gp_uploadPath['primary'].$getItemArray['image_small'].'" width="100" height="30">'.$gp_uploadPath['thumbs'].$getItemArray['image_large'];
						$imgFileName = $getItemArray['image_large'];
						if(!empty($getItemArray['image_small']) && $getItemArray['image_small']!=$getItemArray['image_large']) $imgFileName = $getItemArray['image_small'];
						$attributes = array('filename'=>$imgFileName,'size'=>"primary",'alt'=>$getItemArray['name']);
						$content.=$Images->ScaleImage($attributes);
					$content.='</a></div>'."\r\n";
					$content.='<div class="textArea">';
					$content.='<h2><a href="'.$AppendedUrl.'" title="'.$AppendedLinkTitle.'">'.$CMSTextFormat->ReduceString(stripslashes($getItemArray['name']),100).'</a></h2>';
					if($getItemArray['status']==1){
						$content.='<p>';
						if(!empty($getItemArray['price'])) $content.=$CMSTextFormat->Price_StripDecimal($getItemArray['price']);
						if($getItemArray['price_details']) $content.=' <span class="price_details">'.$getItemArray['price_details'].'</span>';
						$content.='</p>'."\r\n";
					}
					$content.='</div>'."\r\n";
				$content .= '</li>';
			
			// Style : row
			}elseif($getStyle=="row"){
				$content.='<li class="itemRow">';
					$content.='<div class="imgArea">';
						if($AppendedUrl) $content.='<a href="'.$AppendedUrl.'" title="'.$AppendedLinkTitle.'">';
						$attributes = array('filename'=>$getItemArray['image_large'],'size'=>"primary",'alt'=>$getItemArray['name'],'class'=>"primary");
						$content.=$Images->ScaleImage($attributes);
						if($AppendedUrl) $content.='</a>';			
					$content.='</div>';
					$content.='<div class="textArea">';
						$content.='<h2>';
						if($AppendedUrl) $content.='<a href="'.$AppendedUrl.'" title="'.$AppendedLinkTitle.'">';
						$content.=$CMSTextFormat->highlightWords($getItemArray['name'],$qs_keywordsArr);
						//if(!empty($AppendedPrice)) $content.=' &#124; '.$AppendedPrice;
						if($AppendedUrl) $content.='</a>';
						$content.='</h2>';
						
						//Source (press)
						if($getItemArray['category']==$clientCats['Press']){
							$content.='<h3>Source: '.$getItemArray['detail_2'];
							if(!empty($getItemArray['price_details'])) $content.=', '.$CMSTextFormat->FormatDate($getItemArray['price_details'],"F Y");
							$content.='</h3>';
						}elseif($getItemArray['category']==$clientCats['News']){
							$content.='<h3>Published: '.$CMSTextFormat->FormatDate($getItemArray['upload_date'],"F Y").'</h3>';
						}			
						
						if(!empty($getItemArray['detail_6'])){
							$description='<p>'.$getItemArray['detail_6'].'</p>';
						}else{
							//$attributes = array('string'=>$getItemArray['description'],'trim_start'=>20,'trim_middle'=>"...");
							//$description=$CMSTextFormat->Abbreviate($attributes);
							$description=$Catalogue->MakeTeaser($getItemArray['description']);							
						}
						if($description) $content.= $CMSTextFormat->highlightWords($description,$qs_keywordsArr);					
						//$content.='<a href="'.$AppendedUrl.'" title="'.$AppendedLinkTitle.'" class="NextBut">View details</a>';
					$content.='</div>';
				
				$content.='</li>';
			}
			
			return $content;
		}
		/// END ///
		
		// Condense description
		function MakeTeaser($getDescription){
			global $CMSTextFormat;
			//echo ':'.strpos($getDescription,"<p>");
			if($CMSTextFormat->StringContains($getDescription,"<p>")){
				$getDescription = str_replace(array("<br>","<br/>","<br />")," ",$getDescription);
				return $CMSTextFormat->GetFirstParagraph($getDescription);
			}else{
				$getDescription = strip_tags($getDescription);
				return '<p>'.$getDescription.'...</p>';
			}			
		}
		// (END)

		
		
		function PrintNewsBox(){
			global $SEO,$client,$gp_uploadPath,$clientCats,$CMSShared,$CMSTextFormat;
			
			$NewsQuery = "SELECT * FROM catalogue WHERE category=${clientCats['Press']} ORDER BY upload_date DESC LIMIT 1";
			$NewsResult = mysql_query($NewsQuery);
			if($NewsResult && mysql_num_rows($NewsResult)>=1){
				$NewsRow = mysql_fetch_array($NewsResult);

				$attributes = array('type'=>'item','id'=>$NewsRow['id'],'name'=>$NewsRow['name'],'categoryID'=>$NewsRow['category']);
				$NewsLink = $SEO_links->GenerateLink($attributes);
				$NewsLinkTag = '<a href="'.$NewsLink.'" title="Link to story: '.$NewsRow['name'].'">';
				
				$LatestNewsBox = '<div class="PreviewBox" id="NewsBox">';
				$LatestNewsBox .= '<h1>Car Magazine News</h1>';
				
				$NewsThumb = $gp_uploadPath['large'].$NewsRow['image_large'];
				if($CMSShared->FileExists($NewsThumb)){
					$LatestNewsBox .= $NewsLinkTag.'<img src="'.$NewsThumb.'" alt="'.$NewsRow['name'].'" width="160px;" height="220px" id="PressPage"></a>';
				}
				//$LatestNewsBox .= '<p>We have added more clippings from newspapers and motoring journals featuring the story of how we came to discover the incredible '.$NewsLinkTag.'Mercedes Benz 600k Special Roadster</a> on our Press &amp; Media page...</p>';
				$LatestNewsBox .= '<h2>'.$NewsLinkTag.$NewsRow['name'].'</a></h2>';
				$LatestNewsBox .= $NewsRow['description'];
				$LatestNewsBox .= '<p>Published: '.$CMSTextFormat->FormatDate($NewsRow['upload_date'],"").'</p>';
				$LatestNewsBox .= '</div>';
				
				return $LatestNewsBox;
			}
		}
		
		/////////////////
		/// Get Item Data (return ALL or selected fields only)
		function GetItemData($getAttributes){			
			
			if(is_array($getAttributes)){
				$getID = $getAttributes['id'];
				$getTable = $getAttributes['table'];
				$getField = $getAttributes['field'];
			}else{
				$getID = $getAttributes;
			}

			if(empty($getTable)) $getTable = 'catalogue';
			if(empty($getField)) $getField = '*';
			
			
			$getQuery = "SELECT $getField FROM $getTable WHERE id=$getID LIMIT 1";
			$getResult = mysql_query($getQuery);
			if($getResult && mysql_num_rows($getResult)==1){
				if($getField!="*"){
					$getArray = mysql_fetch_row($getResult);
					return $getArray[0];
				}else{
					$getArray = mysql_fetch_array($getResult);
					return $getArray;
				}
			}
		}
		/// END ///
		
		

		
		function PrintSubCats($getAttributes){
			global $SEO,$SEO_links,$client,$clientCats,$CMSTextFormat,$SiteFunctions,$CMSShared,$gp_uploadPath;		
			
			$getTitleString = "Classic Cars For Sale";
			$getQuery="select csc.id AS subcategoryID,csc.subcategory AS subcategoryName, count(*) AS subcategoryCount";
			$getQuery.=" from (catalogue_subcats csc LEFT JOIN catalogue c on csc.id = c.subcategory)";
			$getQuery.=" WHERE csc.category=${clientCats['Classifieds']} AND csc.status=1";
			if(!empty($getAttributes['status'])){
				$hackStatus = $getAttributes['status'];
				$getQuery.=" AND c.status=${getAttributes['status']}";
				$AppendTitle = "Sold";
			}else{
				$hackStatus = 1;
				$getQuery.=" AND c.status=1";
				$AppendTitle = "For Sale";
			}
			if(!empty($getAttributes['titleHref'])){
				$titleHref=$getAttributes['titleHref'];
			}else{
				$titleHref="classic-cars-for-sale";
			}
			$getQuery.=" AND c.category=${clientCats['Classifieds']} AND c.subcategory=csc.id";
			$getQuery.=" GROUP BY csc.id ORDER BY lower(csc.subcategory)";//subcategoryCount DESC
			
			$getTitleString = "";
			if(!empty($getAttributes['title'])) $getTitleString = $getAttributes['title'];
			if(!empty($getAttributes['query'])) $getQuery = $getAttributes['query'];
						
			$scData = '';
			$StockCount = 0;
			$scResult = mysql_query($getQuery);
			if($scResult && mysql_num_rows($scResult)>=1){
				$scData .= '<ul>';
				for($i=0;$i<mysql_num_rows($scResult);$i++){
					if($scArray = mysql_fetch_array($scResult)){
						$thisID = $scArray['id'];
						
						$attributes = array('type'=>'subcategory','id'=>$scArray['subcategoryID'],'subcategoryName'=>$scArray['subcategoryName'],'status'=>$hackStatus);
						$SubcatLink = $SEO_links->GenerateLink($attributes);
						
						$thisLinkTag = '<a href="'.$SubcatLink.'" title="Link to '.ucfirst($scArray['subcategoryName']).' Classic Cars '.$AppendTitle.'">'.ucfirst($scArray['subcategoryName']).'</a> ('.$scArray['subcategoryCount'].')';
						$scData .= '<li>'.$thisLinkTag.'</li>';
						$StockCount+=$scArray['subcategoryCount'];
					}
				}
				$scData .= '</ul>';
			}
			
			$scBox.='<div class="featureBox">'."\r\n";
			if($getTitleString){
				$scBox.='<h2>';
				if($StockCount && !$hackStatus) $scBox.=$StockCount.'&nbsp;';
				$scBox.='<a href="'.$titleHref.'" title="Link to '.$getTitleString.' pages">'.$getTitleString.'</a></h2>'."\r\n";
			}

			$scBox.=$scData;
			$scBox.='</div>';
					
			return $scBox;
		}
		/// END ///
		
		
		function SwitchCurrPage($getCurrPage){
			global $client,$clientCats,$currentpage;
						
			switch($getCurrPage){
					
				case "services":
					$tmpCatNum=$clientCats['Services'];
					$tmpPageTitle="Services";
					break;
					
				default:
					$currentpage = "services";
					$tmpCatNum=$clientCats['Services'];
					$tmpPageTitle="Services";
					break;
			}
			
			$currPageArr = array('categoryID'=>$tmpCatNum,'PageTitle'=>$tmpPageTitle);
			return $currPageArr;
			
		}
		//END//
		
		
		function addYearToName($getYear,$getName){
			global $currentpage;
			if(empty($getYear) || $currentpage=="testimonials" || $currentpage=="news"){
				$getYear = '';
			}else{
				$getYear.=' ';
			}
			return $getYear.$getName;
		}
	
		function isOLD($getDate){
			if($getDate<="2007-11-29") return true;
			false;
		}
			
	}
	
	$Catalogue = new Catalogue();

?>