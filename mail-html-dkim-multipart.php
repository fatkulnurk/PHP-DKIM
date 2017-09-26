<?php
// Send simple html message

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
$nmessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
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


// 3) OR USE OPTIONS TO ADD SOME FLAVOR :

$message = preg_replace('/(?<!\r)\n/', "\r\n", $message);
$headers = preg_replace('/(?<!\r)\n/', "\r\n", $headers);

$options = array(
	'use_dkim' => false,
	'use_domainKeys' => true,
	'identity' => MAIL_IDENTITY,
	// if you prefer simple canonicalization (though the default "relaxed"
	// is recommended)
	'dkim_body_canonicalization' => 'simple',
	'dk_canonicalization' => 'nofws',
	// if you want to sign the mail on a different list of headers than the
	// default one (see class constructor). Case-insensitive.
	'signed_headers' => array(
		'message-Id',
		'Content-type',
		'To',
		'subject'
	)
);

require_once 'mail-signature.class.php';
require_once 'mail-signature.config.php';

$signature = new mail_signature(
	MAIL_RSA_PRIV,
	MAIL_RSA_PASSPHRASE,
	MAIL_DOMAIN,
	MAIL_SELECTOR,
	$options
);
$signed_headers = $signature -> get_signed_headers($to, $subject, $message, $headers);

// echo mail($to, $subject, $message, $signed_headers.$headers);
