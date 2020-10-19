<?php

include "vendor/autoload.php";

use DiscordPHP\Session;

$clientId = 'YourClientId';

$secret = 'YourSecret';

$redirect = 'http://localhost/discord-web-hook/example.php';

$scops = ['identify', 'email', 'connections'];

$session = new Session(
    $clientId,
    $secret,
    $redirect,
    $scops
);

$api = new \DiscordPHP\DiscordPHP();

if (isset($_GET['code'])) {
    $session->requestAccessToken($_GET['code']);
    $api->setAccessToken($session->getAccessToken());

    print_r($api->getGuildAuditLog());
} else {
    header('Location: ' . $session->getAuthorizeUrl());
    die();
}
