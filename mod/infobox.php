<?
$datadir    = 'data';

if ($userPreferredLanguage=="en") { $name_field="name_en"; $descr_field="desc_en"; }  else { $name_field="nom"; $descr_field="description"; }

?>
<h1> METAMODELER v.<?=$version?></h1>
<div style="position:absolute;bottom:50px"><span class="colortheme_dark" style="text-transform:capitalize">f key:fullscreen mode</span><br><br><span class='colortheme' style="text-transform:initial">Apache 2.0 license.  &copy; <?=date('Y')?> METAMODELER <a href='mailto:jordi@opengea.org' style='color:#67d5a6'>By Jordi Berenguer</a> <a href="https://www.opengea.org" target="_blank" style="padding-top:5px;color:#eeeeee">opengea.org</a><br></span><br></div>
<div class='menutab' onclick="setTab('settings');">Settings</div><div class='menutab' onclick="setTab('info')">info</div>
<br>
<div class="tab" id='settings'>
<h2>SETTINGS</h2>
<? /*Model:<select name="model" id="model">
<option>Toroid</option>
</select>*/?>
<form method="POST">
<input id="render_wireframe" type="checkbox" checked onchange="torusmaterial.wireframe=this.checked">Wireframe<br>

<input id="render_wireframe_torus" name="render_torus" type="checkbox" <? if ($_POST['render_torus']=="on"||$_POST['render_torus']) echo ""?> onchange="torus0.visible=this.checked;torus1.visible=this.checked;torus2.visible=this.checked;torus3.visible=this.checked;">Torus<br>
<input id="render_wireframe_sphere0" name="render_sphere" type="checkbox"  <? if ($_POST['render_sphere']=="on"||$_POST['render_sphere']) echo "checked"?> onchange="sphere0.visible=this.checked">Plasma sphere<br>
<input id="render_wireframe_sphere1" name="render_sphere" type="checkbox"  <? if ($_POST['render_sphere']=="on"||$_POST['render_sphere']) echo "checked"?> onchange="sphere1.visible=this.checked">Neutral sphere<br>
<input id="render_wireframe_sphere2" name="render_sphere" type="checkbox"  <? if ($_POST['render_sphere']=="on"||$_POST['render_sphere']) echo "checked"?> onchange="sphere2.visible=this.checked">World sphere<br>
<input id="light" name="light" type="checkbox" onchange='if(this.checked) toggleColors(1); else toggleColors(0);'>Dark theme<br>

<?
if ($_POST['sphere_rad']=="") $_POST['sphere_rad']=100;
if ($_POST['geo_rad']=="") $_POST['geo_rad']=55;
if ($_POST['geo_tub']=="") $_POST['geo_tub']=55;
if ($_POST['geo_seg']=="") $_POST['geo_seg']=64;
if ($_POST['geo_arc']=="") $_POST['geo_arc']=64;
?>
TORUS:<br>
Rad <input type=text id="geo_rad" name="geo_rad" value="<?=$_POST['geo_rad']?>"> 
Tub <input type=text id="geo_tub" name="geo_tub" value="<?=$_POST['geo_tub']?>">
Seg <input type=text id="geo_seg" name="geo_seg" value="<?=$_POST['geo_seg']?>">
Arc <input type=text id="geo_arc" name="geo_arc" value="<?=$_POST['geo_arc']?>">
<br>
SPHERE:<br>
Rad <input type=text id="sphere_rad" name="sphere_rad" value="<?=$_POST['sphere_rad']?>">
<br>
<? if (!isset($_POST['x'])||$_POST['x']=="") { $_POST['x']=0; }
   if (!isset($_POST['y'])||$_POST['y']=="") { $_POST['y']=0; } 
   if (!isset($_POST['z'])||$_POST['z']=="") { $_POST['z']=0; }  ?>
X <input type=text id="x" name="x" value="<?=$_POST['x']?>">
Y <input type=text id="y" name="y" value="<?=$_POST['y']?>">
Z <input type=text id="z" name="z" value="<?=$_POST['z']?>">
<br>

<input type='submit' value="RESET">



