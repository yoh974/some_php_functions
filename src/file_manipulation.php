<?php


/**
 * @param string $url
 * @param string $login
 * @param string $password
 * @param string $old_file
 * @return bool|string
 */
function getFileFromUrl(string $url, string $login = "", string $password = "", string $old_file = "")
{
    if ($url !== "") {
        $imgbinary = false;

        $url = formatURL($url);

        $fileSize = getDistantFileSize($url);

        if (file_exists($old_file)) {
            $oldFileSize = ($old_file !== "") ? filesize($old_file) : 0;
        }
        else {
            $oldFileSize = 0;
        }

        if ((int)$fileSize !== $oldFileSize || $oldFileSize === 0) {

            $imgbinary = getDataFromURL($url, $login, $password);


        }
        else {
            $imgbinary = "Fichier déjà présent";
        }
        $file = fopen("./tmp", "r");
        $content = fread($file, 500);

        //don't persist hmtl pages
        if (strpos($content, "<html") !== false) {
            fclose($file);
            echo "Pas un fichier une page html";


            return false;
        }


        fclose($file);
        return $imgbinary;

    }
    echo "Lien vide";
    return false;
}

/**
 * @param string $url
 * @return string|string[]
 */
function formatURL(string $url)
{


    $url = preg_replace(["/^\/\//", "/\s{2,}/m"], ["", " "], $url);

    $url = urlencode(trim($url));

    $url = str_replace(['%2F', '%3A'], ['/', ':'], $url);
    $url = urldecode($url);
    $url = str_replace(" ", "%20", $url);

    return addhttp($url);

}

function addhttp($url)
{
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

/**
 * @param string $url
 * @return int|mixed
 */
function getDistantFileSize(string $url)
{
    $fileSize = 0;
    $headers = getHeaderFromURL($url);

    if ($headers !== false) {
        $headers = array_change_key_case($headers);
    }

    $param = 'content-length';
    if (isset($headers[$param])) {
        if (is_array($headers[$param])) {
            $fileSize = (int)$headers[$param][1];
        }
        else {
            $fileSize = $headers[$param];
        }
    }


    return $fileSize;
}

/**
 * @param $url
 * @param $login
 * @param $password
 * @return bool|string
 */
function getDataFromURL($url, $login = "", $password = "")
{
    /*
      Here is a script that is usefull to :
      - login to a POST form,
      - store a session cookie,
      - download a file once logged in.
    */
    $tmp_file = fopen("./tmp", "wb");
    // INIT CURL
    $ch = curl_init();
    // SET URL FOR THE POST FORM LOGIN
    curl_setopt($ch, CURLOPT_FILE, $tmp_file);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // SET POST PARAMETERS : FORM VALUES FOR EACH FIELD

    if ($login !== "" && $password !== "") {
        // ENABLE HTTP POST
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'login=' . $login . '&password=' . $password);
    }

    // EXECUTE REQUEST (FILE DOWNLOAD)
    $return = curl_exec($ch);

    if ($return === false) {
        echo 'Erreur Curl : ' . curl_error($ch) . " URL : $url ";
        curl_close($ch);
        return false;
    }
    else {
        curl_close($ch);
        fclose($tmp_file);
        return true;
    }

}

function getHeaderFromURL($url, $login = "", $password = "", $method = 'GET')
{
    if ($password !== "" && $login !== "" && $method === 'POST') {
        $postdata = http_build_query(
            [
                'login' => $login,
                'password' => $password
            ]
        );
        stream_context_set_default([
            'http' => [
                'method' => $method,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $postdata
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true]
        ]);
    }
    stream_context_set_default(['ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true]]);

    return @get_headers($url, 1);
}
