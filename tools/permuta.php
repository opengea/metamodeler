<?php

function permuta($arrays, $prefix = array()) {
    $count = count($arrays);
    
    if ($count === 0) {
        echo implode(' ', $prefix) . PHP_EOL;
    } else {
        $firstArray = array_shift($arrays);
        foreach ($firstArray as $element) {
            $newPrefix = $prefix;
            $newPrefix[] = $element;
            permuta($arrays, $newPrefix);
        }
    }
}

// Arrays amb els termes
$termes1 = array('TEO', 'PRA', 'TEO-PRA');
$termes2 = array('FEN', 'NOU', 'FEN-NOU');
$termes3 = array('OBJ', 'SUB', 'OBJ-SUB');
$termes4 = array('PLA', 'MON', 'PLA-MON');

// Generar les permutacions
echo "Permutacions dels termes:" . PHP_EOL;
permuta(array($termes1, $termes2, $termes3, $termes4));
?>
