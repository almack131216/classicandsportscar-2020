<?php
	
	Class GoogleMap {		
		
		///////////////////////////////////////////////////////////
		//add Google Map within iFrame (for use on workshop finder)
		function addGoogleMapIFrame($getPageID){
			global $DefaultBranchID;
			$DefaultBranchID="";
			
			$AppendSRC = '';
				
			if($_GET['workshopTitle']) $AppendSRC = '&gZoom=all&workshopTitle='.$_GET['workshopTitle'];
			if($_REQUEST['lat'] && $_REQUEST['lng']) $AppendSRC = '?lng='.$_REQUEST['lng'].'lat='.$_REQUEST['lat'];				
			$attributes = array('src'=>'index.php?gMapType=propertyfinder','width'=>700,'height'=>650,'title'=>'Workshop Finder','style'=>'style="float:left;"');
			if($_GET['uid']){
				//$attributes['src'] = 'index.php?gMapType=propertyfinder';
				$attributes['width'] = 700;
				$attributes['style'] = 'style="float:left;"';
				$AppendSRC .= '&gZoom=single&id='.$_GET['uid'];
			}
			if($_REQUEST['SearchByTown']){
				//$attributes['src'] = 'index.php?gMapType=propertyfinder';
				$attributes['width'] = 700;
				$attributes['style'] = 'style="float:left;"';
				$AppendSRC .= '&gZoom=all&SearchByTown='.$_REQUEST['SearchByTown'];
			}
			if($_REQUEST['title']) $AppendSRC .= '&title='.$_REQUEST['title'];
			if($_REQUEST['radiusSelect']) $AppendSRC .= '&radiusSelect='.$_REQUEST['radiusSelect'];
			if($_REQUEST['when']) $AppendSRC .= '&when='.$_REQUEST['when'];
			if($_REQUEST['orderSelect']) $AppendSRC .= '&orderSelect='.$_REQUEST['orderSelect'];
				
			
			//if($_GET['RegionList']) $AppendSRC .= '?RegionList='.$_GET['RegionList'];
			//if($_POST['SearchByTown']) $AppendSRC .= '?SearchByTown='.$_POST['SearchByTown'];		
			
			$content = "";
			$content .= '<iframe src="_map/'.$attributes['src'].$AppendSRC.'" width="'.$attributes['width'].'px" height="'.$attributes['height'].'px" marginwidth="0" marginheight="0" frameborder="0" scrolling="no">';//(not w3c but still useful): hspace="0" vspace="0" allowtransparency="true"
			$content .= '<!-- Alternate content for non-supporting browsers -->';
			$content .= '<p>Unfortunately, your browser does not support iFrames</p>';
			$content .= '<p>To view our '.$attributes['title'].', you will need to <a href="_map/'.$attributes['src'].'?GoogleMap=true" title="Open '.$attributes['title'].' a new window" target="_blank">Open '.$attributes['title'].' a new window</a>.</p>';
			$content .= '</iframe>';
			return $content;
		}
		/// END ///
			
	}
	
	$GoogleMap = new GoogleMap();

?>