<?php

require __DIR__ . '/../private/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
    echo json_encode(['error' => 'request method must be POST']);
    exit;
}

function post($key, $default='') {
    if (array_key_exists($key, $_POST) && isset($_POST[$key])) {
        return trim($_POST[$key]);
    }
    return $default;
}

$bitrix = new BitrixCRM('https://xxx.bitrix24.ru/rest/1/douasdqdsxSWgc3mgc1/', 8);

$bitrix->name           = post('name', 'Заявка');
$bitrix->phone          = post('phone');
$bitrix->email          = post('email');
$bitrix->comments       = post('comment');
$bitrix->utmSource      = post('utm_source');
$bitrix->utmMedium      = post('utm_medium');
$bitrix->utmCampaign    = post('utm_campaign');
$bitrix->utmContent     = post('utm_content');
$bitrix->utmTerm        = post('utm_term');

$bitrix->setField('UF_CRM_1622708417377', 'custom field value');

$out = ['status' => 'error'];
if ( $bitrix->send() ) {
    $out['status'] = 'ok';
}

echo json_encode($out);
