<?php

$filename="../data/0-dimensions.js";$array="data";
//$filename="../data/1-globalium.js";$array="globalium";
$fp = fopen($filename, "r");

while(!feof($fp))
  {
	$line=fgets($fp);
//        globalium['COV']={pos:situa(80,0,0)
//        data['SUB'] ={pos:[0,0,50],c
	if ($array=="globalium") {

		$p=strpos($line,$array."[");
		if ($p) {
		$cat=substr($line,$p+11,3);
		$coord=substr($line,strpos($line,"situa(")+6);
		$coord=substr($coord,0,strpos($coord,")"));
		echo $cat.":".$coord."\n";
		} else {
		//echo "NOT FOUND: ".$line;
		}
	} else {
	        $p=strpos($line,$array."[");
	        if ($p) {
                $cat=substr($line,$p+6,4);
                $coord=substr($line,strpos($line,"pos:[")+5);
                $coord=substr($coord,0,strpos($coord,"]"));
                echo $cat.":".$coord."\n";
        	} else {
                //echo "NOT FOUND: ".$line;
	        }
	}
  }

fclose($fp);
