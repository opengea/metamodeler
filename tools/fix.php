<?php


$filename="superafins.dat";
$fp = fopen($filename, "r");
$a=array();$i=0;
while(!feof($fp))
  {
	$line=fgets($fp);

	$p=strpos($line,"-")-3;
	$coords=substr($line,$p,$p+7);
	$terme=substr($line,0,$p-1);
	if ($terme) { echo $terme.":".$coords;
	$a[$i]=array();
	$a[$i][0]=$coords; $a[$i][1]=$terme;
	}
	$i++;
  }
fclose($fp);
asort($a);
//print_r($a);

$fp = fopen("output.txt", "w");

foreach ($a as $n) {
	$l=trim($n[0]).":".trim($n[1])."\n"; 
$enc = mb_detect_encoding($l);
$data = mb_convert_encoding($l, "UTF-8", $enc);

		fwrite($fp,$data);
	}

fclose($fp);
