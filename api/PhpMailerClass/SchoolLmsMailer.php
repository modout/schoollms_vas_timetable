<?php
require 'class.phpmailer.php';

class SchoolLmsMailer{
	
	function ProcessMail($to_email,$to_fullname,$message,$subject,$attachement= null,$isHTML = true)
	{
		$mail = new PHPMailer;

		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'mail.sipnet.co.za';  // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'schoollms@sipnet.co.za';                            // SMTP username
		$mail->Password = '12_s5ydw3ll';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

		$mail->From = 'schoollms@sipnet.co.za';
		$mail->FromName = 'School LMS';
		$mail->AddAddress($to_email, $to_fullname);  // Add a recipient
		$mail->AddReplyTo('schoollms@sipnet.co.za', 'School LMS');
		$mail->WordWrap = 100;  

		if($attachement != null)
		{
			if(is_array($attachement))
			{
				foreach($attachement as $value)
				{
					$mail->AddAttachment($value);  // Add attachments
				}
			}
			else{
				$mail->AddAttachment($attachement);  // Add attachments
			}
		}
		//$mail->AddAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->IsHTML($isHTML); 
		
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
		//echo "We are here...";
		if(!$mail->Send())
		{
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			//die();
			return false;
		}
		else{
			echo "email sent<br/>";
			//die();
			return true;
		}
		//return $mail->Send();
		
	}
	
}




?>