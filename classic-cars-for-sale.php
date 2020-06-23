<?php

	$currentpage = "catalogue";
	$Content = '';
	$MainTitleBuild = '';
	$MainKeywordsBuild = '';
	$ThisPageKeywords = '';
	$MainDescriptionBuild = '';
	
	
	$status = 1;
	if($_GET['status']==2){
		$currentpage = "archive";
		$itemsPerPage = 18;
	}else{
		$itemsPerPage = 12;
	}

	if($_GET['status']) $status = $_GET['status'];
	if($_GET['searching']) $searching = $_GET['searching'];
	if($_GET['category']) $categoryID = $_GET['category'];
	if($_GET['subcategory']) $subcategoryID = $_GET['subcategory'];
	if($_GET['subcategoryName']) $subcategoryName = $_GET['subcategoryName'];
	if($_GET['region']) $regionID = $_GET['region'];
	if($_GET['uid']) $itemID = $_GET['uid'];
	
	
	
	
	$categoryID = 2;//Classifieds
	
	
	include("inc/classes/ClientData.php");
	include("inc/classes/PageBuild.php");
	include("inc/classes/Catalogue.php");	
	include("inc/classes/Featured.php");
	include("inc/classes/Images.php");
	//$SearchPanel = $Search->SearchPanel();
	if($searching) $MainTitleBuild .= 'Search for \''.$qs_keywords.'\'';
	
	//$PageBuild->AddTags("css/featured.css");
	$PageBuild->AddTags("css/featurebox2.css");
	$PageBuild->AddTags("css/paginator.css");
	$PageBuild->AddTags("css/catalogue2.css");
	$PageBuild->AddTags("drop-down/menu.css");
	
	$CrumbPages = array();
	$PaginatorAttributes = array('status'=>$status,'itemsPerPage'=>$itemsPerPage,'itemPage'=>$client['itemPage'].'?status='.$status,'itemStyle'=>"li");
	
	
	if($categoryID){
		//include("inc/classes/CatalogueSubCats.php");
		$attributes = array('id'=>$categoryID,'table'=>'catalogue_cats','field'=>'category');
		$categoryName = $Catalogue->GetItemData($attributes);
		if($searching){
			$MainTitleBuild .= ' in \''.$categoryName.'\'';
		}else{
			$MainTitleBuild .= $categoryName." in Malton, North Yorkshire";
			if($categoryID==2 && $status==2) $MainTitleBuild = "Classic Cars Sold by ".$client['name'].", Malton, North Yorkshire ";
		}
		
		$CrumbPages['category']=array();	
		$CrumbPages['category']['title']=$categoryName;
		$attributes = array('type'=>'category','id'=>$categoryID,'name'=>$categoryName);
		$CrumbPages['category']['href'] = $SEO_links->GenerateLink($attributes);
						
		$PaginatorAttributes['categoryID']=$categoryID;
		$PaginatorAttributes['categoryName']=$categoryName;
		$PaginatorAttributes['itemPage'].='&category='.$categoryID;
	}

	
	if($subcategoryID || $subcategoryName){		
		if($subcategoryID){
			$attributes = array('id'=>$subcategoryID,'table'=>'catalogue_subcats','field'=>'subcategory');
			$subcategoryName = $Catalogue->GetItemData($attributes);			
		}else{

			//echo '<br>1. '.$subategoryName;
			$subategoryName = $SEO->sanitize(array('string'=>$_GET['subcategoryName'],'from'=>true));
			//echo '<br>2. '.$subategoryName;
			$subcatQuery = "SELECT id,subcategory FROM catalogue_subcats WHERE lower(subcategory)='$subategoryName' LIMIT 1";
			$subcatResult = mysqli_query($dbc,$subcatQuery);
			if($subcatResult && mysqli_num_rows($subcatResult)==1){
				$subcatRow = mysqli_fetch_row($subcatResult);
				$subcategoryID = $subcatRow[0];
				$subcategoryName = $subcatRow[1];				
			}
		}

		$CrumbPages['subcategory']=array();		
		$CrumbPages['subcategory']['title']=$subcategoryName;
		$attributes = array('type'=>'subcategory','id'=>$subcategoryID,'name'=>$subcategoryName,'categoryID'=>$categoryID,'categoryName'=>$categoryName);
		$CrumbPages['subcategory']['href'] = $SEO_links->GenerateLink($attributes);

		$MainTitleBuild = ucwords($subcategoryName)." for sale in Malton, North Yorkshire";
		if($categoryID==2 && $status==2) $MainTitleBuild = ucwords($subcategoryName)." sold by ".$client['name'].", Malton, North Yorkshire";
		$PaginatorAttributes['subcategoryID'] = $subcategoryID;
		$PaginatorAttributes['subcategoryName'] = ucwords($subcategoryName);
		$PaginatorAttributes['itemPage'].='&subcategory='.$subcategoryID;
	}

	
	if($itemID){
		include("inc/classes/CatalogueDetails.php");
		$itemArray_query = "SELECT c.*,cc.id AS categoryID,cc.category AS categoryName,csc.id AS subcategoryID,csc.subcategory AS subcategoryName";//,r.region AS regionName
		$itemArray_query .= " FROM catalogue AS c,catalogue_cats AS cc,catalogue_subcats AS csc";//,tbl_regions AS r
		$itemArray_query .= " WHERE c.id=$itemID AND c.category=cc.id AND c.subcategory=csc.id";
		//$itemArray_query .= " AND (c.upload_date<=$TheDayToday AND (c.spare_date='0000-00-00' OR c.spare_date='' OR c.spare_date>$TheDayToday))";
		$itemArray_query .= " LIMIT 1";// AND r.id=c.$field_region
		$echo .= $itemArray_query;
		$itemArray_result = mysqli_query($dbc,$itemArray_query);
		if($itemArray_result && mysqli_num_rows($itemArray_result)==1){
			$row = mysqli_fetch_array($itemArray_result);
			$itemName = $row['name'];
			$categoryID = $row['categoryID'];
			$categoryName = $row['categoryName'];
			$subcategoryID = $row['subcategoryID'];
			$subcategoryName = $row['subcategoryName'];

			$CrumbPages['category']=array();
			$CrumbPages['category']['title']=$categoryName;
			$attributes = array('type'=>'category','id'=>$categoryID,'name'=>$categoryName);
			$CrumbPages['category']['href'] = $SEO_links->GenerateLink($attributes);
			
			$CrumbPages['subcategory']=array();		
			$CrumbPages['subcategory']['title']=$subcategoryName;
			$attributes = array('type'=>'subcategory','id'=>$subcategoryID,'name'=>$subcategoryName,'categoryID'=>$categoryID,'categoryName'=>$categoryName);
			$CrumbPages['subcategory']['href'] = $SEO_links->GenerateLink($attributes);
			
			$PageBuild->AddTags("css/imageBox.css");
			$PageBuild->AddAudioPlayer();
		
			$CrumbPages['item']=array();		
			$CrumbPages['item']['title']=$itemName;		
			
			$PageBuild->AddTags("css/slideshow2.css");
			$PageBuild->AddTags("css/details.css");			
			//$imageBox = include("imageBox.php");
			$PageBuild->AddLightBox();
			$MainTitleBuild = $itemName;
		}
	}
	
	
	if($itemID){
		$Classes = $CatalogueDetails->ItemDetails($itemID);
	}else{
		//if($categoryID && !$subcategoryID && !$searching){
		//	$Classes = $CatalogueSubCats->ListSubCats(array('categoryID'=>$categoryID));
		//}else{
			$Classes = include("content/catalogue_show.php");
		//}
	}

	if($MainKeywordsBuild) $ThisPageKeywords = $MainKeywordsBuild;
	$PageBuild->SetPageKeywords($ThisPageKeywords);
	if($status==1){
		$PageBuild->SetPageDescription('Classic Cars For Sale: '.$MainDescriptionBuild);
	}else{
		$PageBuild->SetPageDescription('Classic Cars Archive: '.$MainDescriptionBuild);
	}
	$PageBuild->SetPageTitle($MainTitleBuild);
	/*if($searching || $currentpage=="archive"){
		$PageBuild->SetBodyTag('<body id="slide-effect">');
		$PageBuild->AddTags("accordion/style.css");
		$PageBuild->AddTags("accordion/mootools_v1_11.js");
		$jsTags .= <<<EOD
<script type="text/javascript">
	//<![CDATA[	

	var Site = {

		start: function(){
			if($('vertical')) Site.vertical();
		},
		
		vertical: function(){
			var list = $$('#vertical li div.collapse');
			var headings = $$('#vertical li h3');
			var collapsibles = new Array();
			
			headings.each( function(heading, i) {

				var collapsible = new Fx.Slide(list[i], { 
					duration: 500, 
					transition: Fx.Transitions.linear,
					onComplete: function(request){ 
						var open = request.getStyle('margin-top').toInt();
						if(open >= 0) new Fx.Scroll(window).toElement(headings[i]);
					}
				});
				
				collapsibles[i] = collapsible;
				
				heading.onclick = function(){
					
					var span = this.getElementsByTagName("acronym");
					//alert(span.innerHTML +" / "+spanExpand[0].innerHTML );

					if(span){
						if(span[0].className=="expand"){
							span[0].className = "collapse";
						}else{
							span[0].className = "expand";
						}
					}

					collapsible.toggle();
					return false;
				}
				
				collapsible.hide();
				
			});
			
			$('collapse-all').onclick = function(){
				headings.each( function(heading, i) {
					collapsibles[i].hide();
					var span = heading.getElementsByTagName("acronym");
					span[0].className = "expand";
				});
				return false;
			}
			
			$('expand-all').onclick = function(){
				headings.each( function(heading, i) {
					collapsibles[i].show();
					var span = heading.getElementsByTagName("acronym");
					span[0].className = "collapse";
				});
				return false;
			}
			
		}
	};
	window.addEvent('domready', Site.start);
//]]>	
</script>
EOD;

	}
	*/

	$Content .= $PageBuild->StartMetaTags();

	
	
	$Content .= '<div class="contentbox" id="BlueBG">';
		
		$Content .= '<div class="contentLeft">';
			$Content .= $SiteFunctions->PrintNav("Main",$arr_page[0]);

			if($status==2){
				$Content .= $Catalogue->PrintSubCats(array('title'=>"Classic Cars Sold",'titleHref'=>"classic-car-archive",'status'=>$status));
			}else{
				$Content .= $Catalogue->PrintSubCats("");
				$Content .= $ContactDetails->DetailsBox("");
				$Content .= $ContactDetails->DetailsBox("OpeningHours");
			}
			
		$Content .= '</div>'."\r\n";		
		
		$Content .= '<div class="contentMiddle" id="SpanRight">';			
			if($categoryID==1) $CrumbPages['category']=array();
			//$Content .= $Crumbs->BuildCrumbs($CrumbPages);				
			$Content .= $Classes;
		$Content .= '</div>'."\r\n";		

	$Content .= '</div>'."\r\n";
	
	echo $Content;
		
	echo $PageBuild->BuildFTR();	
?>