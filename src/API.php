<?php


class API
{
    private $url;
    private $login;
    private $password;
    private $token;

    public function __construct($url, $login, $password)
    {
        $this->url = $url;
        $this->login = $login;
        $this->password = $password;
        $this->token = '';
    }

    public function post_login($endpoint)
    {
        if ($this->login !== "" && $this->password !== "") {
            $curl = curl_init();

            $login_infos = json_encode(
                array("email" => $this->login, "password" => $this->password));

            curl_setopt($curl, CURLOPT_POST, 1);

            if ($this->login && $this->password)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $login_infos);


            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($login_infos))
            );
            curl_setopt($curl, CURLOPT_URL, $this->url . $endpoint);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);

            curl_close($curl);
            $this->token = json_decode($result, true)['token'];
        }
    }

    public function getFromEndpoint($endpoint)
    {
        $curl = curl_init();

        //Si pas de token on implémente pas le mème header
        if ($this->token !== '') {
            $authorization = "Authorization: Bearer " . $this->token;
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        }else{
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        }

        curl_setopt($curl, CURLOPT_URL, $this->url . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}