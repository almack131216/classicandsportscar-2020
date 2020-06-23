<?php
					
	Class Images {
		
		/////////////
		/// ImageBox
		function ImageBox($getID){
			global $dbc;
			global $gp_uploadPath;
			
			$query = "SELECT id,name,image_large,image_small FROM catalogue WHERE id=$getID LIMIT 1";
			$result = mysqli_query($dbc, $query);
			if($result && mysqli_num_rows($result)==1){
				$array = mysqli_fetch_array($result);
				$title = $array['name'];
				$image = $array['image_large'];
				//if(!empty($array['image_small']) && $array['image_small']!=$array['image_large']) $image = $array['image_small'];
				$imageSRC = $gp_uploadPath['large'].$image;

				if(!empty($image) && file_exists($imageSRC)){
					$PageData.='<div id="ImageBox">';				
						
						$PageData.='<div id="imageGallery">';
							$PageData.='<img src="'.$imageSRC.'" id="placeholder" alt="'.$title.'" width="360" height="270" class="BorderTop">';
							$PageData.='<p id="caption">'.$title.'</p>';
						$PageData.='</div>';				
						
		
						$thumb_query = "SELECT id, name, image_large, image_small description FROM catalogue WHERE id=$getID OR id_xtra=$getID ORDER BY position_initem ASC";//id=$getID OR 
						$thumb_result = mysqli_query($dbc, $thumb_query);
						if($thumb_result && mysqli_num_rows($thumb_result)>1){
							$PageData.='<ul id="thumbs">';
							for($thumbcount=0;$thumbcount<mysqli_num_rows($thumb_result);$thumbcount++){
								$thumb_array = mysqli_fetch_array($thumb_result);
								$thumb_name = $title;
								if(!empty($thumb_array['name'])) $thumb_name = $thumb_array['name'];
								if(empty($thumb_array['name']) && !empty($thumb_array['description'])) $thumb_name = strip_tags($thumb_array['description']);
								$thumb_imageSRC = $gp_uploadPath['thumbs'].$thumb_array['image_large'];
								$thumb_imageSRC_large = $gp_uploadPath['large'].$thumb_array['image_large'];
								if(file_exists($thumb_imageSRC)){
									$PageData.='<li><img src="'.$thumb_imageSRC.'" name="'.$thumb_imageSRC_large.'"  alt="'.$thumb_name.'" class="NewsPicThumb"></li>';
									//echo '<li><img src="" name="'.$filesrc.'" width="70px" height="50px" alt="'.$imageList[$getChalet][$tmpcount][1].'" title="'.$imageList[$getChalet][$tmpcount][1].'">';
								}
							}
							$PageData.='</ul>';
						}
						
						
					$PageData.='</div>';
					
				}else{
					//$PageData.='<img src="'.$gp_uploadPath['large'].$client['missingimage'].'" width="360" height="270" id="placeholder" alt="'.$title.'">';
				}//IF IMAGE EXISTS
			}//IF RESULT FOUND
			return $PageData;
		}
		/// END ///		
		
		
		function ReturnImage($getFileDir,$getFileName){
			global $CMSShared,$client;
			
			$imgSRC = $getFileDir.$getFileName;
			if($CMSShared->FileExists($imgSRC)){
				return $imgSRC;
			}else{
				return $getFileDir.$client['missingimage'];
			}			
		}
		
		function IsPortrait($getSRC){
			$tmp = getimagesize($getSRC);
			if($tmp[1]>$tmp[0]) return true;
			return false;
		}
		// END //
		
		function IsPortraitSmall($getSRC){
			$tmp = getimagesize($getSRC);
			if($tmp[1]>$tmp[0] && $tmp[0]<=160) return true;
			return false;
		}
		// END //
		
		function NoResizeNeeded($getSRC){
			global $gp_primary_width,$gp_primary_height;
			
			$tmp = getimagesize($getSRC);
			if($tmp[0]==$gp_primary_width && $tmp[1]==$gp_primary_height) return true;
			return false;
		}
		// END //
		
		
		////////////////////////////////////////////////////
		/// FUNCTION: Scale image (directory,filename.jpg,"primary","Alt text")
		/// $getImgDir,$getImgFilename,$getSize,$getAlt,$getClass
		function ScaleImage($getAttributes){
			global $currentpage,$client,$Images,$gp_uploadPath,$CMSShared;
			global $gp_large_width,$gp_large_height,$gp_primary_width,$gp_primary_height;
			

			$getImgFilename = $getAttributes['filename'];
			$getSize = $getAttributes['size'];
			$getAlt = $getAttributes['alt'];
			$getClass = $getAttributes['class'];
			$getID = $getAttributes['id'];
			$getRel = $getAttributes['rel'];
			//
			
			
			/////////////////////////////////////////////////
			/// Set required dimensions depending on $getSize
			switch($getSize){
				case "primary":
					$getImgSrc = $gp_uploadPath['primary'].$getImgFilename;
					$priority = "width";//this ensures we scale by width REGARDLESS of whether image is portrait or landscape
					if($currentpage=="home"){
						$width_max	= 140;
						$height_max	= 105;
					}elseif($currentpage=="press" || $currentpage=="news" || $currentpage=="testimonials"){
						$width_max	= 140;
						$height_max	= 105;
						$priority = "height";
					}else{
						$width_max	= 160;
						$height_max	= 120;
					}
					
					break;
					
				case "large":
					$getImgSrc = $gp_uploadPath['large'].$getImgFilename;
					$width_max	= 360;//$gp_large_width
					$height_max	= 270;//$gp_large_height
					$priority = "height";
					break;
					
				case "homepage":
					$getImgSrc = $gp_uploadPath['large'].$getImgFilename;
					$width_max	= 580;//$gp_large_width
					$height_max	= 270;//$gp_large_height
					$priority = "width";
					break;
					
				default:
					$width_max	= $gp_primary_width;
					$height_max	= $gp_primary_height;
					$priority = "";
					break;
			}
				
			// get original (ACTUAL) dimensions
			//$FileMissing=false;
			if (!$CMSShared->FileExists($getImgSrc) || empty($getImgFilename)) {
				switch($getSize){
					case "primary": $getImgSrc = $gp_uploadPath['primary'].$client['missingimage'];break;
					case "large":	$getImgSrc = $gp_uploadPath['large'].$client['missingimage'];$getSize="";break;
				}
				$FileMissing=true;				
			}		
			
			/// IF FILE EXISTS & SIZE IS ASSIGNED
			if(!empty($getSize)){// && !$FileMissing
				$tmp = getimagesize($getImgSrc);
				$width_tmp  = $tmp[0];
				$height_tmp = $tmp[1];
				
				if($priority=="absolute"){
					$width_abs = $width_max;
					$height_abs = $height_max;
				}else{
					if(($height_tmp >= $width_tmp || $priority=="height") && $priority!="width") {
						//echo '<br/>SCALE BY HEIGHT<br/>';
						$tmp = number_format(($height_tmp / $width_tmp), 3);			
						if ($height_tmp >= $height_max) {
							$height_abs = $height_max;
						} else {
							$height_abs = $height_tmp;
						}			
						$width_abs = number_format(($height_abs / $tmp), 0);
						//echo '<br/>(FB)2:'.$width_abs.' / '.$height_abs;
					} else if(($width_tmp >= $height_tmp || $priority=="width") && $priority!="height") {
						
						//echo '<br/>SCALE BY WIDTH<br/>';
						$tmp = number_format(($width_tmp / $height_tmp), 3);			
						if ($width_tmp >= $width_max) {
							$width_abs = $width_max;	
						} else {
							$width_abs = $width_tmp;														
						}			
						$height_abs = number_format(($width_abs / $tmp), 0);
						//echo '<br/>(FB)3:'.$width_max.' / '.$height_abs.' ('.$tmp.')';
					}
					/// END OF SHRINK TO FIT ///
				}
				
				//echo '<br/>exists'.$width_abs.' / '.$height_abs.' ('.$tmp.')';
				//echo '<br/>exists'.$width_max.' / '.$height_max.' ('.$tmp.')';
				$imgPropsArray = array('src'=>$getImgSrc,'width'=>$width_abs,'height'=>$height_abs,'alt'=>$getAlt,'class'=>$getClass,'id'=>$getID,'rel'=>$getRel);
				return $Images->PrintImage($imgPropsArray);
				
			}else{ /// (ELSE) IF FILE EXISTS & SIZE IS ASSIGNED
				$imgPropsArray = array('src'=>$getImgSrc,'alt'=>$getAlt);
				switch($getSize){
					case "primary":
						return $Images->PrintImage($imgPropsArray);break;
				}
				
			}
			
		}
		/// END : ScaleImage ///
		
		//////////////////////////////////////////
		/// MAIN IMAGE (INCLUDING FADING SEQUENCE)
		function SlideshowCMS($getAttributes){		
			global $PrintMainPic,$Images,$CMSShared,$gp_uploadPath,$SiteFunctions,$CMSTextFormat,$HasAudioFile;
			
			$getID = $getAttributes['itemID'];
			$ss_fade = true;
			$ss_width = 760;
			$ss_height = 245;
			$ss_delay = 1500;
			$ss_pause = 1;
			$ss_nofade = 0;
			$ss_thumbs = false;
				
			if(sizeof($getAttributes)!=0){				
				if(!empty($getAttributes['limit']))		$getLimit = $getAttributes['limit'];
				if(isset($getAttributes['fade']))		$ss_fade = $getAttributes['fade'];
				if(!empty($getAttributes['width']))		$ss_width_master = $getAttributes['width'];
				if(!empty($getAttributes['height']))	$ss_height_master = $getAttributes['height'];
				if(!empty($getAttributes['delay']))		$ss_delay = $getAttributes['delay'];
				if(!empty($getAttributes['pause']))		$ss_pause = $getAttributes['pause'];
				if(!empty($getAttributes['thumbs']))	$ss_thumbs = $getAttributes['thumbs'];
			}
			
			$thumbs = array();
			$content = "";
			$ImageData = "";

			if($getID){
				$query = "SELECT * FROM catalogue WHERE image_large!='' AND (id=$getID OR id_xtra=$getID) ORDER BY position_initem ASC";
				if($getLimit) $query.=" LIMIT $getLimit";
				$result = mysqli_query($dbc, $query);
				if($result && mysqli_num_rows($result)>=1){
					$HasAudioFile = true;				
					$StartWrap='<div id="slideshow_wrap_all">'."\r\n";
					$SSData='';
					
					if(mysqli_num_rows($result)==1){
						$imgArr = mysqli_fetch_array($result);
						$imgSRC = $gp_uploadPath['large'].$imgArr['image_large'];
						
						if($Images->IsPortrait($imgSRC)){
							$Portrait=true;
							$SSData.='<div id="slideshow_wrap" class="portrait">'."\r\n";
							$SSData.='<div id="slideshow" class="portrait">'."\r\n";
							//$RoundCorners = '<div id="RoundCorners" class="portrait">&nbsp;</div>'."\r\n";
						}else{
							$SSData.='<div id="slideshow_wrap">'."\r\n";
							$SSData.='<div id="slideshow">'."\r\n";
							//$RoundCorners = '<div id="RoundCorners">&nbsp;</div>'."\r\n";
						}
						
					
						if(($CMSShared->FileExists($imgSRC) && $CMSShared->IsImage($imgSRC))){
							$imgFullSRC = $gp_uploadPath['large'].$imgArr['image_large'];
							$SSData.='<img src="'.$imgSRC.'" width="'.$ss_width_master.'px" height="'.$ss_height_master.'px" alt="'.$imgArr['name'].'"/>'."\r\n";
							$SSData.='</div>'."\r\n";
							//$SSData.='<a href="'.$imgFullSRC.'" rel="lightbox-journey" title="'.$imgArr['name'].'">';
							//$SSData.=$RoundCorners;
							//$SSData.='</a>';
						}else{
							$NoImage = true;
						}
						
					}else{						
						
						$SSData.='<div id="slideshow_wrap">'."\r\n";
						$SSData.='<div id="slideshow">'."\r\n";
					
						$JScode='<script type="text/javascript">'."\r\n";
						$JScode.='var tmpImageList=new Array();'."\r\n";
						
						$DefaultHyperlink = "";
						$DefaultHyperlinkTarget = "_self";
						
						$tmpImageListID = 0;//need this as if date is not valid, the sequence will skip rows & mess up order
						for($tmpcount=0;$tmpcount<mysqli_num_rows($result);$tmpcount++){
							$imgArr = mysqli_fetch_array($result);
							$thumbSRC = $gp_uploadPath['thumbs'].$imgArr['image_large'];
							$imgSRC = $gp_uploadPath['large'].$imgArr['image_large'];
							$largeSRC = $gp_uploadPath['large'].$imgArr['image_large'];
							$imgAlt = $imgArr['name'];
							
							if(!$imgFullSRC) $imgFullSRC = $imgSRC;
							if(!$imgFullNAME) $imgFullNAME = $imgAlt;
							if($tmpcount==0) $StaticImage = $imgSRC;
							
							
							if(!$imgParentCat) $imgParentCat = $imgArr['category'];
							
							if(($CMSShared->FileExists($imgSRC) && $CMSShared->IsImage($imgSRC))){//$CMSShared->DateValid($imgArr['upload_date'],$imgArr['spare_date']) &&
								// get original (ACTUAL) dimensions
								$tmp = @getimagesize($imgSRC);
								$ss_width=$tmp[0];
								$ss_height=$tmp[1];
								
								$JScode.='tmpImageList['.$tmpImageListID.']=["'.$imgSRC.'", "'.$DefaultHyperlink.'", "'.$DefaultHyperlinkTarget.'"];'."\r\n";

								$tmpImageListID++;
								if($getID!=$imgArr['id']) array_push($thumbs,array($imgArr['id'],$thumbSRC,$imgSRC,$largeSRC,$imgAlt));
							}							
						}
						 

						//new fadeshow(IMAGES_ARRAY_NAME, slideshow_width, slideshow_height, borderwidth, delay, pause (0=no, 1=yes), optionalRandomOrder, nofade(0=fade,1=nofade) )
						$JScode.='new fadeshow(tmpImageList, '.$ss_width_master.', '.$ss_height_master.', 0, '.$ss_delay.', 1, 0, '.$ss_nofade.')'."\r\n";
						$JScode.='</script>'."\r\n";
						
						if(!$ss_fade){
							$SSData.='<a href="'.$imgFullSRC.'" rel="lightbox-journey" title="'.$imgFullNAME.'">';
							$SSData.='<img src="'.$StaticImage.'" width="'.$ss_width_master.'px" height="'.$ss_height_master.'px" alt="'.$imgArr['name'].'">';
							$PrintMainPic = '<img src="'.$StaticImage.'">';
							$SSData.='</a>';
						}else{
							$SSData.=$JScode;
						}
						$SSData.='</div>'."\r\n";
						//$SSData.='<img id="placeholder" src="'.$imgSRC.'" alt="'.$imgArr['name'].'">'."\r\n";
						//$SSData.='<div id="RoundCorners">&nbsp;</div>'."\r\n";
							
					}
					
					
					$SSData.='</div>'."\r\n";
					
					if(sizeof($thumbs)<2) $ss_thumbs = false;//don't print thumbnails if only one image
					$SSData.='<ul id="thumbs">'."\r\n";
					if($ss_thumbs){						
						for($t=0;$t<sizeof($thumbs);$t++){
							$SSData.='<li';
							if($t>0 && is_integer(($t+1)/10)) $SSData.=' class="last"';							
							$SSData.='>';
							if($getAttributes['showlarge']) $SSData.='<a href="'.$thumbs[$t][3].'" rel="lightbox-journey" title="'.$thumbs[$t][4].'">';
							$SSData.='<img src="'.$thumbs[$t][1].'" name="'.$thumbs[$t][2].'" alt="'.$thumbs[$t][3].'">';
							if($getAttributes['showlarge']) $SSData.='</a>';
							$SSData.='</li>'."\r\n";
						}									
					}
					$SSData.='</ul>'."\r\n";
					
					$SS_all = $StartWrap;
					$SS_all .= $SSData;
					$SS_all .='</div>'."\r\n";
					
					if(!$NoImage) return $SS_all;	
				}				
			}
		}
		/// END ///
		
		function PrintImage($getimgPropsArray){
			
			$attributes = array('src','width','height','alt','class','id','rel');
			$buildIMG = '<img';
			for($i=0;$i<sizeof($attributes);$i++){
				if(!empty($getimgPropsArray[$attributes[$i]])) $buildIMG .= ' '.$attributes[$i].'="'.$getimgPropsArray[$attributes[$i]].'"';
			}
			if($getimgPropsArray['class']=="primary" && $getimgPropsArray['width']<140) $buildIMG .= ' style="position:absolute;top:0px;left:'.round((140-$getimgPropsArray['width'])/2).'px;"';
			$buildIMG .= '>';
			return $buildIMG;
			
			//return '<img src="'.$getImgSrc.'" width="'.$width_abs.'" height="'.$height_abs.'" alt="'.$getAlt.'"/>';			
		}

		
			
	}
	
	$Images = new Images();

?>