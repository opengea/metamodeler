<?php
include "setup.php";
header('Content-Type: application/json'); // Ensure the response is JSON formatted

$meta = strtoupper($_GET['meta']); // Get the 'meta' parameter from the AJAX request

$meta_flag = $_GET['meta_flag'];
$value = $_GET['value'];
$object_id = $_GET['object'];
if (isset($_POST['descr'])) $descr = $_POST['descr'];
//$original_value = $_GET['original_value'];

// Establish database connection
$link = mysqli_connect("localhost", $dbuser, $dbpass, $dbname);
if (!$link) {
    die(json_encode(['error' => 'Error establishing a connection to the database.']));
}

// Set character encoding to UTF-8
mysqli_query($link,"SET NAMES 'utf8'");
mysqli_query($link,"SET CHARACTER SET utf8 ");

if ($meta_flag==0) $table="kms_kb_categories"; else $table="kms_kb_metacategories";
// Execute the query
if (isset($descr)) $query = "UPDATE $table SET description=\"".$descr."\" WHERE id=\"".$object_id."\"";
else $query = "UPDATE $table SET nom=\"".$value."\" where id=\"".$object_id."\""; // AND nom=\"".$original_value."\"";
$res = mysqli_query($link, $query);

if (!$res) $response= "Error updating ".$label; else $response="success";

// Return the results as JSON
echo json_encode("meta_flag: ".$meta_flag."\nresponse: ".$respose."\nquery: ".$query);

// Close the connection
mysqli_close($link);
?>
