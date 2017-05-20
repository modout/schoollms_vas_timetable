<?php

include("lib/PhpMailerClass/Mailer.php");

$mailer = new Mailer();
if($mailer->ProcessMail("siphiwo@ekasiit.com","Siphiwo","This is a test","Test",null,true))
{
	echo "Sent";
}
else{
	echo "Nope";
}

?>