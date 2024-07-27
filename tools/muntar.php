<?php

$filename="superafins.dat";
$fp = fopen($filename, "r");
$a=array();$i=0;
while(!feof($fp))
  {
	$line=fgets($fp);

	$p=strpos($line,"-")-3;
	$coords=str_replace(" ","",trim(substr($line,$p,$p+7)));
	$terme=trim(substr($line,0,$p-1));
	if ($terme&&strpos($coords,"-")) { 
//		echo $terme.":".$coords."\n";

		if (!isset($a[$coords])) {
			$a[$coords]=array();
			$a[$coords][0]=$terme; 
			$a[$coords][1]=$terme;
		} else {
			if ($a[$coords][1]!="")
				 $a[$coords][1].=", ".$terme; 
				else $a[$coords][1].=$terme;
		}
	}
	$i++;
  }
fclose($fp);
//print_r($a);
asort($a);

//save

$fp = fopen("output2.txt", "w");

foreach ($a as $n => $c ) {
		$l=$n.":".$c[0].":".$c[1]."\n";

//		$l=trim($n[0]).":".trim($n[1])."\n"; 
		$enc = mb_detect_encoding($l);
		$data = mb_convert_encoding($l, "UTF-8", $enc);
		fwrite($fp,$data);
	}

fclose($fp);
