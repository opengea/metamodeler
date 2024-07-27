<?

$dbuser="opengea_kms";
$dbpass="tY43JBhE";
$dbname="opengea_kms";

$link = mysqli_connect("localhost", $dbuser, $dbpass, $dbname);
if (!$link) die ('error connecting to database '.$dbname);
mysqli_query($link,"SET NAMES 'utf8'");
mysqli_query($link,"SET CHARACTER SET utf8 ");

/*
$res=mysqli_query($link,"select label,nom,description from kms_kb_categories");
while ($row=mysqli_fetch_assoc($res)) {

echo $row['label'].";".trim(str_replace(";",",",$row['nom']))."\n";

}

exit;
*/

$filename="en2.data";
$file = fopen($filename, "r");

while(!feof($file))
  {
  	$line=explode(";",fgets($file));
	echo $line[0]."->".$line[1];
	$q="update kms_kb_categories set name_en=\"".trim($line[1])."\" where label=\"".$line[0]."\"";
echo $q;
		$res=mysqli_query($link,$q);
  }
fclose($file);
?>

