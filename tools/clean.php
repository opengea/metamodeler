<?php


$filename="superafins.txt";
$fp = fopen($filename, "r");

while(!feof($fp))
  {
	$line=fgets($fp);

	$newline=substr($line,strpos($line,". "));
	echo $newline;

  }

fclose($fp);
