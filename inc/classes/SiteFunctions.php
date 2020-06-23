<?php
					
	Class SiteFunctions {		
		
		function ReturnInternational($getNumber){
			return str_replace("01440", "+44(0) 1440", $getNumber);
		}
		
		
		/// SHOW | reduced string		
		function AssLogos(){
			$Logos = '';
			$Logos .= '<ul class="AssLogos">';
				$Logos .= '<li><a href="http://www.rightmove.co.uk" title="Rightmove - The UK\'s number one property website" class="one" target="_blank"><span>rightmove.co.uk</span></a></li>';
				$Logos .= '<li><a href="http://www.propertytoday.co.uk/" title="PropertyToday Property for sale and rent in the UK and overseas through leading estate agents." class="two" target="_blank"><span>Property Finder</span></a></li>';
				$Logos .= '<li><a href="http://www.rics.org/" title="Royal Institution of Chartered Surveyors" class="three" target="_blank"><span>RICS</span></a></li>';
				$Logos .= '<li><a href="http://www.rics.org/" title="Royal Institution of Chartered Surveyors" class="four" target="_blank"><span>RICS</span></a></li>';
				$Logos .= '<li><a href="http://www.rics.org/" title="Royal Institution of Chartered Surveyors" class="five" target="_blank"><span>RICS</span></a></li>';
				$Logos .= '<li><a href="http://www.rics.org/" title="Royal Institution of Chartered Surveyors" class="six" target="_blank"><span>RICS</span></a></li>';
			$Logos .= '</ul>';
			
			return $Logos;
		}
		/// END ///
	
		///////////////////
		// Print Navigation
		function PrintNav($getStyle,$getNav){
			global $currentpage,$client,$arr_page,$CrumbPages;			
			
			
			$Nav = '<ul id="'.$getStyle.'">'."\r\n";
			
			for($i=0;$i<count($getNav);$i++){
				
				if($getStyle=="Main" && !$getNav[$i]['minilink']){
					/*if($getStyle=="Main" && $i==0){
						$Nav .= '<li class="first">';
					}elseif($getStyle=="Main" && $i==count($getNav)-1){
						$Nav .= '<li class="last">';
					}else{
						$Nav .= '<li>';
					}*/
					if($getNav[$i]['pos']){
						$Nav .= '<li class="'.$getNav[$i]['pos'].'">';
					}else{
						$Nav .= '<li>';
					}
					
					//id="'.$getNav[$i]['name'].'"
					$Nav .= '<a href="'.$getNav[$i]['href'].'" title="'.$getNav[$i]['title_x'].'"';				
					if($getNav[$i]['class']) $Nav .= ' class="'.$getNav[$i]['class'].'"';
if($currentpage==$getNav[$i]['name']) $Nav .= ' class="current"';
					if($getNav[$i]['target']) $Nav .= ' target="'.$getNav[$i]['target'].'"';
					$Nav .= '>'.$getNav[$i]['title'].'</a></li>'."\r\n";
					
					
				}
			}
			$Nav .= '</ul>'."\r\n";
			return $Nav;
		}
		
		function PrintNavMiniLinks($getStyle,$getNav){
			global $currentpage;
			$MiniLinks = '<ul class="minilinks">'."\r\n";
			for($tmpcount=0;$tmpcount<count($getNav);$tmpcount++){
				
				$MiniLinks .= '<li class="ml_'.$getNav[$tmpcount]['name'].'"';				
				if($tmpcount==0) $MiniLinks .= ' id="first"';
				$MiniLinks .= '>';
				
				$MiniLinks .= '<a href="'.$getNav[$tmpcount]['href'].'" title="'.$getNav[$tmpcount]['title_x'].'"';
				if($getNav[$tmpcount]['name']=="addtofav") $MiniLinks .= ' id="addtofav"';
				if($currentpage == $getNav[$tmpcount]['name']) $MiniLinks .= ' class="current"';
				
				$MiniLinks .= '><span>'.$getNav[$tmpcount]['title'].'</span></a></li>'."\r\n";
			}
			$MiniLinks .= '</ul>'."\r\n";
			return $MiniLinks;
		}
		
		
		
		//////////////////////////// FORCE File Download
		/// File Download
		function FileDownloadLink($getFilePath){
			$FileLink = 'force-download.php?file='.$getFilePath;
			return $FileLink;	
		}
		/// END ///
		
		///////////////////////////////////////////
		/// FUNCTION : Check article is ok to show
		function DateValid($getDateStart,$getDateEnd){
			global $TheDayToday;
			//echo '<br>Open: '.$getDateStart;
			//echo '<br>Close: '.$getDateEnd;
			//echo '<br>Today is: '.$TheDayToday.'('.date(G).'hr)';
			if(empty($getDateEnd) || $getDateEnd=='0000-00-00' || strpos($getDateEnd, "00-00")!=0) $SkipExpiry=true;
			if($getDateStart<=$TheDayToday && ($SkipExpiry || $getDateEnd>$TheDayToday))return true;
		}
		/// END ///
		
			
	}
	
	$SiteFunctions = new SiteFunctions();
	$TheDayToday = date('Y-m-d');

?>