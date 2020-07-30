<?php

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = $arIp[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function apiWebvorkV1NewLead($post, $ip, $offerId, $counter = 0)
{
    $token = '89adaf22b88f5c0e3766e0c147046601'; // Заменяем на свой из кабинета

    $url = 'http://api.webvork.com/v1/new-lead?token=' . rawurlencode($token)
        . '&ip=' . rawurlencode($ip)
        . '&offer_id=' . rawurlencode($offerId)
        . '&name=' . rawurlencode($post['name'])
        . '&phone=' . rawurlencode($post['phone'])
        . '&country=' . rawurlencode($post['country'])
        . '&utm_medium=' . rawurlencode($post['utm_medium'])
        . '&utm_campaign=' . rawurlencode($post['utm_campaign'])
        . '&utm_content=' . rawurlencode($post['utm_content'])
        . '&utm_term=' . rawurlencode($post['utm_term']);


    $json = file_get_contents($url);
    $data = json_decode($json, 1);

    if ($data['status'] != 'ok') {
        if ($counter < 5) {
            sleep(1);
            return apiWebvorkV1NewLead($post, $ip, $offerId, ++$counter);
        } else {
            return false;
        }
    }

    if ($data['status'] == 'ok') {
        return true;
    }
}

apiWebvorkV1NewLead($_POST, getIp(), offerId:4);

header("Location: ../success.html");
