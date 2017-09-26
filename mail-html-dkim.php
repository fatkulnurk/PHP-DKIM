<?php
// Send simple email without DKIM SIgning
// YOUR E-MAIL
$name = 'Qflash.pl';
$sender = 'info@qflash.pl';
$to = 'fxstareu@gmail.com';
$subject = 'Simple html message with dkim';

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

// NOW YOU WILL DO (after setting up the config file and your DNS records) :
// Make sure linefeeds are in CRLF format - it is essential for signing
$message = preg_replace('/(?<!\r)\n/', "\r\n", $message);
$headers = preg_replace('/(?<!\r)\n/', "\r\n", $headers);

require_once 'mail-signature.class.php';
require_once 'mail-signature.config.php';

$signature = new mail_signature(
	MAIL_RSA_PRIV,
	MAIL_RSA_PASSPHRASE,
	MAIL_DOMAIN,
	MAIL_SELECTOR
);
$signed_headers = $signature -> get_signed_headers($to, $subject, $message, $headers);

// Send email
ini_set('sendmail_from', $sender);
echo mail($to, $subject, $message, $signed_headers.$headers);
die();