<h2>Models</h2>
<select onchange="metacat(this.value)">
    <option value="0">** Metamodel global **</option>
    <option value="pla">Creativitat</option>
    <option value="mon">Societat</option>
    <option value="fen">Comunicació</option>
    <option value="nou">Espiritualitat</option>
    <option value="sub">Subjectivitat i realització personal</option>
    <option value="obj">Teoria unificada de la física</option>
    <option value="teo">Teoria epistemològica de la ment</option>
    <option value="pra">Cosmologia</option>

    <option value="exp">Història</option>
    <option value="ana">Enteniment</option>
    <option value="sin">Pedagogia</option>
    <option value="amo">No-violència i pau</option>
    <option value="sgt">Diàleg i consens</option>
    <option value="stm">Emocions i sentiments</option>
    <option value="sge">Simbologia</option>
    <option value="stt">Intel·ligència</option>

    <option value="mtf">Metafísica i filosofia</option>
    <option value="mtp">Metapsíquica i arquetips de la personalitat</option>
    <option value="1">Disciplines</option>
    <option value="art">Art i creativitat</option>
    <option value="log">Llenguatge</option>
    <option value="ide">Matemàtiques</option>
    <option value="eti">Cibernètica</option>
    <option value="tec">Intel·ligència Artificial</option>
    <option value="mit">Cultura i tradició</option>
    <option value="est">Comunicació i retòrica</option>
    <option value="psi">Sociologia</option>
    <option value="mis">Espiritualitat i mística</option>

    <option value="rgn">Verapau</option>
</select>


<?
$dimensions="seny.js";
if ($_GET['s']!="") { $dimensions=$_GET['s'].".js"; }
$files = scandir($datadir);
foreach ($files as $f) {
        if (substr($f,0,1)!="."&&$f!="disabled"&&$f!="dimensions"&&$f!="models") {  $name=str_replace(".js","",$f);?>
<input id="chk_<?=substr($name,0,3)?>" class="cc" type="checkbox" checked onchange="set_visibility('<?=$name?>',this.checked)"><?=$name?><br>
<? 
}
}
?>


<?

$dimensions="seny.js";
if ($_GET['s']!="") { $dimensions=$_GET['s'].".js"; }
//include "data/dimensions/".$dimensions;
//BD


$link = mysqli_connect("localhost", $dbuser, $dbpass, $dbname);
if (!$link) die ('</header><center><span class=\'error\'> Error establishing a connection to the database.</span></center></body></html> '.$dbname);
mysqli_query($link,"SET NAMES 'utf8'");
mysqli_query($link,"SET CHARACTER SET utf8 ");
$res=mysqli_query($link,"select * from kms_kb_categories where parent=0 and status=1 order by tipus asc,id");
$tipus="";
$estil = array();
$estil['dim']="textcolor:'0,0,0',borderThickness:3, fontsize:100, borderalpha:1.0, bgalpha:0.8";
$estil['dir']="textcolor:'0,0,0',borderThickness:2.5, fontsize:100, borderalpha:1.0, bgalpha:0.8";
$estil['dis']="textcolor:'0,0,0',borderThickness:2.5, fontsize:100, borderalpha:1.0, bgalpha:0.8";
$estil['pla']="textcolor:'0,0,0',borderThickness:1.5, fontsize:100, borderalpha:1.0, bgalpha:0.8";
$estil['neu']="textcolor:'0,0,0',borderThickness:3, fontsize:100, borderalpha:1.0, bgalpha:0.8";
$estil['mun']="textcolor:'0,0,0',borderThickness:1.5, fontsize:100, borderalpha:1.0, bgalpha:0.8";
$estil['afi']="textcolor:'0,0,0',borderThickness:2.5, fontsize:100, borderalpha:1.0, bgalpha:0.8";
?>

<script>

var mode="<?=$_GET['mode']?>";
var model=0; // model actual
var meta_flag=0;   // 0 = GLOBAL, 1 = META de GLOBAL, 2 = META de META 

