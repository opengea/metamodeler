<?php
error_reporting(0);
$filename="coords.dat";
$fp = fopen($filename, "r");
$coords=array();
while(!feof($fp))
{
   	$line=fgets($fp);
	$c=explode(":",$line);
	if ($c[0]) {
	$coords[$c[0]]=array();
	$d=explode(",",$c[1]);
	$coords[$c[0]]['x']=$d[0];
	$coords[$c[0]]['y']=$d[1];
	$coords[$c[0]]['z']=$d[2];
	}
}


$filename="superafins.dat";
$fp = fopen($filename, "r");
while(!feof($fp))
  {
	$line=fgets($fp);
	$terme=explode(":",$line);

//afins['Fantasia']   ={pos:situa(50,-25,50),color:[0,40,255],title:'Fantasia',type:'Afins',descr:''};

	$coord_no=explode("-",$terme[0]);
	$x1=trim($coords[$coord_no[0]]['x']);
	$y1=trim($coords[$coord_no[0]]['y']);
	$z1=trim($coords[$coord_no[0]]['z']);
        $x2=trim($coords[$coord_no[1]]['x']);
        $y2=trim($coords[$coord_no[1]]['y']);
        $z2=trim($coords[$coord_no[1]]['z']);
	
//if (trim($terme[1])=="Agitació") echo "Agit {$x1} comparant {$x2}\n";	
//if (trim($terme[1])=="Decisió viscuda") echo "Deci {$x1} comparant {$x2}\nDeci {$y1} comparant {$y2}\n";

/*
	if ($x1>0&&$x2>0) {
		if ($x1>$x2) $x=$x1-$x2; else $x=$x2-$x1;
	} else if ($x1<0&&$x2<0) {
		if (abs($x1)>abs($x2)) $x=abs($x1)-abs($x2); else $x=abs($x2)-abs($x1);
		$x=-$x;
        } else if ($x1<0&&$x2>0) {
                if (abs($x1)>abs($x2)) $x=abs($x1)-abs($x2); else $x=abs($x2)-abs($x1);
                $x=-$x;
        } else if ($x1>0&&$x2<0) {
                if (abs($x1)>abs($x2)) $x=abs($x1)-abs($x2); else $x=abs($x2)-abs($x1);
                $x=-$x;
	} else {
		$x=$x1-$x2;
	}


        if ($y1>0&&$y2>0) {
                if ($y1>$y2) $y=$y1-$y2; else $y=$y2-$y1;
        } else if ($y1<0&&$y2<0) {
                if (abs($y1)>abs($y2)) $y=abs($y1)-abs($y2); else $y=abs($y2)-abs($y1);
                $y=-$y;
        } else if ($y1<0&&$y2>0) {
                if (abs($y1)>abs($y2)) $y=abs($y1)-abs($y2); else $y=abs($y2)-abs($y1);
                $y=-$y;
        } else if ($y1>0&&$y2<0) {
                if (abs($y1)>abs($y2)) $y=abs($y1)-abs($y2); else $y=abs($y2)-abs($y1);
                $y=-$y;
        } else {
                $y=$y1-$y2;
        }

        if ($z1>0&&$z2>0) {
                if ($z1>$z2) $z=$z1-$z2; else $z=$z2-$z1;
        } else if ($z1<0&&$z2<0) {
                if (abs($z1)>abs($z2)) $z=abs($z1)-abs($z2); else $z=abs($z2)-abs($z1);
                $z=-$z;
        } else if ($z1<0&&$z2>0) {
                if (abs($z1)>abs($z2)) $z=abs($z1)-abs($z2); else $z=abs($z2)-abs($z1);
                $z=-$z;
        } else if ($z1>0&&$z2<0) {
                if (abs($z1)>abs($z2)) $z=abs($z1)-abs($z2); else $z=abs($z2)-abs($z1);
                $z=-$z;
        } else {
                $z=$z1-$z2;
        }

*/

/*
	$x1_=abs($x1); $x2_=abs($x2);
	$x=abs($x1-$x2)/2;
	if ($x1<$x2) $x=$x1+$x; else $x=$x2+$x;

        $y1_=abs($y1); $y2_=abs($y2);
        $y=abs($y1-$y2)/2;
        if ($y1<$y2) $y=$y1+$y; else $y=$y2+$y;

        $z1_=abs($z1); $z2_=abs($z2);
        $z=abs($z1-$z2)/2;
        if ($z1<$z2) $z=$z1+$z; else $z=$z2+$z;
*/

$colors=array("blue"=>"0,40,255","green"=>"0,255,0","magenta"=>"255,0,255","red"=>"255,0,0");

        $x=($x1-$x2)/2;  $x=abs($x); if ($x1>$x2) $x=$x2+$x; else $x=$x1+$x;
        $y=($y1-$y2)/2;  $y=abs($y); if ($y1>$y2) $y=$y2+$y; else $y=$y1+$y;
        $z=($z1-$z2)/2;  $z=abs($z); if ($z1>$z2) $z=$z2+$z; else $z=$z1+$z;
	$coord_ok="{$x},{$y},{$z}";
     if ($x>0&&$y>0&&$z>0) { $color=$colors['blue']; }
else if ($x<0&&$y>0&&$z>0) { $color=$colors['red']; }
else if ($x>0&&$y<0&&$z>0) { $color=$colors['magenta']; }
else if ($x>0&&$y>0&&$z<0) { $color=$colors['blue']; }
else if ($x<0&&$y<0&&$z<0) { $color=$colors['green']; }
else if ($x>0&&$y<0&&$z<0) { $color=$colors['green']; }
else if ($x<0&&$y>0&&$z<0) { $color=$colors['red']; }
else if ($x<0&&$y<0&&$z>0) { $color=$colors['magenta']; }


	echo "afins['".str_replace("'",'"',trim($terme[1]))."']={pos:situa(".$coord_ok."),color:[".$color."],title:'".str_replace("'",'"',trim($terme[1]))."',type:'Afins',descr:'".str_replace("'",'"',trim($terme[2]))."'};\n";

  }

fclose($fp);

