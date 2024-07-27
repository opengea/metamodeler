<?
include "conf/config.php";
$debug=0;
$version="1.0b";
$info = "<b style='text-transform:initial !important'>Gmodeler is software developed by Jordi Berenguer to create global models of reality.</b><br><br>
<br>
Ontological hyperspace dimensions:<br>
<br>
3 Cartesian:<br>
<ul>
<li>1:TEO-PRA</li>
<li>2:SUB-OBJ</li>
<li>3:NOU-FEN</li>
</ul>
+1 Spherical Polar:<br>
<ul>
<li>4:PLA-MON</li>
</ul>
<br>
<br>
Model based on the Globalium, a global model of reality created by Lluís Maria Xirinacs.<br><br>";

function getPreferredLanguage($availableLanguages, $default = 'en') {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $acceptedLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $langs = [];
        foreach ($acceptedLanguages as $lang) {
            $lang = explode(';', $lang);
            $langs[] = $lang[0];
        }

        // Check each language against available languages
        foreach ($langs as $lang) {
            if (in_array($lang, $availableLanguages)) {
                return $lang;
            }
            // Check the language without the region code
            $langPrimary = substr($lang, 0, 2);
            if (in_array($langPrimary, $availableLanguages)) {
                return $langPrimary;
            }
        }
    }

    // Return default language if no match is found
    return $default;
}

// List of available languages in your application
$availableLanguages = ['en', 'ca']; 

// Detect the user's preferred language
$userPreferredLanguage = getPreferredLanguage($availableLanguages);

$userPreferredLanguage="en";


?>