function metacat(object_id) {

    // Load all meta categories from a determined meta model

    console.log("metacat function called with meta object_id:"+object_id+" meta_flag:"+meta_flag) // Debug log

    model = object_id;  //actualitzem la variable global model (id metastructure o 0 = global)
    console.log("model actual: "+model);
    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the callback function that will be triggered when the response is received
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) { // Check if the request is complete
            if (xhr.status == 200) { // Check if the request was successful
                //console.log("Response received:", xhr.responseText); // Debug log

                try {
                    // Parse the response (assuming it's JSON encoded)
                    var data = JSON.parse(xhr.responseText);
		    metacategories = data['metacategories'];
		    metastructure = data['metastructure'];

                    // Create an array of objects indexed by row.label
                    var indexedData = {};

                    // Loop through the data and index by row.label
                    metacategories.forEach(function(row) {
                        // Ensure that row.label exists to avoid errors
                        if (row.label) {
                            indexedData[row.label] = row;
                        }
                    });

                    // Now indexedData is an object where each key is row.label, and the value is the row object
                    console.log("Indexed data by row.label:", indexedData);

		    var data=[];
                    Object.keys(indexedData).forEach(function(label) {

			var row = indexedData[label];
    			data[label] = {
				id: row.id,
			        pos: [row.y, -row.z, -row.x],    // Create an array for the position
				color: [row.r, row.g, row.b],
				title: row.nom,
  				type: row.tipus,
				descr: row.descr,
				label: row.label,
				generacio: row.generacio
			};
                    });

		//Eliminem tots els textSprites de l'escena
//		console.log(objects);
		// deleting sprites
		for (let i = objects.length - 1; i >= 0; i--) {
		    let child = objects[i];

		    if (child.type='TextSprite') {
		        scene.remove(child);  
			objects.splice(i, 1);
		    }
		}
		// deleting labels
                for (let i = labels.length - 1; i >= 0; i--) {
                    let child = labels[i];

                    if (child.type='TextSprite') {
                        scene.remove(child);
                        labels.splice(i, 1);
                    }
                }

		loadCategories('neu', data, {<?=$estil['neu']?>});
		loadCategories('mun', data, {<?=$estil['mun']?>});
		loadCategories('pla', data, {<?=$estil['pla']?>});

		console.log('loading preinfo');
    		info="<div id='preinfo'></div><div id='readonly' class='colortheme_dark' style='display:none'></div><div id='edit'  style='display:none'><form id='editform'><textarea class='textarea_edit' id='textarea'></textarea><br><input type='submit' value='submit'></input></form></div><div id='postinfo'></div></div>";
		    if (metastructure !== null) {
		    info = "<br>"+metastructure.nom.toUpperCase()+"<br><br>"+metastructure.description;//"Disciplines<br><br>Teoria del coneixement, epistemologia. Materies d'estudi.";
		    }
		    $('#info').html(info);

                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            } else {
                console.error("Error: Request failed with status", xhr.status);
            }
        }
    };

    // Open a new connection, using the GET method to the PHP script
    xhr.open("GET", "meta_loader.php?object_id="+object_id+"&meta_flag="+meta_flag, true);

    // Send the request
    xhr.send();

    console.log("sent meta_loader.php?object_id="+object_id+"&meta_flag="+meta_flag); 


}


function set_metacat(object_id,value) {

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the callback function that will be triggered when the response is received
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) { // Check if the request is complete
            if (xhr.status == 200) { // Check if the request was successful
                //console.log("Response received:", xhr.responseText); // Debug log

                try {
                    // Parse the response (assuming it's JSON encoded)
                    var data = JSON.parse(xhr.responseText);

                //Eliminem tots els textSprites de l'escena
                //console.log(data);


		// console.log('deleting sprites');
                for (let i = objects.length - 1; i >= 0; i--) {
                    let child = objects[i];

                    if (child.type='TextSprite') {
                        scene.remove(child);
                        objects.splice(i, 1);
                    }
                }
		// console.log('deleting labels');
                for (let i = labels.length - 1; i >= 0; i--) {
                    let child = labels[i];

                    if (child.type='TextSprite') {
                        scene.remove(child);
                        labels.splice(i, 1);
                    }
                }

		// reload model
		console.log("call metacat with meta_flag "+meta_flag);
		metacat(model);
		openNav();
		setTab('info');

                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            } else {
                console.error("Error: Request failed with status", xhr.status);
            }
        }
    };

    // Open a new connection, using the GET method to the PHP script
    console.log( "meta_updater.php?meta="+model+"&meta_flag="+meta_flag+"&value=" + encodeURIComponent(value)+"&object=" + object_id);
    xhr.open("GET", "meta_updater.php?meta="+model+"&meta_flag="+meta_flag+"&value=" + encodeURIComponent(value)+"&object=" + object_id, true);
    // Send the request
    xhr.send();
}

function set_metacat_descr(object_id, value) {  

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the callback function that will be triggered when the response is received
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) { // Check if the request is complete
            if (xhr.status == 200) { // Check if the request was successful
                //console.log("Response received:", xhr.responseText); // Debug log

                try {
                    // Parse the response (assuming it's JSON encoded)
                var data = JSON.parse(xhr.responseText);
                //Eliminem tots els textSprites de l'escena
                console.log(data);
		metacat(model);

                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            } else {
                console.error("Error: Request failed with status", xhr.status);
            }
        }
    };

    // Open a new connection, using the GET method to the PHP script
    console.log( "meta_updater.php?meta="+model+"&meta_flag="+meta_flag+"&object="+ object_id);
   xhr.open("POST", "meta_updater.php?meta="+model+"&meta_flag="+meta_flag+"&object=" + object_id, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

 // Prepare the data to be sent in the body
    var params = "descr=" + encodeURIComponent(value);
    // Send the request with the parameters in the body
    xhr.send(params);
}




