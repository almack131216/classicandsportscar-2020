<?php

	Class BuildForm {
		
		////////////////////////////////////////////////////////////////
		// Print fields with labels determined by '$getAttributes' value
		// accepts array or just FieldName on its own
		// SYNTAX 1: $BuildForm->PrintField(array('id'=>"Address",'type'=>"textarea",'rows'=>3,'cols'=>45));
		// SYNTAX 2: $BuildForm->PrintField("FirstName");
		function PrintField($getAttributes){
			global $BuildForm,$Colon,$UseFields,$Mandatory,$CustomFields;			
			
			if(is_array($getAttributes)){
				//global ${$getAttributes['id']},${"Class_".$getAttributes['id']};//if using data in array				
				$FieldID = $getAttributes['id'];
				$FieldValue = ${$getAttributes['id']};//$getAttributes['value'];
				//$FieldValue = ${$FieldID};
				$FieldType = $getAttributes['type'];
				$FieldCols = $getAttributes['cols'];//for textarea
				$FieldRows = $getAttributes['rows'];//for textarea
				$LabelClass = $getAttributes['labelClass'];
			}else{
				global ${$getAttributes};//if using just FieldName			
				$FieldID = $getAttributes;
				$FieldValue = ${$getAttributes};				
			}
			global ${$FieldID},${"Class_".$FieldID};
			//echo '<br>'.${$FieldID}.','.${"Class_".$FieldID};
			$FieldValue = ${$FieldID};
			$FieldClass = ${"Class_".$FieldID};
			if(strtolower($FieldID)=="submit") $FieldType = "submit";
			
			if(in_array($FieldID,$UseFields)){
				$FieldData='';
				//if($Members->LoggedIn() && empty(${$FieldID})) ${$FieldID} = $_SESSION['MemberName'];
				//if($Members->LoggedIn() && empty($EmailAddress)) $EmailAddress = $_SESSION['EmailAddress'];
				$FieldData .= '<span class="row">';
					$FieldData .= '<label for="'.$FieldID.'"';
					if($LabelClass) $FieldData .= ' class="'.$LabelClass.'"';
					$FieldData .= '>';
					if(in_array($FieldID,$Mandatory)) $FieldData .= '<span>&#42;</span> ';
					$FieldData .= $CustomFields[$FieldID]['label'].$Colon.'</label>';
					
					switch($FieldType){
						case "textarea":
							$FieldData .= '<textarea id="'.$FieldID.'" name="'.$FieldID.'" class="'.$FieldClass.'"';
							if($FieldCols && $FieldRows) $FieldData .= 'cols="'.$FieldCols.'" rows="'.$FieldRows.'"';
							$FieldData .= '>'.$FieldValue.'</textarea>';break;
							
						case "submit":
							$FieldData .= '<input type="submit" id="'.$FieldID.'" name="'.$FieldID.'" value="Send Request">';break;//onsubmit="return false;"
							
						case "radio":
							//$FieldData .= '<span class="floatRight">';
								$FieldData .= '<input type="hidden" class="radio" id="'.$FieldID.'" name="'.$FieldID.'" value="'.$FieldValue.'">';
								$FieldData .= '<br/>';
								for($i=0;$i<5;$i++){
									if($CustomFields[$FieldID]['value'.$i]){
										$FieldData .= '<input type="radio" class="radio" name="'.$FieldID.'Radio" value="'.$CustomFields[$FieldID]['value'.$i].'" onclick="UpdateRadioValue(\''.$FieldID.'\',\''.$CustomFields[$FieldID]['value'.$i].'\');" '.$BuildForm->CheckChecked($FieldID,$CustomFields[$FieldID]['value'.$i]).'>';
										$FieldData .= '&nbsp;'.$CustomFields[$FieldID]['value'.$i];
										$FieldData .= '&nbsp;&nbsp;&nbsp;';
									}
								}
							//$FieldData .= '</span>';
							break;
							
						case "date":
						case "datetime":
							$FieldData .= '<span class="floatRight">';
							$FieldData .= '<input type="text" id="'.$FieldID.'" name="'.$FieldID.'" value="'.$FieldValue.'" class="'.$FieldClass.'" readonly="readonly">';
							$FieldData .= '<br/><a href="javascript:NewCssCal(\''.$FieldID.'\',\'yyyymmdd\',\'arrow\',false);" class="DateSelect">Edit</a>';
							$FieldData .= ' &#124; <a href="Javascript:clearDate(\''.$FieldID.'\');" class="DateClear" title="Clear Date">Clear</a>';
							$FieldData .= '</span>'."\r\n";
							break;
		
	
						default:
							$FieldData .= '<span class="floatRight">';
							$FieldData .= '<input type="text" id="'.$FieldID.'" name="'.$FieldID.'" value="'.$FieldValue.'" class="'.$FieldClass.'">';
							$FieldData .= '</span>'."\r\n";
							break;
							
					}
				$FieldData .= '</span>'."\r\n";
				
				return $FieldData;
			}
		}
		/// END ///
		
		
		///////////////
		/// CHECK FIELD
		function CheckField($getField){
			global $Form,$UseFields,$Mandatory,$CustomFields;
			global $ErrorLog,$ErrorMessage;
			global $Message,$MessageB;
			global ${"Class_".$getField},${$getField};
			
			$getFieldID = $getField;
			//echo '<br>'.$CustomFields[$getFieldID]['value'].' - ('.$_POST[$getFieldID].')';
			if(in_array($getFieldID,$UseFields)){
				if(!empty($_POST[$getFieldID]) && $_POST[$getFieldID]!=$CustomFields[$getFieldID]['value']){
					${$getField} = $_POST[$getFieldID];
					
					//If EmailAddress then perform a validation check
					if($getFieldID=="EmailAddress" && !$Form->valid_email(${$getField}) && in_array($getFieldID,$Mandatory)){
						array_push($ErrorLog, $ErrorMessage["EmailAddressInvalid"]);
						${"Class_".$getFieldID} = "error";	
					}else{
					//Otherwise, all is well
						$Message.=$CustomFields[$getFieldID]['label'].": {${$getField}}\n\n";
					}						
					
				}else{
					if(in_array($getFieldID,$Mandatory)){
						array_push($ErrorLog, $CustomFields[$getFieldID]['error']);
						${"Class_".$getFieldID} = "error";
					}
				}
			}
		
		}
		/// END ///
		
		///////////////
		// CheckChecked
		function CheckChecked($getField,$getValue){
			global $CustomFields;
			//echo "if $getField = $getValue";
			if($_POST['Room']==$getValue){
				return "checked";//check radio selected
			//}else{
				//return "unchecked";
			}
		}
		/// END ///
		
		function AddCalendarTags(){
			global $jsTags,$cssTags;
			$jsTags = '<script type="text/javascript" src="js/calendar/datetimepicker_css.js"></script>';
			$cssTags .= '<link rel="stylesheet" href="js/calendar/rfnet.css" type="text/css" media="screen"/>';
		}
		
	}
	$BuildForm = new BuildForm();

?>