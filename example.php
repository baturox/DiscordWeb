<?php

include "vendor/autoload.php";

use DiscordPHP\Session;

$clientId = '644515994588676101';

$secret = 'WX2VoGsXcu3yVX_XhLV0FJwRDJRPtlmw';

$redirect = 'http://localhost:81/discord-web-hook/example.php';

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
