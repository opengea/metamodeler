<?
$datadir    = 'data';

if ($userPreferredLanguage=="en") { $name_field="name_en"; $descr_field="desc_en"; }  else { $name_field="nom"; $descr_field="description"; }

?>
<h1> GMODELER v.<?=$version?></h1>
<div style="position:absolute;bottom:5px"><span class="colortheme_dark" style="text-transform:capitalize">f key:fullscreen mode</span><br><br><span class='colortheme' style="text-transform:initial">(cc) BY-SA GMODELER <a href='mailto:jordi@opengea.org' style='color:#67d5a6;float:right;padding-left:5px'>Jordi Berenguer</a> <a href="http://www.opengea.org" target="_blank" style="padding-top:5px;color:#eeeeee">www.opengea.org</a><br></span><br></div>
<div class='menutab' onclick="setTab('settings');">Settings</div><div class='menutab' onclick="setTab('info')">info</div>
<br>
<div class="tab" id='settings'>
<h2>SETTINGS</h2>
<? /*Model:<select name="model" id="model">
<option>Toroid</option>
</select>*/?>
<form method="POST">
<input id="render_wireframe" type="checkbox" checked onchange="torusmaterial.wireframe=this.checked">Wireframe<br>

<input id="render_wireframe" name="render_torus" type="checkbox" <? if ($_POST['render_torus']=="on") echo "checked"?> onchange="torus.visible=this.checked">Torus<br>
<input id="render_wireframe_sphere" name="render_sphere" type="checkbox"  <? if ($_POST['render_sphere']=="on") echo "checked"?> onchange="sphere.visible=this.checked">World sphere<br>

<?
if ($_POST['sphere_rad']=="") $_POST['sphere_rad']=100;
if ($_POST['geo_rad']=="") $_POST['geo_rad']=110;
if ($_POST['geo_tub']=="") $_POST['geo_tub']=110;
if ($_POST['geo_seg']=="") $_POST['geo_seg']=64;
if ($_POST['geo_arc']=="") $_POST['geo_arc']=64;
?>
TORUS:<br>
Rad <input type=text id="geo_rad" name="geo_rad" value="<?=$_POST['geo_rad']?>"> 
Tub <input type=text id="geo_tub" name="geo_tub" value="<?=$_POST['geo_tub']?>">
Seg <input type=text id="geo_seg" name="geo_seg" value="<?=$_POST['geo_seg']?>">
Arc <input type=text id="geo_arc" name="geo_arc" value="<?=$_POST['geo_arc']?>">

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



</form>
<h2>CATEGORIES</h2>
<?/*
<input class="cc" type="checkbox" checked onchange="set_visibility(cat['dim'],this.checked)">Dimensions<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['seny'],this.checked)">Cam&iacute; seny<br>
<input class="cc" type="checkbox" checked onchange="set_visibility(cat['glo'],this.checked)">Harmonitzaci&oacute; Glob&agrave;lium Major<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['filo'],this.checked)">Filosofia<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['fis'],this.checked)">F&iacute;sica forces fonamentals<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['uni'],this.checked)">Univers<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['disc'],this.checked)">Disciplines<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['art'],this.checked)">Arts<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['cie'],this.checked)">Ci&egrave;ncies<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['ling'],this.checked)">Llenguatge<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['mat'],this.checked)">Matem&agrave;tiques<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['qui'],this.checked)">Elements qu&iacute;mica<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['his'],this.checked)">Hist&ograve;ria<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['bio'],this.checked)">Biologia (especies)<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['crv'],this.checked)">Equivalencia cervell<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['sup'],this.checked)">Superaci&oacute; personal<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['emo'],this.checked)">Emocions i sentiments<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['arq'],this.checked)">Arquetips de la personalitat<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['ali'],this.checked)">Alimentaci&oacute;<br>
<input class="cc" type="checkbox" onchange="set_visibility(cat['onaSeny'],this.checked)">Ona seny entrella&ccedil;ada<br>
*/
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
$res=mysqli_query($link,"select * from kms_kb_categories where (parent is null or parent='') and status=1 order by tipus asc,id");
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
var info="<?=str_replace("\n","",$info)?>";
function showCategories() {
<?

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

        echo "data['{$row['label']}'] ={pos:[".($row['y']).",".(-$row['z']).",".(-$row['x'])."],color:[{$r},{$g},{$b}],title:'{$row[$name_field]}',type:'{$row['tipus']}',descr:'".trim(str_replace("'","\'",$row[$descr_field]))."'};\n";
        echo "globalium['{$row['label']}']=data['{$row['label']}'];";
}
$tipus=substr(strtolower($tipus),0,3);
echo "loadCategories('".$tipus."',data,{".$estil[$tipus]."});\n\n";


?>	

}

$(document).ready( function() { 




init(<?=$mobile?>);
onaSeny[0].visible=onaSeny[1].visible=false;
onaCrepus[0].visible=onaCrepus[1].visible=false;

animate();

//timer=setTimeout("set_visibility_all(false)",600); //prevent buggy
//onaSeny[0].visible=onaSeny[1].visible=false;
//onaCrepus[0].visible=onaCrepus[1].visible=false;

torus.visible=<?if ($_POST['render_torus']=="on") echo "true"; else echo "false";?>;

sphere.visible=<? if ($_POST['render_sphere']=="on"||$_POST['render_sphere']=="") echo "true"; else echo "false";?>;

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
<h2>INFO</h2>
<br>
<?=$info?>



</div>
