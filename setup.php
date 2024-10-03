<?
include "../conf/config.php";
$debug=0;
$version="1.0";

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

//$userPreferredLanguage="en";


?>