function toggleColors(r) {
  if (!r)  {
	 scene.background = new THREE.Color(0xffffff);
	 // torusmaterial.opacity = 0.15;
	 } else { 
	 scene.background = new THREE.Color(0x161111);
	// torusmaterial.opacity = 0.4;
	 
	}
}

var info="<?=str_replace("\n","",$info)?>";
function showCategories() {
<?

function normalitza($s) {
	$s=str_replace("\r","<br>",$s);
	$s=str_replace("\n","<br>",$s);
	$s=str_replace("'","\'",$s);
	$s=trim($s);
	return $s;
}


echo "// autogenerated setup\n";
echo "var data=[];\n";
while ($row=mysqli_fetch_assoc($res)) {
        if ($tipus=="") $tipus=substr(strtolower($row['tipus']),0,3);
        if ($row['color']=="") $row['color']="2e8560";
        $r=hexdec(substr($row['color'],0,2));
        $g=hexdec(substr($row['color'],2,2));
        $b=hexdec(substr($row['color'],4,2));
        $transp_factor=1.5;
        if ($tipus=="pla"||$tipus=="mun") { $r=(int)($r/$transp_factor); $g=(int)($g/$transp_factor); $b=(int)($b/$transp_factor); }

        if ($tipus!=substr(strtolower($row['tipus']),0,3)) {
                echo "loadCategories('".$tipus."',data,{".$estil[$tipus]."});\n\nglobalium['{$row['label']}']=data['{$row['label']}'];data=[];\n";
                $tipus=substr(strtolower($row['tipus']),0,3);
        }

        echo "data['{$row['label']}'] ={id:".$row['id'].",pos:[".($row['y']).",".(-$row['z']).",".(-$row['x'])."],color:[{$r},{$g},{$b}],title:'{$row[$name_field]}',type:'{$row['tipus']}',descr:'".normalitza($row[$descr_field])."',generacio:".$row['generacio']."};\n";
        echo "globalium['{$row['label']}']=data['{$row['label']}'];";
}
$tipus=substr(strtolower($tipus),0,3);
echo "loadCategories('".$tipus."',data,{".$estil[$tipus]."});\n\n";



?>	

}


$(document).ready( function() { 




init(<?=$mobile?>);
//onaSeny[0].visible=onaSeny[1].visible=false;
//onaCrepus[0].visible=onaCrepus[1].visible=false;

animate();

//timer=setTimeout("set_visibility_all(false)",600); //prevent buggy
//onaSeny[0].visible=onaSeny[1].visible=false;
//onaCrepus[0].visible=onaCrepus[1].visible=false;


torus0.visible=torus1.visible=torus2.visible=torus3.visible=<? if ($_POST['render_torus']=="on"||$_POST['render_torus']=="1") echo "true"; else echo "false";?>;
sphere0.visible=sphere1.visible=sphere2.visible=<? if ($_POST['render_sphere']=="on"||$_POST['render_sphere']=="") echo "true"; else echo "false";?>;


$('#render_wireframe_torus').prop('checked',false);
$('#render_wireframe_sphere0').prop('checked',true);
$('#render_wireframe_sphere1').prop('checked',true);
$('#render_wireframe_sphere2').prop('checked',true);

torus0.visible=torus1.visible=torus2.visible=torus3.visible=false;
sphere0.visible=sphere1.visible=sphere2.visible=true;

var x=<?=$_POST['x']?>;
var y=<?=$_POST['y']?>;
var z=<?=$_POST['z']?>;
//torus.position.set(x,y,z);

console.log('off');


showCategories();

set_visibility('1-afins',false);



});



</script>
<?/*<h2>SENY/RAUXA</h2>
<input class="cc" type="checkbox" onchange="onaSeny[0].visible=onaSeny[1].visible=this.checked;if(this.checked) $('#info').html('<?=$seny?>');">Ona seny entrella&ccedil;ada<br>
<input class="cc" type="checkbox" onchange="onaCrepus[0].visible=onaCrepus[1].visible=this.checked;if(this.checked) $('#info').html('<?=$rauxa?>');">Ona rauxa entrella&ccedil;ada<br>
*/?>
</div>

<div class="tab colortheme" id="info">
<? /*<h2>INFO</h2>
<div id="preinfo"></div>
<div id="readonly" class="colortheme_dark" style='display:none'><?=$info?></div>
<div id="edit" style="display:none">
	<form id="editform">
	<textarea class="textarea_edit" id="textarea" style='height:300px' id="textarea"><?=str_replace("<br />","\n",$info)?></textarea><br>
	<input type="submit" value="Submit"></input>
	</form>
</div>
<div id="postinfo"></div>
*/?>
</div>

