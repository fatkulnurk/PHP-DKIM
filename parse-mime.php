<?php
$str = file_get_contents('mime-mixed-related-alternative.eml');

preg_match_all('/((?<=(Content-Type: multipart\/mixed; boundary="))(.*)?(?=(")))|((?<=(Content-Type: multipart\/related; boundary="))(.*)?(?=(")))|((?<=(Content-Type: multipart\/alternative; boundary="))(.*)?(?=(")))/', $str, $boundary);

echo "<pre>";
// print_r($boundary);

$AllPartsUnique = "";
$j=0;

foreach ($boundary[0] as $key => $v) {
  if($key >= 0){
    echo "\n\n\nBoundary " . $v . "\r\n";

    // cut boundary content
    preg_match_all('/(?<=(--'.$v.'))(| |.*|[\s\S]+|\<|\>|\.|\r|\n|\0|@|\w+)?(?=(--'.$v.'--))/', $str, $part);

    $bname = $v;

    foreach ($part[0] as $v) {
        // echo "PART " . $bname . " " . $v . "\r\n";
        $parts = explode("--".$bname, $v);

        echo "<pre>";
        foreach ($parts as $v) {
            // echo "\r\nSINGLE PART " . $v . "\r\n";     
            // $AllPartsUnique[$j] = $v;
            // with html visible on page
            $AllPartsUnique[$j] = htmlentities($v);     
            $j++;  
        }
    }
  }
}
echo "<pre>";
// print_r($AllPartsUnique);



foreach($AllPartsUnique as $key => $one) {
  foreach ($boundary[0] as $find) {
    if(strpos($one, $find) !== false){
        unset($AllPartsUnique[$key]); 
    }
  }    
}

echo "<pre>";
 print_r($AllPartsUnique);

preg_match_all('/(?<=((\n)To: )|(^To: ))(.*)+?(?=())/', $str, $to);
echo "To " . $to[0][0];

preg_match_all('/(?<=((\n)From: )|(^From: ))(.*)+?(?=())/', $str, $from);
echo "From " . $from[0][0];

preg_match_all('/(?<=((\n)Subject: )|(^Subject: ))(.*)+?(?=())/', $str, $subject);
echo "Subject " . $subject[0][0];
    
// end script
die();
?>
