<?php
require 'API.php';
require 'time_functions.php';
require 'SimpleXMLPlugiMMO.php';
require 'file_manipulation.php';

/*if (file_exists("conf.json")) {
    $json = json_decode(file_get_contents("conf.json"), true);
    $url = $json['URL'];
    $login = $json['LOGIN'];
    $password = $json['MDP'];
    unset($json);

    $api = new API($url, $login, $password);
    $api->post_login("/key/");
    $programs = json_decode($api->getFromEndpoint("/programs/"), true);
    $fp = fopen('programs.json', 'w+');
    $program_json = array();

    foreach ($programs as $program) {
        $program_json[] = json_decode($api->getFromEndpoint("/programs/" . $program["programId"] . "/"), true);
    }

    fwrite($fp, json_encode(
            $program_json, JSON_UNESCAPED_UNICODE)
    );

    fclose($fp);

} else {
    echo "Fichier de configuration inexistant";
}*/



/*$time_start = microtime(true);

sleep(10);

$time_end = microtime(true);
$time = convert_time(floor($time_end - $time_start));


echo "$time\n";*/
$tab_urls = [
    "https://espace-partenaires-acantys.fr/wp-content/uploads/2019/03/Plaquette_Calz%C3%A9a_BD.pdf",
    "https://espace-partenaires-acantys.fr/wp-content/uploads/2019/06/001-Notice-Descriptive-FLOWER.pdf",
    "https://espace-partenaires-acantys.fr/wp-content/uploads/2018/11/002-Plan-de-travail-5ID.png",
    "https://intranet.creation-developpement-patrimoine.com/img_residences/736/couverture.jpg",
    "https://img.greencity.iwit.pro/coeur_floreal/236802_06._grille_de_prix_tva_5"
];

foreach ($tab_urls as $url) {
    getFileFromUrl($url);
}
