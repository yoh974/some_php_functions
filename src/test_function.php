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


while (true) {
    $url = readline("\nurl : ");
    getFileFromUrl($url);
}
