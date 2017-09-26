<?php
error_reporting('E_ALL');
set_time_limit(6000);
//header('Content-type: text/html; charset=utf-8');

// log file
$dir = (__DIR__);
$file = $dir.'/logs/SendLog-'.date('Y-m-d', time()).'.txt';

// phpmailer scripts
require('mailer/PHPMailerAutoload.php');
require('mailer/class.phpmailer.php');

$log = date('Y-m-d H:i:s', time())." ###RUN_SEND <br>\r\n";
file_put_contents($file, $log, FILE_APPEND);

// don't delete
require('config.php');

function Conn(){
global $host,$dbname,$user,$pass;
$connection = new PDO('mysql:host='.$host.';dbname='.$dbname, $user,$pass);
//$connection = new PDO('mysql:host=localhost;dbname=newsletter', 'root', 'toor');
// don't cache query
$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// show warning text
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
// throw error exception
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// don't colose connecion on script end
$connection->setAttribute(PDO::ATTR_PERSISTENT, false);
// set utf for connection
$connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8' COLLATE 'utf8'");
return $connection;
}

$db = Conn();

// main domain url address where was instaled
$host = $_SERVER['HTTP_HOST'];
$host = $MYHOSTNAME;
//$SMTPdomain = 'fxstar.eu';

$ip = $_SERVER['REMOTE_ADDR'];
//$st = $db->query("SELECT a.active,a.id,a.campid,a.listaid,a.templateid,a.email,a.name,a.lastname,a.ip,a.sendtime,a.sendcount,c.active as cactive FROM sendQue as a LEFT JOIN subscribers as c ON c.email = a.email AND c.listaid = a.listaid LEFT JOIN template as b ON a.templateid = b.id WHERE a.active = '1' AND c.active = '1' LIMIT $limit");

$st = $db->query("SELECT a.active,a.id,a.campid,a.listaid,a.templateid,a.email,a.name,a.lastname,a.ip,a.sendtime,c.phone,c.city,c.country,c.address,a.sendcount,b.subject,b.html,c.id as sid,c.active as cactive  FROM sendQue as a LEFT JOIN template as b ON a.templateid = b.id  LEFT JOIN subscribers as c ON c.email = a.email AND c.listaid = a.listaid WHERE a.active = '1' AND c.active = '1' LIMIT $limit");

$rows = $st->fetchALL(PDO::FETCH_ASSOC);

if ($st->rowCount() > 0) {
$log = date('Y-m-d H:i:s', time())."###RUN_ROWS <br>\r\n";
file_put_contents($file, $log, FILE_APPEND);

foreach ($rows as $r) {
	$id1=$id2=$id3=$id4=$id5=$id6=$id7=$id8=$id9=$id0=$id11 = "";
	$sub = $msg = $unsubscribe = "";
	// camp id
	$id0 = (int)$r['id'];
	$id1 = $r['listaid'];
	$id2 = $r['templateid'];
	$id3 = $r['email'];
	$id4 = $r['name'];
	$id5 = $r['lastname'];
	$id6 = $r['ip'];
	$id7 = $r['sendtime'];

	// empty
	$id8 = $r['phone'];
	$id9 = $r['country'];
	$id10 = $r['city'];
	$id11 = $r['address'];
	$subscriberid = $r['sid'];
	
	$host = $MYHOSTNAME;

	// unsubscribe 
	$unsubscribe = "https://".$host.'/login/autoresponder/unsubscribe.php?id='.$id3;

	// track mail open
	$linkopen = 'https://'.$host.'/login/linkopen.php?id='.$id0.'&s='.$subscriberid;

	//msg	
	$sub = $r['subject'];
	$msg = $r['html'];

	$msg = str_replace('{EMAIL}', $id3, $msg);
	$msg = str_replace('{UNSUBSCRIBE}', $unsubscribe, $msg);
	$msg = str_replace('{NAME}', $id4, $msg);
	$msg = str_replace('{LASTNAME}', $id5, $msg);
	$msg = str_replace('{IP}', $id6, $msg);
	$msg = str_replace('{PHONE}', $id8, $msg);
	$msg = str_replace('{COUNTRY}', $id9, $msg);
	$msg = str_replace('{CITY}', $id10, $msg);
	$msg = str_replace('{ADDRESS}', $id11, $msg);
	$msg = str_replace('{OPEN}', $linkopen, $msg);	
	//$id = $r[''];

	// Send email	
	try {	
		// send mail
		$msg             = eregi_replace("[\]",'',$msg);
		$mail             = new PHPMailer();
		// send like html
		$mail->IsHTML(true);
		$mail->IsSMTP(); 	

	
		//$mail->SMTPDebug  = 1;              // 1 = errors and messages, 2 = messages only
		//hostname
		$mail->Host       = $SMTPdomain; 	// SMTP server hostname
		$mail ->CharSet   = "utf-8";		// charset utf-8
		$Mail->Encoding    = '8bit';		// encoding 
		$mail->SMTPSecure = $SMTPSecure;    // true or false
		$mail->Port       = $SMTPPort;      // set the SMTP port for the GMAIL server
		$mail->SMTPAuth   = $SMTPAuth;      // enable SMTP authentication
		$mail->Username   = $SMTPUsername; 	// SMTP account username
		$mail->Password   = $SMTPPassword;  // SMTP account password
    	$mail->Timeout       =   60; 		// set the timeout (seconds)
    	$mail->SMTPKeepAlive = true; 		// don't close the connection between messages

		// Add dkim keys
		if (isset($DKIMdomain)) {
			$keydir = (__DIR__);
			$mail->DKIM_domain = $DKIMdomain; 		// domain dns
			$mail->DKIM_private = $keydir.'/'.$DKIMprivkey;		// private key path to file
			$mail->DKIM_selector = $DKIMselector;  	//this effects what you put in your DNS record
			$mail->DKIM_passphrase = $DKIMpassword;	// nothing if empty
		}

		$mail->SetFrom($SMTPsendfrom, $SetFrom);
		$mail->AddReplyTo($SMTPsendfrom, $SetFrom);

		$mail->Subject    = $sub;
		$mail->AltBody    = $sub;
		$mail->MsgHTML(html_entity_decode($msg));

		// email to and name
		$mail->AddAddress($id3, $id4);

		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

		if(!$mail->Send()) {
			$time = date('Y-m-d H:i:s', time());
		  	echo $error = $time." ###Mailer Error: " . $mail->ErrorInfo . " ###MAIL" . $id3. "###CAMPID " .$id0."<br>\r\n";
		  	file_put_contents($file, $error, FILE_APPEND);
		} else {		 
			$st->closeCursor();				
			$st = $db->query("UPDATE sendque SET active = '0', timesend = CURRENT_TIMESTAMP WHERE id = $id0");		
			$time = date('Y-m-d H:i:s', time());
			$log = $time." ###Message sent! ".$id3." Temat: ".$sub.' Wiadomość: '.$msg."<br>\r\n";		
			file_put_contents($file, $log, FILE_APPEND);		 
		}
	} catch (Exception $e) {
		//print_r($e->errorCode());
		//echo $e->getMessage(); 		
		$log = "ERROR_SEND#".$id1.'#'.$id2.'#'.$id3;
		file_put_contents($file, $log, FILE_APPEND);
	}	
	}
}

$log = date('Y-m-d H:i:s', time())." ###RUN_END <br>\r\n";
file_put_contents($file, $log, FILE_APPEND);
?>
