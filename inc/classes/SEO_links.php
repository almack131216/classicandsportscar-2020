<?php
	
	Class SEO_links {		
		
		////////////////
		// Generate Link
		// makes life easier if all Friendly URLs are generated in one function
		// also useful to turn on/off if on localhost or not PAID FOR seo services
		function GenerateLink($getAttributes){
			global $SEO,$client,$clientCats;
			
			$Friendly = true;
			
			if(is_array($getAttributes)){
				$LinkType = $getAttributes['type']; 
			}else{
				$LinkType = $getAttributes; 
			}
			switch($LinkType){
				case "zip":					
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$ZipFileName = $getAttributes['itemID'].'_'.str_replace(array(" ","&nbsp;"),"_",$getAttributes['name']);						
					}else{
						$SanitizeName = $SEO->sanitize($getAttributes['name']);
						$ZipFileName = $getAttributes['itemID'].'_'.$SanitizeName;
					}
					$pageURL = 'zips/'.$ZipFileName.'.zip';
					break;
					
				case "item":
				case "itemPhotos":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						if($LinkType=="itemPhotos"){
							$pageURL = 'photos.php?uid='.$getAttributes['id'];
						}else{
							$pageURL = $client['itemPage'].'?uid='.$getAttributes['id'];
						}
					}else{
						//echo '?:'.$getAttributes['categoryID'];
						$SanitizeName = $SEO->sanitize($getAttributes['name']);
						//$pageURL = $SanitizeName.'/';
						if(!$getAttributes['categoryID'] || $getAttributes['categoryID']==$clientCats['Classifieds']){						
							if($getAttributes['status']==2){
								$pageCat = 'classic-cars-sold';
							}else{
								$pageCat = 'classic-cars-for-sale';
							}
						}elseif($getAttributes['categoryID']==$clientCats['News']){
							$pageCat = 'news';
						}elseif($getAttributes['categoryID']==$clientCats['Press']){
							$pageCat = 'press';
						}elseif($getAttributes['categoryID']==$clientCats['Testimonials']){
							$pageCat = 'testimonial';
						}
						
						if($LinkType=="itemPhotos"){
							$pageURL = 'photos/'.$SanitizeName;
						}else{
							$pageURL = $SanitizeName.'/'.$pageCat;
						}
						$pageURL .= '/'.$getAttributes['id'];
					}
					break;
					
				case "paginator":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = $_SERVER['PHP_SELF'].'?status='.$getAttributes['status'];
						if($getAttributes['categoryID']) $pageURL .= '&category='.$getAttributes['categoryID'];
						if($getAttributes['subcategoryID']) $pageURL .= '&subcategory='.$getAttributes['subcategoryID'];
					}else{
						
						if($getAttributes['categoryID']==$clientCats['Classifieds']){
							$SanitizeName = $SEO->sanitize($getAttributes['subcategoryName']);
							if($getAttributes['status']==2){
								$pageURL = "classic-car-archive";
								if($getAttributes['subcategoryID']) $pageURL = $SanitizeName."-sold";						
							}else{
								$pageURL = "classic-cars-for-sale";
								if($getAttributes['subcategoryID']) $pageURL = $SanitizeName."-in-our-showroom";
							}
						}elseif($getAttributes['categoryID']==$clientCats['News']){
							$pageURL = 'news';
						}elseif($getAttributes['categoryID']==$clientCats['Press']){
							$pageURL = 'press';
						}elseif($getAttributes['categoryID']==$clientCats['Testimonials']){
							$pageURL = 'testimonials';
						}
					}
					break;
					
				case "paginatorPageNum":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = $getAttributes['page'].'&pg='.$getAttributes['pageNum'];
					}else{
						$pageURL = $getAttributes['page'].'/p'.$getAttributes['pageNum'];
					}
					break;
					
				case "subcategory":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = 'classic-cars-for-sale.php?subcategory='.$getAttributes['id'];
						if($getAttributes['status']) $pageURL .= '&status='.$getAttributes['status'];
					}else{
						if(isset($getAttributes['status']) && $getAttributes['status']==2){
							$AppendURL = "-sold";
						}else{
							$AppendURL = "-in-our-showroom";
						}
						$pageURL = $SEO->sanitize($getAttributes['subcategoryName']).$AppendURL;
					}
					break;
					
				case "contact":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "contact.php";
					}else{
						$pageURL = "contact-details";
					}
					break;
					
				case "contact_map":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "contact.php?map=true";
					}else{
						$pageURL = "malton/google-map";
					}
					break;
					
				case "transport":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "transport.php";
					}else{
						$pageURL = "classic-car-transportation";
					}
					break;

				case "filmTV":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "film-tv-hire.php";
					}else{
						$pageURL = "film-tv-hire";
					}
					break;
					
				case "request":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "request.php";
					}else{
						$pageURL = "request-a-classic-cars";
					}
					break;

				case "about":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "about.php";
					}else{
						$pageURL = "about-classic-and-sportscar-centre";
					}
					break;					
				
				case "plates":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "plates.php";
					}else{
						$pageURL = "registration-numbers";
					}
					break;
					
				
				case "news":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "news.php";
					}else{
						$pageURL = "news";
					}
					break;
					
				case "press":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "press.php";
					}else{
						$pageURL = "press";
					}
					break;
					
				case "testimonials":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "testimonials.php";
					}else{
						$pageURL = "testimonials";
					}
					break;
					
				case "selling":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "selling.php";
					}else{
						$pageURL = "selling-a-classic-car";
					}
					break;
					
				case "classifieds":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "classic-cars-for-sale.php";
					}else{
						$pageURL = "classic-cars-for-sale";
					}
					break;
					
				case "sold":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = "classic-cars-for-sale.php?status=2";
					}else{
						$pageURL = "classic-car-archive";
					}
					break;
					
				case "homepage":
					if($_SERVER['HTTP_HOST']=="localhost" || !$Friendly){
						$pageURL = $client['siteroot'];
					}else{
						$pageURL = "homepage";
					}
					break;
			}
			
			return $pageURL;
		}
		
	}
	$SEO_links = new SEO_links();

?>