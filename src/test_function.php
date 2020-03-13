<?php
require 'API.php';

if(file_exists("conf.json"))
{
    $json = json_decode(file_get_contents("conf.json"),true);
    $url = $json['URL'];
    $login = $json['LOGIN'];
    $password = $json['MDP'];
    unset($json);

    $api = new API($url, $login, $password);
    $api->login("POST", "/key/");
    $programs = json_decode($api->getFromEndpoint("/programs/"), true);
    $fp = fopen('programs.json', 'w+');


    foreach ($programs as $program) {
        $program_infos = $api->getFromEndpoint("/programs/" . $program["programId"]."/");
        fwrite($fp, json_encode(
            json_decode(
                $program_infos))
        );

    }

    fclose($fp);
}else{
    echo "Fichier de configuration inexistant";
}

