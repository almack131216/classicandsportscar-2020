<?php
	
	Class SEO {		
		
		////////////////////////////////////////////////
		/// Sanitize URL TO and FROM the browser address
		function sanitize($getAttributes){

			if(is_array($getAttributes)){
				$To = $getAttributes['to'];
				$From = $getAttributes['from'];
				$tmp = $getAttributes['string'];
				if(!$getAttributes['string'] && $getAttributes['url']) $tmp = $getAttributes['url'];
			}else{
				$To = true;
				$tmp = $getAttributes;
			}
			

			$tmp = str_replace("&nbsp;"," ",$tmp);
			$tmp = str_replace("&#39;","'",$tmp);
			$tmp = str_replace("&pound;","",$tmp);
			
			$tmp = trim($tmp);
			$remove_these = array(':',';',',','`','"','’','\'','\\','/','(',')','%','$','£','!','?');
			$tmp = str_replace($remove_these,'',$tmp);

			if($To){
				//$tmp = str_replace("+","&#126;",$tmp);
				$tmp = str_replace("-","_",$tmp);
				$tmp = str_replace(" ","-",$tmp);				
				$tmp = str_replace("---","-",$tmp);
				$tmp = str_replace("--","-",$tmp);
				$tmp = str_replace("&","",$tmp);
			}elseif($From){	
				$tmp = str_replace(array("é","Ã©"),"e",$tmp);				
				$tmp = str_replace(array("~","&#126;"),"+",$tmp);				
				$tmp = str_replace("-"," ",$tmp);
				$tmp = str_replace("_","-",$tmp);
			}else{
				$tmp = $getString;
			}
			return $tmp;
		}
		/// END ///

	}
	$SEO = new SEO();

?>