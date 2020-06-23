<?php
	
	Class ContactDetails {		
		
		////////////////////////////////////
		/// FUNCION // PRINT CONTACT DETAILS		
		function PrintContactDetails($getStyle){
			global $ShowMap,$client;
			
			$ContactDetails = '';
			
			if($getStyle=="header_left"){
				$ContactDetails .= '<p class="left">E: <a href="mailto:'.$client['email'].'" title="Email '.$client['email'].'">'.$client['email'].'</a>';
			}elseif($getStyle=="header_right"){
				$ContactDetails .= '<p class="right">Telephone: '.$client['tel'];
				if(!empty($client['fax'])) $ContactDetails .= '<br>Fax: '.$client['fax'];
				$ContactDetails .= '<br>Email: <a href="mailto:'.$client['email'].'" title="Email '.$client['name'].' via email">'.$client['email'].'</a></p>';
			}elseif($getStyle=="homepage"){
				$ContactDetails .= '<p><strong>Contact Us:</strong>';
				$ContactDetails .= '<br>'.$client['address'].', '.$client['postcode'];
				$ContactDetails .= '<br>Email: <a href="mailto:'.$client['email'].'" title="Email '.$client['email'].'">'.$client['email'].'</a>';
				$ContactDetails .= '<br>Telephone: '.$client['tel'].'</p>';
			}elseif($getStyle=="footer"){
				$ContactDetails .= '<p>'.$client['address'].', '.$client['postcode'];
				$ContactDetails .= ' &#124; T:&nbsp;'.$client['tel'];
				if($client['fax']) $ContactDetails .= ' &#124; F:&nbsp;'.$client['fax'];
				$ContactDetails .= '<br>E:&nbsp;<a href="mailto:'.$client['email'].'" title="Contact '.$client['name'].' via email">'.$client['email'].'</a>';
				$ContactDetails .= ' &#124; W:&nbsp;'.$client['web'];
				$ContactDetails .= ' &#124; <a href="/privacy" title="View our Privacy Policy">Privacy Policy</a>';
				$ContactDetails .= '</p>';
				/*$ContactDetails .= <<<EOD
<p>Classic & Sportscar Centre is the trading name of Classic & Sportscar Limited
<br>Registered No: 2598022. in England & Wales at the above address. &#124; VAT No: 548 0938 17</p>
EOD;*/

			}else{
				//$ContactDetails .= '<h1>'.$client['name'].'</h1>';
				
				
				$AddressLite = str_replace(", UK","",$client['address']);
				//$AddressLite = str_replace(", ","<br>",$AddressLite);				
				
				
				$ContactDetails .= '<p><strong>Address:</strong> '.$AddressLite.', '.$client['postcode'].', UK</p>';

				$ContactDetails .= '<p>';
				if(!empty($client['tel'])) $ContactDetails .= '<strong>Telephone:</strong> '.$client['tel'];
				if(!empty($client['mob'])) $ContactDetails .= '<br><strong>Mobile:</strong> '.$client['mob'];
				if(!empty($client['fax'])) $ContactDetails .= '<br><strong>Fax:</strong> '.$client['fax'];
				$ContactDetails .= '<br><strong>Email:</strong> <a href="mailto:'.$client['email'].'" title="Email '.$client['email'].'">'.$client['email'].'</a>';
				$ContactDetails .= '<br><strong>Web:</strong> <a href="http://'.$client['web'].'" title="Our Homepage">'.$client['web'].'</a>';
				$ContactDetails .= '</p>';
			}
			
			return $ContactDetails;
			
		}
		// END //
		
		
		function DetailsBox($getAttributes){
			global $SEO,$SEO_links,$Catalogue,$ShowMap,$client,$CMSTextFormat;
			
			$DetailsBox = '<div class="PreviewBox">';//id="ContactDetails"
			//$DetailsBox .= '<h1>Useful Links</h1>';
			
			if($getAttributes=="OpeningHours"){
				$isChristmas = false;
				if($_GET['xmas']) $isChristmas = true;
				
				if($isChristmas){
					$DetailsBox .= '<h3>Christmas Opening Times</h3>';
					$DetailsBox .= '<div class="OpeningHours">';
					
					$DetailsBox .= '<ul>';
					$DetailsBox .= '<li>Fri 20th 8:30am-1pm</li>';
					$DetailsBox .= '<li>Sat 21st CLOSED</li>';
					$DetailsBox .= '<li>Sun 22nd 10am-4pm</li>';
					$DetailsBox .= '<li>Mon 23rd 9am-5:30pm</li>';
					$DetailsBox .= '<li>Tue 24th 9am-1pm</li>';
					$DetailsBox .= '<li>--Christmas--</li>';
					$DetailsBox .= '<li>Fri 27th 9am-5:30pm</li>';
					$DetailsBox .= '<li>Sat 28th CLOSED</li>';
					$DetailsBox .= '<li>Sun 29th 10am-4pm</li>';
					$DetailsBox .= '<li>Mon 30th 9am-5:30pm</li>';
					$DetailsBox .= '<li>Tue 31st 9am-1pm</li>';
					$DetailsBox .= '</ul>';

					$DetailsBox .= '<p>Thursday 2nd open as normal</p>';
					

					$DetailsBox .= '</div>';
				}else{
					$OpeningHours = $Catalogue->GetItemData(8687);//Opening Hours
					$DetailsBox .= '<h3>'.$OpeningHours['name'].'</h3>';
					$DetailsBox .= '<div class="OpeningHours">'.$CMSTextFormat->SwapBreak($OpeningHours['description']).'</div>';
				}

				
				
			}elseif($getAttributes=="Contact"){
				$DetailsBox .= '<h3>'.$client['name'].', Malton</h3>';
				$DetailsBox .= '<p>Tel: '.$client['tel'];
				if(!empty($client['fax'])) $DetailsBox .= '<br>Fax: '.$client['fax'];
				$DetailsBox .= '</p>';
			}else{
				$DetailsBox .= '<h3>'.$client['name'].', Malton</h3>';
				$DetailsBox .= '<h4>'.$client['address'].', '.$client['postcode'].'</h4>';
				$DetailsBox .= '<ul class="ContactOptions">';
				$DetailsBox .= '<li><a href="'.$SEO_links->GenerateLink("transport").'" title="Car Transport Services" class="transport">Transport Services</a></li>';
				if($ShowMap){
					$DetailsBox .= '<li><a href="'.$SEO_links->GenerateLink("contact").'" title="Malton, North Yorkshire" class="map">Malton, N Yorkshire</a></li>';
				}else{
					$DetailsBox .= '<li><a href="'.$SEO_links->GenerateLink("contact_map").'" title="View Location on a Google Map" class="map">Find On Google Map</a></li>';
				}
				$DetailsBox .= '<li><a href="javascript:OpenClose(\'GetDirections\');" title="Get directions using Google Map" class="directions">Get Directions</a></li>';
				
				$DetailsBox .= '</ul>';
	
				$GetDirections='<div id="GetDirections" class="ExpandMe">';
				$GetDirections.='<form action="http://maps.google.co.uk/maps" method="get" target="_blank">';
				$GetDirections.='<label for="saddr">Enter your town / postcode and hit GO!</label><br>';
				$GetDirections.='<input type="text" name="saddr" id="saddr" value="">';
				$GetDirections.='<input type="submit" value="GO!" class="gmapGo">';
				$GetDirections.='<input type="hidden" name="daddr" value="'.$client['postcode'].'">';
				$GetDirections.='<input type="hidden" name="hl" value="en">';
				$GetDirections.='</form>';
				$GetDirections.='</div>';
				
				$DetailsBox .= $GetDirections;
			}
			$DetailsBox .= '</div>'."\r\n";
			
			return $DetailsBox;
		}
			
	}
	
	$ContactDetails = new ContactDetails();

?>