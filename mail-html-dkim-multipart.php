<?php
// Send multipart mime message with dkim

// YOUR E-MAIL
$name = 'Qflash.pl';
$sender = 'info@qflash.pl';
$to = 'fxstareu@gmail.com';
$subject = 'My subject';

$headers =
'MIME-Version: 1.0
From: "Sender" <'.$sender.'>
Content-type: text/html; charset=utf8';

$message =
'<html>
	<header></header>
	<body>
		Hello, this a DKIM test e-mail
	</body>
</html>';

$file = 'img.jpg';
$content = file_get_contents($file);
$content = chunk_split(base64_encode($content));
$uid = md5(uniqid(time()));
$filename = basename($file);

$boundary = microtime();
$header = "From: ".$name." <".$sender.">\r\n";
$header .= "Reply-To: ".$sender."\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n\r\n";

// message & attachment
$nmessage = "--".$boundary."\r\n";
// $nmessage .= "Content-type:text/html; charset=iso-8859-2\r\n";
// $nmessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
$nmessage .= "Content-type:text/html; charset=utf-8\r\n";
$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$nmessage .= $message."\r\n\r\n";
$nmessage .= "--".$boundary."\r\n";
$nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
$nmessage .= "Content-Transfer-Encoding: base64\r\n";
$nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
$nmessage .= $content."\r\n\r\n";
$nmessage .= "--".$boundary."--";

// NOW YOU WILL DO (after setting up the config file and your DNS records) :
// multipart mimie
// Make sure linefeeds are in CRLF format - it is essential for signing
$message = preg_replace('/(?<!\r)\n/', "\r\n", $nmessage);
$headers = preg_replace('/(?<!\r)\n/', "\r\n", $header);

require_once 'mail-signature.class.php';
require_once 'mail-signature.config.php';

$signature = new mail_signature(
	MAIL_RSA_PRIV,
	MAIL_RSA_PASSPHRASE,
	MAIL_DOMAIN,
	MAIL_SELECTOR
);
// text/html
$signed_headers = $signature -> get_signed_headers($to, $subject, $message, $headers);

// send message
ini_set('sendmail_from', $sender);
echo mail($to, $subject, $message, $signed_headers.$headers);
echo ' Send with attachment '.$filename;
// script end
die();
