<?php

class Authorization
{
    private $code; //Код, необходимый для получения токена
    private $token; //Собственно, токен
    private $secret; //Ключ, необходимый для осуществления запросов через http соединение
    private $uid; //ID авторизовавшегося пользователя

    public function __construct()
    {
        require_once('config.php');
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setToken($token)
    {
        $this->token = $token;
        session_start();
        $_SESSION['token'] = $token;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
        $_SESSION['secret'] = $secret;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
        $_SESSION['uid'] = $uid;
    }

    public function getToken()
    {
        if (!$this->code) {
            exit('Ошибка.');
        }

        $curl = curl_init();
        $getAccessTokenParams = 'client_id=' . APP_ID . '&client_secret=' . APP_SECRET . '&code=' . $this->code . '&redirect_uri=' . REDIRECT_URL;
        curl_setopt($curl, CURLOPT_URL, ACCESS_TOKEN_URL . '?' . $getAccessTokenParams);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);
        curl_close($curl);

        $resultObj = json_decode($result);
        if ($resultObj->access_token) {
            $this->setToken($resultObj->access_token);
            $this->setUid($resultObj->user_id);
            $this->setSecret($resultObj->secret);
            return true;
        } else {
            return false;
        }
    }

    public function redirect($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$url);
        exit();
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirect(APP_URL);
    }


}