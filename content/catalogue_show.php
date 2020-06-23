<?php

	$itemPage = $PaginatorAttributes['itemPage'];
	$searchLimit = 50;
	
	$status = $PaginatorAttributes['status'];
	$categoryID = $PaginatorAttributes['categoryID'];
	$categoryName = $PaginatorAttributes['categoryName'];	
	$subcategoryID = $PaginatorAttributes['subcategoryID'];
	$subcategoryName = $PaginatorAttributes['subcategoryName'];

	
	if($PaginatorAttributes['itemsPerPage']){
		$itemsPerPage = $PaginatorAttributes['itemsPerPage'];
	}else{
		$itemsPerPage = $searchLimit;
	}
	$ItemStyle = $PaginatorAttributes['itemStyle'];
	
	
	$catalogueItems = "";
	//$echo .= '<br/>'.$gp_qs_arr_order[$qs_order][2];
		
	///////////// GET VALUES
	//$tableshade = '#F4F4F4';//////////// separating shade for table(light grey)	
	$first 		= TRUE;
	
	///////////// QUERY DATABASE
	$itemArray = array();
	$getItemNum = 0;	
	
	$attributes = array('type'=>'paginator','categoryID'=>$categoryID,'subcategoryID'=>$subcategoryID,'subcategoryName'=>$subcategoryName,'status'=>$status);
	$paginatorHref = $SEO_links->GenerateLink($attributes);
	
	/// If highlighting selected item at top (first)
	if(!empty($_GET['uid'])){
		$getItemNum = $_GET['uid'];
		$paginatorHref .= $getItemNum;
		if(empty($_GET['pg']) || $_GET['pg']==1){
			$query = "SELECT * FROM catalogue AS c WHERE c.id=$getItemNum LIMIT 1";
			$result = mysqli_query($dbc, $query);
			if($result && mysqli_num_rows($result)==1){
				$row = mysqli_fetch_array($result);				
				$itemArray[] = $row;
				$ItemHighlighted = true;// This is needed to ensure item does not appear twice (as it has been esculated to pos1 as selected)				
			}
		}
	}
	
	if($categoryID==$clientCats['Press']){
		$OrderCatBy = "c.position_insubcat ASC";//Press pages rely on VERY OLD data which is entered manually as '0000-00-00' in price_details field
	}else{
		$OrderCatBy = "c.upload_date DESC";
	}

	/// Variables
	if($currentpage=="archive"){
		$WHERE = "WHERE c.status=2";
	}else{
		$WHERE = "WHERE c.status=$status";		
	}
	$WHERE .= " AND c.id_xtra=0";
	if($currentpage=="classes") $WHERE .= " AND c.category!=1";//exclude News & adverts
	if($categoryID){
		$CategorySpecific = " AND c.category=$categoryID";
		$WHERE .= $CategorySpecific;
	}
	if($subcategoryID) $WHERE .= " AND c.subcategory=$subcategoryID";
	
	if($qs_price_min)$WHERE .= " AND c.price>=$qs_price_min";
	if($qs_price_max)$WHERE .= " AND c.price<=$qs_price_max";
	
	
		
		
	if($qs_keywords){
		$qs_keywordsArr = explode(" ",$qs_keywords);
		$qs_keywordsPlus = '+'.str_replace(" "," +",$qs_keywords);
		$MatchFields = "c.name,c.detail_2,c.description,c.detail_5,c.detail_7,c.detail_8";//,cc.category,csc.subcategory
		
		$searchQuery = "SELECT c.*,cc.category AS categoryName,csc.subcategory AS subcategoryName, MATCH (c.name) AGAINST ('$qs_keywords' WITH QUERY EXPANSION) AS relevancyName, MATCH ($MatchFields) AGAINST ('$qs_keywords' IN BOOLEAN MODE) AS relevancy";
		$searchQuery .= " FROM catalogue c,catalogue_cats cc,catalogue_subcats csc";
		$searchQuery .= " WHERE c.category=cc.id AND c.subcategory=csc.id";
		if($categoryID){
			$searchQuery .= $CategorySpecific;
			$ResultsTitleAppend = ' within \''.$categoryName.'\'';
		}
		
		$searchQuery .= " AND MATCH ($MatchFields) AGAINST ('$qs_keywordsPlus' IN BOOLEAN MODE) ORDER BY relevancyName DESC, relevancy DESC LIMIT $searchLimit";
		$searchResult = mysqli_query($dbc, $searchQuery);
		
		
		if(!$searchResult || mysqli_num_rows($searchResult)==0){
			$searchQuery = str_replace($CategorySpecific,"",$searchQuery);
			$searchResult = mysqli_query($dbc, $searchQuery);
			$categoryEmpty = true;
		}
		
		if($searchResult && mysqli_num_rows($searchResult)>=1){
			$echo .= '<br>'.mysqli_num_rows($searchResult).':::'.$searchQuery;
			$first=false;
			$totals = mysqli_num_rows($searchResult);			
			
			if($totals>=10){
				$ResultsSubTitle = '<h2>Your matches have been ordered by relevance</h2>';
				if($totals==$searchLimit){
					$searchTip = true;
					$ResultsSubTitle = '<h2>Consider using a more specific search term or add an extra word for better results</h2>';
				}
				$catalogueItems .= $string;
				$catalogueItems .= <<<EOD
<div id="container">

<div class="heading">
	<a id="expand-all" href="#" title="Expand all" class="expand">expand all</a>&nbsp;<a id="collapse-all" href="#" title="Collapse all" class="collapse">collapse all</a>
</div>

<ul id="vertical" class="simple">
EOD;
				
				$catalogueItems .= '<ul>';
				$RowNum = 0;
				for($i=0;$i<$totals;$i++){
					$RowNum++;
					$row = mysqli_fetch_array($searchResult);
					$catalogueItems .= '<li>';
					$ExpandTitle = $row['categoryName'].' -&#62; '.$row['subcategoryName'].' -&#62; '.$row['name'];
					$ExpandTitle = $CMSTextFormat->highlightWords($ExpandTitle,$qs_keywordsArr);
					$catalogueItems .= '<h3>'.$RowNum.'. <a name="'.$RowNum.'" href="#'.$RowNum.'">'.$ExpandTitle.'</a>  <acronym class="expand" title="Click to Expand / Collapse">&nbsp;</acronym></h3>';
					$catalogueItems .= '<div class="collapse"><div class="collapse-container">';
					$attributes = array('itemArray'=>$row,'itemStyle'=>"row",'itemPage'=>$itemPage);
					$catalogueItems .= $Catalogue->ItemPreview($attributes);
					//$catalogueItems .= $row['description'];
					$catalogueItems .= '</div></div>';
					$catalogueItems .= '</li>';
				}
				$catalogueItems .= '</ul>';
				$catalogueItems .= '</ul></div>';
				
			}else{
				for($i=0;$i<$totals;$i++){
					$RowNum++;
					$row = mysqli_fetch_array($searchResult);
					//$row['name'] = $row['relevancy'].':'.$row['name'];
					$attributes = array('itemArray'=>$row,'itemStyle'=>"row",'itemPage'=>$itemPage);
					$catalogueItems .= $Catalogue->ItemPreview($attributes);
				}
			}
		}else{
			$catalogueItems .= 'No matches';
		}

	}
	
	if(!$catalogueItems){
		
		//Show order by... 
		if($qs_order){
			$WHERE .= " ORDER BY $qs_order";
		}else{
			$WHERE .= " ORDER BY $OrderCatBy";
			/*if($OrderCatBy){
				$WHERE .= " ORDER BY $OrderCatBy, c.upload_date desc";//position_incat asc
			}else{
				$WHERE .= " ORDER BY c.upload_date desc";//latest -> oldest
			}*/	
		}
		//if($ItemHighlighted) $WHERE .= " AND id!=$getItemNum";
		
		
		// Number of results per page
		if(!empty($qs_keywords)) $itemsPerPage = $searchLimit;
		if(!isset($_GET['pg'])){ $pg = 1; } else { $pg = $_GET['pg']; }
		$from = (($pg * $itemsPerPage) - $itemsPerPage);
	
		/// Count total
		$countQuery = "SELECT COUNT(*) as Num FROM catalogue AS c $WHERE";
		$countResult = mysqli_query($dbc, $countQuery);
		if($countResult && mysqli_num_rows($countResult)>=1){
			$totalsRow = mysqli_fetch_row($countResult);
			$totals = $totalsRow[0];
			$total_pgs = ceil($totals / $itemsPerPage);
			if($_SERVER['HTTP_HOST']=="localhost") $echo .= '<br>TOTAL:'.$totals;
		}
		
		/// Page limiter & result builder
		if($totals==0 && $categoryID){
			$WHERE = str_replace($CategorySpecific,"",$WHERE);
			$categoryEmpty = true;
		}

		$sql = "SELECT id FROM catalogue AS c $WHERE LIMIT $from, $itemsPerPage";
		$result1 = mysqli_query($dbc, $sql);
		if($result1){
			$num_sql = mysqli_num_rows($result1);
		}else{
			$num_sql = 0;
		}

		//$sql = "SELECT * FROM catalogue AS c $WHERE LIMIT $from, $itemsPerPage";
		//$result1 = mysqli_query($dbc, $sql);
		//$num_sql = mysqli_num_rows ($result1);		
		if($_SERVER['HTTP_HOST']=="localhost") $echo .= '<br>SQL:'.$sql.'('.$num_sql.')</p>';
		
		//Build paginator
		$paginator = '';
		$paginator .= '<ul class="paginator">';
		$paginator .= '<li>Pages:</li>';
		if($pg > 1){
			$prev = ($pg - 1); // Previous Link
			$paginator .= '<li><a href="'.$SEO_links->GenerateLink(array('type'=>'paginatorPageNum','page'=>$paginatorHref,'pageNum'=>$prev)).'" class="BackBut"><span>Previous page</span>&nbsp;</a></li>';
		}
		for($i = 1; $i <= $total_pgs; $i++){ /// Numbers
			if(($pg) == $i) {
				if($total_pgs>1) $paginator .= '<li><strong>'.$i.'</strong></li>'; //don't show selected page if it's there are no others
			} else {
				$paginator .= '<li><a href="'.$SEO_links->GenerateLink(array('type'=>'paginatorPageNum','page'=>$paginatorHref,'pageNum'=>$i)).'">'.$i.'</a></li>';
			}
		}
		if($pg < $total_pgs){
			$next = ($pg + 1); // Next Link
			$paginator .= '<li><a href="'.$SEO_links->GenerateLink(array('type'=>'paginatorPageNum','page'=>$paginatorHref,'pageNum'=>$next)).'" class="NextBut"><span>Next page</span>&nbsp;</a></li>';
		}
		$paginator .= '</ul>';
		if($total_pgs<=1 || $qs_keywords) $paginator = '';
	
	
		/// Display results
		if ($num_sql>0) {
			$i = 0;
			while($i<$num_sql){
				$row = mysqli_fetch_array($result1);
				$id = $row['id'];

				if($id){
					$itemArray_query = "SELECT c.*,cc.category AS categoryName,csc.subcategory AS subcategoryName";
					$itemArray_query .= " FROM catalogue AS c,catalogue_cats AS cc,catalogue_subcats AS csc";
					$itemArray_query .= " WHERE c.id=$id AND c.category=cc.id AND c.subcategory=csc.id LIMIT 1";
					$itemArray_result = mysqli_query($dbc, $itemArray_query);
					if($itemArray_result && mysqli_num_rows($itemArray_result)==1){
						$row = mysqli_fetch_array($itemArray_result);
						$itemArray[] = $row;	
						$i++;				
					}
				}
			}
		}
		
		if(sizeof($itemArray)>0){
			//if(sizeof($itemArray) >= 4 && $searching) 
			//$ItemStyle = "row";
			$first = false;
			//$catalogueItems .= $paginator;
			if($ItemHighlighted){
				$itemsPerPage = sizeof($itemArray)-1;
				//$echo .= '<br>DEDUCT 1';
			}else{
				$itemsPerPage = sizeof($itemArray);
			}
			//$ii = 0;
			if($categoryID==$clientCats['Classifieds']){
				$catalogueItems .= '<ul class="itemBox">';
			}else{
				$catalogueItems .= '<ul class="itemRow">';
			}
			for($i=0;$i<$itemsPerPage;$i++){
				//if($SiteFunctions->DateValid($itemArray[$i]['upload_date'],$itemArray[$i]['spare_date'])){
					//$ii++;
					//if($ii==1 || !is_float($ii/4) || !is_float($ii/5)){
					if($i==1 || !is_float($i/4) || !is_float($i/5)){	
						$itemClass = 'featureGrid blue';
					}else{
						$itemClass = 'featureGrid';
					}
					$attributes = array('itemArray'=>$itemArray[$i],'itemStyle'=>$ItemStyle,'itemClass'=>$itemClass,'itemPage'=>$itemPage);
					$catalogueItems .= $Catalogue->ItemPreview($attributes);
				//}
			}
			$catalogueItems .= '</ul>';
			/*
			$catalogueItems .= '<br>'.$sql.'<br>';
			$catalogueItems .= "Results: $totals <br>";
			$catalogueItems .= "Viewing page $pg of $total_pgs<br>";
			*/
		}
		/// END /// Query Database	
	}
	
	if($first) {
		$ResultsTitle = '<h2>Your search criteria failed to find a match.</h2>';
		$catalogueItems = '<div class="error">'.$ResultsTitle;
		$catalogueItems .= '<p>Try amending search criteria or ';
		$catalogueItems .= '<a href="'.$_SERVER['PHP_SELF'].'" title="Reload Page">click here to reload page</a>';
		$catalogueItems .= '</p></div>';
	}else{

		if($qs_keywords){
			$ResultsTitle = '<h1>';
			if($totals==$searchLimit){
				$ResultsTitle .= 'LIMITED to '.$searchLimit;
			}else{
				$ResultsTitle .= $totals;
			}
			$ResultsTitle .= '&nbsp;Matches for \'<span class="highlightWords">'.$qs_keywords.'</span>\''.$ResultsTitleAppend.'</h1>';
		}else{

			if($subcategoryID && $subcategoryName){
				$ResultsTitle = ucwords($subcategoryName);
			}else{
				$ResultsTitle = 'Classic Cars';
			}
			if($status==1){
				$tmpTitle .= $totals.'&nbsp;'.$ResultsTitle.' For Sale';
			}else{
				$tmpTitle .= $ResultsTitle.' SOLD';
			}
			$ResultsTitle = '<h1>'.$tmpTitle.'</h1>';
		}
			

		if($searching){
			if($searchTip){
				$SearchTitle = '<div class="tip">';
			}else{
				$SearchTitle = '<div class="good">';
			}

			$SearchTitle .= $ResultsTitle;
			if($ResultsSubTitle) $SearchTitle .= $ResultsSubTitle;
			$SearchTitle .= '</div>';
			$ResultsTitle = $SearchTitle;
		}
		
		$tmp_catalogueItems = '';		
		/*
		$tmp_catalogueItems .= <<<EOD
<div class="menu">
<ul>
<li class="sub"><a href="#nogo">Products<!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul class="left">
	<li><a href="#nogo">Tripods</a></li>
	<li class="sub"><a href="#nogo">Cameras<!--[if IE 7]><!--></a><!--<![endif]-->

	<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul class="left">
			<li><a href="#nogo">Compact</a></li>
			<li><a href="#nogo">SLR</a></li>
		</ul>
	<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
	<li><a href="#nogo">Films</a></li>
	</ul>

<!--[if lte IE 6]></td></tr></table></a><![endif]-->

	</li>

</ul>
</div>
EOD;
*/
		if($categoryID==$clientCats['Classifieds']){
			$tmp_catalogueItems .= $ResultsTitle;
			$tmp_catalogueItems .= '<div id="Catalogue">';
			$tmp_catalogueItems .= $catalogueItems;
			if($paginator) $tmp_catalogueItems .= $paginator;
			$tmp_catalogueItems .= '</div>';
		}else{
			if($paginator) $tmp_catalogueItems .= $paginator;
			$tmp_catalogueItems .= $catalogueItems;
			if($paginator) $tmp_catalogueItems .= $paginator;
		}
		//$tmp_catalogueItems .= '</div>';

		
		
		$catalogueItems = $tmp_catalogueItems;
	}
	
	return $catalogueItems;
	
?>