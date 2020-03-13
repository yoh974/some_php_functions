<?php
require 'API.php';

if (file_exists("conf.json")) {
    $json = json_decode(file_get_contents("conf.json"), true);
    $url = $json['URL'];
    $login = $json['LOGIN'];
    $password = $json['MDP'];
    unset($json);

    $api = new API($url, $login, $password);
    $api->login("POST", "/key/");
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
}

