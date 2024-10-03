<?php
include "setup.php";
header('Content-Type: application/json'); // Ensure the response is JSON formatted

// ha de ser l'ID de metaestructura i no el label:
$object_id = strtoupper($_GET['object_id']); // Get the 'meta' parameter from the AJAX request
$meta_flag = $_GET['meta_flag']; // 0 = GLOBAL 1 = META de GLOBAL,  2 = META de META.    
// --no--ara pot ser 0 (Global), o 
// pot ser un id de categories, o un label de metacategories (aquest l'hem d'eliminar)

// Establish database connection
$link = mysqli_connect("localhost", $dbuser, $dbpass, $dbname);
if (!$link) {
    die(json_encode(['error' => 'Error establishing a connection to the database.']));
}

// Set character encoding to UTF-8
mysqli_query($link,"SET NAMES 'utf8'");
mysqli_query($link,"SET CHARACTER SET utf8 ");

if ($meta_flag==0) { // model global

	//carrega per tornar enrere a model 0, crec que no ho farem servir
        $query = "SELECT * FROM kms_kb_categories WHERE status=1 ORDER BY id asc";


} else if ($meta_flag==1) { // meta de global // clic a categoria en model global

$debug=false;

	//get metastructure
	$query = "SELECT * from kms_kb_metastructure where parent=".$object_id;
	$res = mysqli_query($link, $query);
	$metastructure = mysqli_fetch_assoc($res);
	// get parent
if ($debug) echo $query."\n";
	$query = "SELECT * FROM kms_kb_categories WHERE id=".$metastructure['parent']; 
	$res2 = mysqli_query($link, $query);
        $parent = mysqli_fetch_assoc($res2);
if ($debug) echo $query."\n";
        // si no existeix l'autogenerem
        if (mysqli_num_rows($res) == 0) {
                $query = "INSERT INTO kms_kb_metastructure (creation_date,status,label,nom,name_en,parent,metaparent) VALUES ('".date('Y-m-d H:i:s')."',1,'".$parent['label']."','Model XXX','XXX Model',".$object_id.",'')";
            if ($debug)   echo $query."\n";
                $res = mysqli_query($link, $query);
                $metastructure_id = mysqli_insert_id($link);

                //generem categories de la plantilla del model global
                $query = "SELECT label,tipus from kms_kb_categories where status=1";
if ($debug) echo $query."\n";
                $res2 = mysqli_query($link, $query);
                while ($cat =  mysqli_fetch_assoc($res2)) {
                        $query = "INSERT INTO kms_kb_metacategories (status,label,nom,name_en,tipus,subtipus,parent) values (1,'".$cat['label']."','xxxx','xxxx','".$cat['tipus']."','".$parent['label']."',".$metastructure_id.")";
         if ($debug) echo $query."\n";
                        $res3 = mysqli_query($link, $query);
                 }
        }

	$query = "SELECT * FROM kms_kb_metacategories WHERE parent=".$metastructure['id']." AND status=1 ORDER BY id asc";
if ($debug) echo $query."\n";

} else if ($meta_flag==2) { // meta de meta // clic a categoria sobre model parcial

        // get metastructure
        $query = "SELECT * FROM kms_kb_metastructure WHERE metaparent=".$object_id;
	$res = mysqli_query($link, $query);
        $metastructure = mysqli_fetch_assoc($res);
	$metastructure_id = $metastructure['id'];
//echo $query."\n";	
	// get metaparent
	$query = "SELECT * FROM kms_kb_metacategories WHERE id=".$object_id;
	$res2 = mysqli_query($link, $query);
        $metaparent = mysqli_fetch_assoc($res2);

	// si no existeix l'autogenerem
        if (mysqli_num_rows($res) == 0) {
                $query = "INSERT INTO kms_kb_metastructure (creation_date,status,label,nom,name_en,parent,metaparent) VALUES ('".date('Y-m-d H:i:s')."',1,'".$metaparent['label']."','Model XXX','XXX Model','',".$object_id.")";
//		 echo $query."\n";
		$res = mysqli_query($link, $query);
		$metastructure_id = mysqli_insert_id($link);

		//generem categories de la plantilla del model global
		$query = "SELECT label,tipus from kms_kb_categories where status=1";
	        $res2 = mysqli_query($link, $query);
     		while ($cat =  mysqli_fetch_assoc($res2)) {
        		$query = "INSERT INTO kms_kb_metacategories (status,label,nom,name_en,tipus,subtipus,parent) values (1,'".$cat['label']."','xxxx','xxxx','".$cat['tipus']."','".$metaparent['label']."',".$metastructure_id.")";
//			echo $query."\n";
		        $res3 = mysqli_query($link, $query);
    		 }
        }
     $query = "SELECT * FROM kms_kb_metacategories WHERE parent=".$metastructure_id." AND status=1 ORDER BY tipus ASC, id";
}

// carreguem categories generades
//$query = "SELECT * FROM $table WHERE parent=".$meta." AND status=1 ORDER BY tipus ASC, id";
$res = mysqli_query($link, $query);

//echo $query."\n";

// Prepare an array to hold the results
$data = [];

while ($row = mysqli_fetch_assoc($res)) {

     // get generic data from main categories
     $query = "SELECT * from kms_kb_categories where label='".$row['label']."'";
     $res2 = mysqli_query($link, $query);
     $cat =  mysqli_fetch_assoc($res2);

     $tipus = substr(strtolower($row['tipus']), 0, 3);
    
    if ($row['color'] == "") {
        $row['color'] = "2e8560";
    }

    $r = hexdec(substr($row['color'], 0, 2));
    $g = hexdec(substr($row['color'], 2, 2));
    $b = hexdec(substr($row['color'], 4, 2));
    $transp_factor = 1.5;

    if ($tipus == "pla" || $tipus == "mun") {
        $r = (int)($r / $transp_factor);
        $g = (int)($g / $transp_factor);
        $b = (int)($b / $transp_factor);
    }

    // Add the row to the results array
    $data[] = [
	'id' => $row['id'],
        'label' => $row['label'],
	'tipus' => $tipus,
        'color' => $row['color'],
        'r' => $r,
        'g' => $g,
        'b' => $b,
        'nom' => $row['nom'],
        'name_en' => $row['name_en'],
        'x' => $cat['x'],
	'y' => $cat['y'],
	'z' => $cat['z'],
	'generacio' => $cat['generacio'],
	'descr' => $row['description']
    ];
}

// combined response
$response = [
    'metastructure' => $metastructure,
    'metacategories' => $data
];

// Return the results as JSON
echo json_encode($response);

// Close the connection
mysqli_close($link);
?>
