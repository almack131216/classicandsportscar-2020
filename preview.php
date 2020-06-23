<?php
	include("inc/classes/ClientData.php");
	include("inc/connect_2_db.php");
		
		
	$uid = $_GET['uid'];
	$query = "SELECT id,category,id_xtra FROM catalogue WHERE id=$uid LIMIT 1";
	$result = mysqli_query($dbc, $query);
	
	if($result && mysqli_num_rows($result)==1){
		$row = mysqli_fetch_array($result);
		$MasterID = $row['id'];
		if(!empty($row['id_xtra'])) $MasterID = $row['id_xtra'];

		switch($MasterID){
			case 210:
			case 229:$JumpToPage = "index.php";break;

			default:
				switch($row['category']){
					case $clientCats['Plates']:$JumpToPage = "plates.php";break;
					case $clientCats['Classifieds']:$JumpToPage = "details.php?uid=".$uid;break;				
				}
		}
		
	}
	header ("Location: ".$client['siteroot'].$JumpToPage);
	
?>