<?php
require_once('Authorization.php');

$auth = new Authorization();

if ($_GET['logout'])
    $auth->logout();

if (!$_GET['code']) {
    $auth->redirect(AUTH_DIALOG_URL);
} else {
    $auth->setCode($_GET['code']);
    $token = $auth->getToken();

    if ($token) {
        $auth->redirect(APP_URL);
    }
}