<?php
// Send simple email without DKIM SIgning
require_once 'mail-signature.class.php';
require_once 'mail-signature.config.php';

// YOUR E-MAIL
$name = 'Qflash.pl';
$sender = 'info@qflash.pl';
$to = 'fxstareu@gmail.com';
$subject = 'My subject';

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

// USE OPTIONS TO ADD SOME FLAVOR :
// Make sure linefeeds are in CRLF format - it is essential for signing
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

$signature = new mail_signature(
	MAIL_RSA_PRIV,
	MAIL_RSA_PASSPHRASE,
	MAIL_DOMAIN,
	MAIL_SELECTOR,
	$options
);
$signed_headers = $signature -> get_signed_headers($to, $subject, $message, $headers);
// Send email
ini_set('sendmail_from', $sender);
echo mail($to, $subject, $message, $signed_headers.$headers);
