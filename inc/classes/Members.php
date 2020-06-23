<?php								
			
	Class Members {

		///////////////////////////////////
		/// FUNCION // IS MEMBER LOGGED IN?
		function LoggedIn() {
			if ( !empty($_SESSION['MemberID']) AND !empty($_SESSION['MemberName']) AND (substr($_SERVER['PHP_SELF'], -19) != 'members_signout.php')) {
				return true;
			} else {
				return false;
			}
		}
		/// END ///	
		
		///////////////////////////////////
		/// FUNCION // SHOW ORIGINAL PRICE
		function DiscountPrice($getPrice){
			global $Members;
			if($Members->LoggedIn() && $getPrice>=1){
				$getPrice = number_format($getPrice*0.9,2);				
			}			
			return $getPrice;
		}
		
		///////////////////////////////////
		/// FUNCION // SHOW ORIGINAL PRICE
		function ShowDiscountPrice($getPrice){
			global $Members, $CMSTextFormat;
			if($Members->LoggedIn() && $getPrice>=1){
				$OriginalPrice = $CMSTextFormat->Price_StripDecimal($getPrice);
				return '&nbsp;&nbsp;<span style="text-decoration:line-through;color:#999;">'.$OriginalPrice.'</span>';
			}
		}


		//////////////////////////////
		/// FUNCION // ADD NEW MEMBER
		//(example): $attributes = array('fullname'=>$FullName,'email'=>$EmailAddress,'password'=>$Password);
		//$Members->AddMember($attributes);
		function AddMember($getAttributes){
			global $Members,$CMSTextFormat;
			
			$FullName = $getAttributes['FullName'];
			$EmailAddress = $getAttributes['EmailAddress'];
			$Password = $getAttributes['Password'];
			
			$TheDayToday = date('Y-m-d');

			$insertQuery = "INSERT INTO tbl_members (Id,fname,email,pword,registered) VALUES ('0','$FullName','$EmailAddress','$Password','$TheDayToday')";
			$insertResult = mysql_query($insertQuery);
			if($insertResult){
				return true;
			}else{
				echo 'QUERY:'.$insertQuery;
				echo '<br>'.mysql_error();
				return false;
			}

		}
		
	}	
	$Members = new Members();
?>