<?php
// Send simple email without DKIM SIgning
// YOUR E-MAIL
$name = 'Qflash.pl';
$sender = 'info@qflash.pl';
$to = 'fxstareu@gmail.com';
$subject = 'Simple html message without dkim';

$headers =
'MIME-Version: 1.0
From: "'.$name.'" <'.$sender.'>
Content-type: text/html; charset=utf8';

$message =
'<html>
	<header></header>
	<body>
		Hello, this a DKIM test e-mail
	</body>
</html>';

// 1) YOU USUALLY DID (simple mail without dkim)
ini_set('sendmail_from', $sender);
echo mail($to, $subject, $message, $headers);