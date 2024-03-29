
# Bitrix24 REST API

## Вызов REST с использованием входящего вебхука

### Установка

Перейдите в папку `/path/to/private` и выполните следующие команды:

```shell
cd /path/to/private

composer install
```

### Пример отправки заявки

```php
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

// Вебхук (обязательный параметр)
// ссылка указана для примера,
// в ЦРМ вам нужно создать новую ссылку или скопировать существующую
$webHookUrl = 'https://xxx.bitrix24.ru/rest/1/douasdqdsxSWgc3mgc1/';

// id источника (id можно узнать в ЦРМ) 
$sourceId = 8;

$bitrix = new BitrixCRM($webHookUrl, $sourceId);

// Поля из формы на сайте
$bitrix->name           = post('name', 'Заявка');
$bitrix->phone          = post('phone');
$bitrix->email          = post('email');
$bitrix->comments       = post('comment');
$bitrix->utmSource      = post('utm_source');
$bitrix->utmMedium      = post('utm_medium');
$bitrix->utmCampaign    = post('utm_campaign');
$bitrix->utmContent     = post('utm_content');
$bitrix->utmTerm        = post('utm_term');

// Пользовательское поле (Поля сущностей ЦРМ)
$bitrix->setField('UF_CRM_1622708417377', 'custom field value');

// Статус отправки
$out = ['status' => 'error'];
if ( $bitrix->send() ) {
    $out['status'] = 'ok';
}
echo json_encode($out);
```
