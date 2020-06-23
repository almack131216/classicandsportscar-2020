<?php

	$ContactForm = '';
	
	if(!empty($_GET['uid']))		$Enquiry = $_GET['uid'];
	if(!empty($_GET['subject']))	$Comments = $_GET['subject']."\r\n";
	
	//include Form Classes	
	include("inc/classes/FormCheck.php");
	include("inc/classes/BuildForm.php");
	
	$Colon = "<br>";//&nbsp;&#58;&nbsp;
	$Exclamation = "&#40;&#33;&#41;";
	$Asterisk = "&#42;&nbsp;";

	$PostAction = $_SERVER['PHP_SELF'];//.'?currPage='.$currentpage;
	$Mailto = $client['email']; //default email address in system
	$Mailto_cc = $client['email_wm'];
	$SubjectTitle = $client['name'].": General Enquiry";//default subject for email
	
	$CustomFields = array(	'FirstName' => array('id'=>'FirstName','label'=>'Your First Name','value'=>'','error'=>'Your first name is required'),
							'Surname' => array('id'=>'Surname','label'=>'Your Surname','value'=>'','error'=>'Your surname name is required'),
							'EmailAddress' => array('id'=>'EmailAddress','label'=>'Your Email','value'=>'','error'=>'Your email address is required'),
							'Address' => array('id'=>'Address','label'=>'Address','value'=>'','error'=>'Address required'),
							'PostCode' => array('id'=>'PostCode','label'=>'Post Code','value'=>'','error'=>'Post Code required'),
							'Telephone' => array('id'=>'Telephone','label'=>'Contact No','value'=>'','error'=>'Contact No required'),
							'Mobile' => array('id'=>'Mobile','label'=>'Mobile','value'=>'','error'=>'Mobile required'),
							'Make' => array('id'=>'Make','label'=>'Make','value'=>'','error'=>'Make required'),
							'Model' => array('id'=>'Model','label'=>'Model','value'=>'','error'=>'Model required'),
							'Year' => array('id'=>'Year','label'=>'Year','value'=>'','error'=>''),
							'Colour' => array('id'=>'Colour','label'=>'Colour','value'=>'','error'=>''),
							'Comments' => array('id'=>'Notes','label'=>'Further Notes','value'=>'','error'=>''),						
							'Submit' => array('id'=>'Submit','label'=>'Send','value'=>'Send','error'=>'Try Again'));
	
	$Colon = "&nbsp;&#58;";//
	//year,colour,t-bar,mileage,price,description
	$UseFields = array("FirstName","Surname","EmailAddress","Address","PostCode","Telephone","Mobile","Make","Model","Year","Colour","Comments","Submit");
	$Mandatory = array("FirstName","Surname","PostCode","EmailAddress","Make","Model");
	
	$PrintIndividualErrors = true;
	$ErrorMessage = array(	'PageTitle'=>"",							
							'Message'=>$Exclamation." Form error. Please correct the red boxes.",
							'EmailAddressInvalid'=>"The email address entered is not valid");
	
	for($i=0;$i<sizeof($UseFields);$i++){
		${"Class_".$UseFields[$i]} = "normal";
		${$UseFields[$i]} = $CustomFields[$UseFields[$i]]['value'];		
		//echo '<br>->'.$UseFields[$i].' -> '.${$UseFields[$i]}.' / '.$CustomFields[$UseFields[$i]]['id'];
	}

					
	//Messages & Feedback							
	$DefaultMessage="";											
	$SubjectTitle = "Website Car Request";
	$PostAction = $_SERVER['PHP_SELF'];
	$Mailto = $client['email']; //default email address in system
	$MailtoCC = "sales@grundymack.com";
	$MailtoBCC = $client['email_wm'];
	$MessageSent='<h2>Thank you...</h2>';
	$MessageSent.='<p>We will be in touch with you soon.</p>';
	$MessageSent.='<p><a href="'.$client['homepage'].'" class="BackBut" title="Return to homepage">Back to homepage</a></p>';
	
	$MessageFailed = array(	'Title'=>'<h2>Please try again</h2>',
							'Message'=>"We were unable to receive your form this time.");
	
	/*////////////////////////////////////////////////////////////////////////////
	//////////	SHOULDN'T NEED TO CHANGE ANYTHING BEYOND THIS POINT!	//////////
	////////////////////////////////////////////////////////////////////////////*/
	
	if($_POST['Submit']){
		$ErrorLog = array();
		$Message = ""; //For sending via online form
		$MessageB = ""; //For sending via email client
		
		if(!empty($_POST['Enquiry'])) $SubjectTitle = $client['name'].": ".$_POST['Enquiry'];
		

		$BuildForm->CheckField("FirstName");
		$BuildForm->CheckField("Surname");
		$BuildForm->CheckField("EmailAddress");
		$BuildForm->CheckField("Telephone");
		$BuildForm->CheckField("Mobile");
		$BuildForm->CheckField("Address");
		$BuildForm->CheckField("PostCode");
		$BuildForm->CheckField("Make");
		$BuildForm->CheckField("Model");
		$BuildForm->CheckField("Year");
		$BuildForm->CheckField("Colour");		
		$BuildForm->CheckField("Comments");
		
		
		if(!empty($ErrorLog)){
			$PrintErrorMessage = '<p class="error">';
			$PrintErrorMessage .= $ErrorMessage['Message'];
			if($PrintIndividualErrors){
				for($ErrorCount=0;$ErrorCount<count($ErrorLog);$ErrorCount++){
					$PrintErrorMessage .= '<br />-&nbsp;'.$ErrorLog[$ErrorCount];
				}
			}
			$PrintErrorMessage .= '</p>';
		}else{
			
			$email_from = $Mailto;
			ini_set("sendmail_from", $email_from);
			$to = $email_from;
			$cc = $MailtoCC;
			$bcc = $MailtoBCC;
			$toName = $client['name'].' Web';
			$subject = $SubjectTitle;
			   
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			   
			// Additional headers
			$headers .= "To: $toName<$to>"."\r\n";
			$headers .= "From: $FirstName $Surname<$EmailAddress>"."\r\n";
			$headers .= "Reply-To: ".$EmailAddress."\n";
			if($cc)		$headers .= "Cc: $cc"."\n";
			if($bcc)	$headers .= "Bcc: $bcc";
			//$headers .= 'Cc: Design Community <alex@design-community.com>'. "\r\n";
			//$headers .= 'Bcc: John <john@example.com>, Peter <peter@example.com>'. "\r\n";
			
			$MessageB = str_replace("\n","%0A",$Message);//For sending via email client
			$Message = str_replace("\n","<br />",$Message);//For sending via online form
			//$Message = str_replace("---------------------------","<hr/>",$Message);
			//$Message = nl2br($Message);
	
			if(@mail($to,$subject,$Message,$headers,'-f'.$email_from)){
				$ContactForm .= $MessageSent;
				$emailSent = true;
			}else{
				$CustomFields['Submit'] = $CustomFields['Submit_TryAgain'];
				$PrintErrorMessage = $MessageFailed['Title'];
				$PrintErrorMessage .= '<p><span class="error">'.$MessageFailed['Message'].'</span>';				
				$PrintErrorMessage .= '<br />Try again or <a href="mailto:'.$Mailto.'?subject='.$SubjectTitle.'&body='.$MessageB.'" title="Send message using your personal email software">re-send using your own email software</a>.</p>';	
			}
		}
				
	}else{					
		$ContactForm .= $DefaultMessage;
	}
	
	
	// PRINT FORM
	if(!$emailSent){
				$ContactForm .= '<p><span class="mandatory">*</span> = Mandatory Field</p>';
		//echo '(FB)Mailto:'.$Mailto;		
		$ContactForm .= '<form action="'.$PostAction.'" method="post" name="FormRequest" id="FormContainer" class="FullWidth">'."\r\n";
		//$ContactForm .= '<fieldset>';

		$ContactForm .= '<hr>';
		$ContactForm .= '<h2>Your Contact Details...</h2>';
		
		if(in_array("Gender",$UseFields)){
			$ContactForm .= '<span class="row">';
				$ContactForm .= '<label for="Gender">'.$CustomFields['Gender'].$Colon.'</label>';
				$ContactForm .= '<span class="RadioBox">';					
					$ContactForm .= '<input type="radio" class="radio" name="Gender" value="'.$CustomFields['Gender_a'].'" '.CheckChecked($Gender,$CustomFields['Gender_a']).'>&nbsp;'.$CustomFields['Gender_a'];
					$ContactForm .= '&nbsp;&nbsp;&nbsp;<input type="radio" class="radio" name="Gender" value="'.$CustomFields['Gender_b'].'" '.CheckChecked($Gender,$CustomFields['Gender_b']).'>&nbsp;'.$CustomFields['Gender_b'];
				$ContactForm .= '</span>';
			$ContactForm .= '</span>';
		}
		// END //

		

		$ContactForm .= $BuildForm->PrintField("FirstName");
		$ContactForm .= $BuildForm->PrintField("Surname");
		$ContactForm .= $BuildForm->PrintField("EmailAddress");
		
		$attributes = array('id'=>"Address",'type'=>"textarea",'rows'=>3,'cols'=>45);
		$ContactForm .= $BuildForm->PrintField($attributes);

		$ContactForm .= $BuildForm->PrintField("PostCode");
		$ContactForm .= $BuildForm->PrintField("Telephone");
		$ContactForm .= $BuildForm->PrintField("Mobile");
		
		$ContactForm .= '<hr>';
		$ContactForm .= '<h2>Details of the car you\'re looking for...</h2>';
		
		////////////////// Custom Fields		
		$ContactForm .= $BuildForm->PrintField("Make");
		$ContactForm .= $BuildForm->PrintField("Model");
		$ContactForm .= $BuildForm->PrintField("Year");
		$ContactForm .= $BuildForm->PrintField("Colour");
		
		$attributes = array('id'=>"Comments",'type'=>"textarea",'rows'=>6,'cols'=>45);
		$ContactForm .= $BuildForm->PrintField($attributes);
		
		$attributes = array('id'=>"Submit",'type'=>"submit",'labelClass'=>"hidden");
		$ContactForm .= $BuildForm->PrintField($attributes);

		
		//$ContactForm .= '</fieldset>';
		$ContactForm .= '</form>';
		$ContactForm .= '<p>&nbsp;</p>';
		
	}
	
	return $ContactForm;
			
?>