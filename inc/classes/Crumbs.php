<?php								
			
	Class Crumbs {

		/////////////
		//BuildCrumbs
		function BuildCrumbs($getAttributes){
			global $client;
			$crumbOrder = array('parent','category','subcategory','item','page');
			
			$Crumbs = '<ul class="Crumbs">'."\n";
			//$Crumbs .= '<li><a class="home" href="'.$client['homepage'].'" title="Homepage">You are here:</a></li>'."\n";
			$Crumbs .= '<li class="root">You are here:</li>'."\n";
			//$Crumbs .= '<li><a href="'.$client['homepage'].'" title="Homepage">Home</a></li>'."\n";
			for($i=0;$i<sizeof($crumbOrder);$i++){
				if($getAttributes[$crumbOrder[$i]]){					
					if($getAttributes[$crumbOrder[$i]]['href'] && $i<round(sizeof($getAttributes))){
						$Crumbs .= '<li><a href="'.$getAttributes[$crumbOrder[$i]]['href'].'" title="Link to '.$getAttributes[$crumbOrder[$i]]['title'].'">'.$getAttributes[$crumbOrder[$i]]['title'].'</a></li>';
					}else{
						$Crumbs .= '<li class="currentCrumb">'.$getAttributes[$crumbOrder[$i]]['title'].'</li>';
					}
				}
			}
				
			/* Static Pages */
			//if(!empty($getAttributes['itemName']) && !empty($getAttributes['itemHref'])) $Crumbs .= '<li><a href="'.$getAttributes['itempageHref'].'" title="'.$getAttributes['itempageTitle'].'">'.$getAttributes['itempageTitle'].'</a></li>';
			
			$Crumbs .= '</ul>'."\n";
			return $Crumbs;
		}
		///END
		
		function itemCrumb(){
			
		}
		
	}	
	$Crumbs = new Crumbs();
?>