<?php

	Class BuildForm {
		
		////////////////////////////////////////////////////////////////
		// Print fields with labels determined by '$getAttributes' value
		// accepts array or just FieldName on its own
		// SYNTAX 1: $BuildForm->PrintField(array('id'=>"Address",'type'=>"textarea",'rows'=>3,'cols'=>45));
		// SYNTAX 2: $BuildForm->PrintField("FirstName");
		function PrintField($getAttributes){
			global $Colon,$UseFields,$Mandatory,$CustomFields;
			global ${$getAttributes['id']},${"Class_".$getAttributes['id']};//if using data in array
			global ${$getAttributes},${"Class_".$getAttributes};//if using just FieldName			
				
			if(is_array($getAttributes)){
				$FieldID = $getAttributes['id'];
				$FieldValue = ${$getAttributes['id']};//$getAttributes['value'];
				$FieldClass = ${"Class_".$getAttributes['id']};				
				$FieldType = $getAttributes['type'];
				$FieldCols = $getAttributes['cols'];//for textarea
				$FieldRows = $getAttributes['rows'];//for textarea
				$LabelClass = $getAttributes['labelClass'];
			}else{
				$FieldID = $getAttributes;
				$FieldValue = ${$getAttributes};
				$FieldClass = ${"Class_".$getAttributes};
			}
			
			if(in_array($FieldID,$UseFields)){
				$FieldData='';
				//if($Members->LoggedIn() && empty(${$FieldID})) ${$FieldID} = $_SESSION['MemberName'];
				//if($Members->LoggedIn() && empty($EmailAddress)) $EmailAddress = $_SESSION['EmailAddress'];
				$FieldData .= '<span class="row">';
					$FieldData .= '<label for="'.$FieldID.'"';
					if($LabelClass) $FieldData .= ' class="'.$LabelClass.'"';
					$FieldData .= '>';
					if(in_array($FieldID,$Mandatory)) $FieldData .= '<span>&#42;</span> ';
					$FieldData .= $CustomFields[$FieldID].$Colon.'</label>';
					
					switch($FieldType){
						case "textarea":
							$FieldData .= '<textarea id="'.$FieldID.'" name="'.$FieldID.'" class="'.$FieldClass.'"';
							if($FieldCols && $FieldRows) $FieldData .= 'cols="'.$FieldCols.'" rows="'.$FieldRows.'"';
							$FieldData .= '>'.$FieldValue.'</textarea>';break;
							
						case "submit":
							$FieldData .= '<input type="submit" id="'.$FieldID.'" name="'.$FieldID.'" value="Send Request" onsubmit="return false;">';break;
							
						default:
							$FieldData .= '<input type="text" id="'.$FieldID.'" name="'.$FieldID.'" value="'.$FieldValue.'" class="'.$FieldClass.'">';break;
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
			global ${"Class_".$getField};
			
			$getFieldID = $getField;
			
			if(in_array($getFieldID,$UseFields)){
				if(!empty($_POST[$getFieldID])){
					${$getField} = $_POST[$getFieldID];
					
					//If Email address then perform a validation check
					if($getFieldID=="EmailAddress" && !$Form->valid_email(${$getField}) && in_array($getFieldID,$Mandatory)){
						array_push($ErrorLog, $ErrorMessage["EmailAddressInvalid"]);
						$Class_EmailAddress = "error";	
					}else{
					//Otherwise, all is well
						$Message.=$CustomFields[$getFieldID].": {${$getField}}\n\n";
					}						
					
				}else{
					if(in_array($getFieldID,$Mandatory)){
						array_push($ErrorLog, $ErrorMessage[$getFieldID]);
						${"Class_".$getFieldID} = "error";
					}
				}
			}
		
		}
		/// END ///
		
	}
	$BuildForm = new BuildForm();

?>