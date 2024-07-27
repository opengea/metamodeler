<?php
if(!empty($_POST['data'])){
$data = $_POST['data'];
$fname = mktime() . ".txt";//generates random name
$file = fopen(__DIR__."/sources/metamodel/files/" .$fname, 'w');//creates new file
fwrite($file, $data);
fclose($file);
echo "OK";
} else {
echo "Empty";
}
?>
