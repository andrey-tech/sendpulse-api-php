# SendPulse API PHP Wrapper

![SendPulse logo](./assets/sendpulse-logo.png)

Простая обертка на PHP7+ для работы с [REST API SendPulse](https://sendpulse.com/ru/integrations/api) с троттлингом запросов,
выводом отладочной информации в STDOUT и логированием в файл.

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Установка](#%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0)
- [Класс `SendPulseAPI`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-sendpulseapi)
    - [Авторизация пользователя](#%D0%90%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D1%8F-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F)
        - [Хранение токена](#%D0%A5%D1%80%D0%B0%D0%BD%D0%B5%D0%BD%D0%B8%D0%B5-%D1%82%D0%BE%D0%BA%D0%B5%D0%BD%D0%B0)
            - [Интерфейс `TokenStorageInterface`](#%D0%98%D0%BD%D1%82%D0%B5%D1%80%D1%84%D0%B5%D0%B9%D1%81-tokenstorageinterface)
            - [Класс `FileStorage`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-filestorage)
            - [Использованиe собственного класса для сохранения токенов](#%D0%98%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8e-%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0-%D0%B4%D0%BB%D1%8F-%D1%81%D0%BE%D1%85%D1%80%D0%B0%D0%BD%D0%B5%D0%BD%D0%B8%D1%8F-%D1%82%D0%BE%D0%BA%D0%B5%D0%BD%D0%BE%D0%B2)
    - [Методы для работы с адресными книгами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B0%D0%B4%D1%80%D0%B5%D1%81%D0%BD%D1%8B%D0%BC%D0%B8-%D0%BA%D0%BD%D0%B8%D0%B3%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с кампаниями](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8)
    - [Методы для работы с шаблонами](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD%D0%B0%D0%BC%D0%B8)
    - [Методы для работы с SMTP сервисом](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-smtp-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81%D0%BE%D0%BC)
    - [Вспомогательные методы класса](#%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
- [Вспомогательные классы](#%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D1%8B)
    - [Класс `\App\HTTP`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-apphttp)
    - [Класс `\App\DebugLogger`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-appdebuglogger)
- [Автор](#%D0%90%D0%B2%D1%82%D0%BE%D1%80)
- [Лицензия](#%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F)

<!-- /MarkdownTOC -->

<a id="%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F"></a>
## Требования

- PHP >= 7.0.
- класс [`\App\HTTP`](https://github.com/andrey-tech/http-client-php) - НТТР(S) клиент с троттлингом запросов и выводом отладочной информации в STDOUT;
- класс [`\App\DebugLogger`](https://github.com/andrey-tech/debug-logger-php) - логгер, cохраняющий отладочную информацию в файл (необязательный);
- Произвольный автозагрузчик классов, реализующий стандарт [PSR-4](https://www.php-fig.org/psr/psr-4/).


<a id="%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0"></a>
## Установка

Установка через composer:
```
$ composer require andrey-tech/sendpulse-api-php:"^1.0"
```

или добавить

```
"andrey-tech/sendpulse-api-php": "^1.0"
```

в секцию require файла composer.json.

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-sendpulseapi"></a>
## Класс `SendPulseAPI`

Для работы с REST API SendPulse используется класс `\App\SendPulse\SendPulseAPI`.  
При возникновении ошибок выбрасывается исключение с объектом класса `\App\SendPulse\SendPulseAPIException`.  
В настоящее в классе частично реализованы методы для работы со следующими сервисами SendPulse:

- [Email сервис](https://sendpulse.com/ru/integrations/api/bulk-email)
- [SMTP сервис](https://sendpulse.com/ru/integrations/api/smtp)

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D1%8F-%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F"></a>
### Авторизация пользователя

Метод для авторизации пользователя находится в трейте `\App\SendPulse\Auth`:

 - `auth(string $clientId, string $clientSecret) :string` Выполняет авторизацию клиента и возвращает персональный ключ (токен), 
    который также сохраняется в хранилище токенов.
    * `$clientId` - ID клиента (пользователя);
    * `$clientSecret` - секрет клиента (пользователя).

```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();
    $sendPulse->auth($clientId, $clientSecret);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

Получение нового токена, по истечении его срока действия, происходит автоматически,
когда в ответ на запрос к API SendPulse возвращается ответ с HTTP-статусом `401 Unauthorized`.

<a id="%D0%A5%D1%80%D0%B0%D0%BD%D0%B5%D0%BD%D0%B8%D0%B5-%D1%82%D0%BE%D0%BA%D0%B5%D0%BD%D0%B0"></a>
#### Хранение токена

Сохранение и загрузка токена выполняется с помощью классов, реализующих интерфейс `\App\SendPulse\TokenStorage\TokenStorageInterface`.

<a id="%D0%98%D0%BD%D1%82%D0%B5%D1%80%D1%84%D0%B5%D0%B9%D1%81-tokenstorageinterface"></a>
##### Интерфейс `TokenStorageInterface`

Интерфейс `\App\SendPulse\TokenStorage\TokenStorageInterface` определяет два метода:

- `save(string $token, string $clientId, string $clientSecret) :void` Сохраняет токен.
    * `$token` - токен;
    * `$clientId` - ID клиента;
    * `$clientSecret` - секрет клиента.
- `load(string $clientId, string $clientSecret) :?string` Загружает токен и возвращает его.
    Метод должен возвращать `null`, когда нет сохраненного токена.
    * `$clientId` - ID клиента;
    * `$clientSecret` - секрет клиента.

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-filestorage"></a>
##### Класс `FileStorage`

По умолчанию для сохранения и загрузки токенов используется класс `\App\SendPulse\TokenStorage\FileStorage`,
который хранит токены в отдельных файлах для каждого сочетания `$clientId` и `$clientSecret`, использованного при авторизации в методе `auth()`.
Класс реализует интерфейс `\App\SendPulse\TokenStorage\TokenStorageInterface` и содержит собственные методы:

- `__construct(string $storageFolder = 'tokens/')` Конструктор класса.
    * `$storageFolder` - каталог для хранения файлов токенов.
- `hasTokens(string $clientId, string $clientSecret) :bool` Проверяет существует ли токен для заданного сочетания `$clientId` и `$clientSecret`.
    * `$clientId` - ID клиента;
    * `$clientSecret` - секрет клиента.

При возникновении ошибок выбрасыватся исключение класса `\App\SendPulse\TokenStorage\TokenStorageException`. 

<a id="%D0%98%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8e-%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0-%D0%B4%D0%BB%D1%8F-%D1%81%D0%BE%D1%85%D1%80%D0%B0%D0%BD%D0%B5%D0%BD%D0%B8%D1%8F-%D1%82%D0%BE%D0%BA%D0%B5%D0%BD%D0%BE%D0%B2"></a>
##### Использованиe собственного класса для сохранения токенов

Пример использования собственного класса для сохранения токенов в базе данных:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();

    // Устанавливаем объект класса, обеспечивающего хранение токенов в базе данных
    $sendPulse->tokenStorage = new \App\SendPulse\TokenStorage\DatabaseStorage();

    $sendPulse->auth($clientId, $clientSecret);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

Структура класса `\App\SendPulse\TokenStorage\DatabaseStorage`:
```php
<?php
namespace App\SendPulse\TokenStorage;

class DatabaseStorage implements TokenStorageInterface
{
    /**
     * Сохраняет токен
     * @param string  $token Токен
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return void
     * @throws TokenStorageException
     */
    public function save(string $token, string $clientId, string $clientSecret)
    {
        // Здесь токен сохраняется в базе данных
    }

    /**
     * Загружает токен
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return array|null
     * @throws TokenStorageException
     */
    public function load(string $clientId, string $clientSecret)
    {
        // Здесь токен извлекается из базы данных
    }
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%B0%D0%B4%D1%80%D0%B5%D1%81%D0%BD%D1%8B%D0%BC%D0%B8-%D0%BA%D0%BD%D0%B8%D0%B3%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с адресными книгами

Методы для работы с адресными книгами находятся в трейте `\App\SendPulse\Addressbooks`:

- `getAddressbook(int $id) :array` [Возвращает информацию об адресной книге](https://sendpulse.com/ru/integrations/api/bulk-email#list-info).
    * `$id` - ID адресной книги.
- `getAddressbooks(int $limit = null, int $offset = null) :array` [Возвращает список адресных книг](https://sendpulse.com/ru/integrations/api/bulk-email#lists-list) (не более 100).
    * `$limit` - количество записей;
    * `$offset` - cмещение выдачи (начиная с какой записи показывать).
- `getAllAddressbooks() :\Generator` Позволяет получить список всех адресных книг, возвращая генератор.
- `addAddressbook(array $params) :int` [Добавляет адресную книгу](https://sendpulse.com/ru/integrations/api/bulk-email#create-list) и возвращает ID адресной книги.
    * `$params` - параметры адресной книги.
- `updateAddressbook(int $id, array $params) :array` [Обновляет адресную книгу](https://sendpulse.com/ru/integrations/api/bulk-email#edit-list).
    * `$id` - ID адресной книги;
    * `$params` - параметры адресной книги.
- `deleteAddressbook(int $id) :array` [Удаляет адресную книгу](https://sendpulse.com/ru/integrations/api/bulk-email#delete-list).
    * `$id` - ID адресной книги.
- `addAddressbookEmails(int $id, array $emails) :array` [Добавляет email адреса в адресную книгу](https://sendpulse.com/ru/integrations/api/bulk-email#add-email).
    * `$id` - ID адресной книги;
    * `$emails` - список email адресов или параметры контактов.
- `deleteAddressbookEmails(int $id, array $emails) :array` [Удаляет email адреса из адресной книги](https://sendpulse.com/ru/integrations/api/bulk-email#delete-email)
    * `$id` - ID адресной книги;
    * `$emails` - список email адресов.
- `getAddressbookEmails(int $id, int $limit = null, int $offset = null) :array` [Возвращает список email адресов в адресной книге](https://sendpulse.com/ru/integrations/api/bulk-email#list-emails) (не более 100).
    * `$id` - ID адресной книги;
    * `$limit` - количество записей;
    * `$offset` - cмещение выдачи (начиная с какой записи показывать).
- `getAllAddressbookEmails(int $id) :\Generator` Позволяет получить список всех email адресов в адресной книге, возвращая генератор.
    * `$id` - ID адресной книги.
- `getAddressbookEmailsTotal(int $id) :int` [Возвращает общее количество email адресов в адресной книге](https://sendpulse.com/ru/integrations/api/bulk-email#get_total_addresses).
    * `$id` - ID адресной книги.
- `getAddresbookVariables(int $id) :array` [Возвращает список переменных для адресной книги](https://sendpulse.com/ru/integrations/api/bulk-email#variables).
    * `$id` - ID адресной книги.

Примеры работы с адресными книгами:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();
    $sendPulse->auth($clientId, $clientSecret);

    // Загружаем все адресные книги
    $num = 0;
    $generator = $sendPulse->getAllAddressbooks();
    foreach ($generator as $addressbooks) {
        foreach ($addressbooks as $addressbook) {
            $num++;
            echo "[{$num}] {$addressbook['id']}: {$addressbook['name']}" . PHP_EOL;
        }
    }

    // Получаем информацию об адресной книге по ID книги
    $addressbookId = 20143254;
    $response = $this->getAddressbook($addressbookId);
    print_r($response);

    // Добаляем новую адресную книгу
    $addressbookId = $sendPulse->addAddressbook([
        'bookName' => 'Тестовая адресная книга'
    ]);
    print_r($addressbookId);

    // Формируем список контактов для адресной книги
    $emails = [
        [
            'email' => 'test1@example.com',
            'variables' => [
                'Name' => 'Тест контакт 1',
                'Phone' => '+79450000001'
            ]
        ],
        [
            'email' => 'test2@example.com',
            'variables' => [
                'Name' => 'Тест контакт 2',
                'Phone' => '+79450000002'
            ]
        ],
        [
            'email' => 'test3@example.com',
            'variables' => [
                'Name' => 'Тест контакт 3',
                'Phone' => '+79450000003'
            ]
        ]
    ];

    // Добавляем контакты в адресную книгу
    $response = $sendPulse->addAddressbookEmails($addressbookId, $emails);
    print_r($response);

    // Получаем первые 100 контаков из адресной книги
    $response = $sendPulse->getAddressbookEmails($addressbookId);
    print_r($response);

    // Получаем количество email адресов в адресной книге
    $response = $sendPulse->getAddressbookEmailsTotal($addressbookId);
    print_r($response);

    // Получаем список переменных для адресной книги
    $response = $sendPulse->getAddresbookVariables($addressbookId);
    print_r($response);

    // Удаляем адресную книгу
    $response = $sendPulse->deleteAddressbook($addressbookId);
    print_r($response);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D0%BA%D0%B0%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8F%D0%BC%D0%B8"></a>
### Методы для работы с кампаниями

Методы для работы с кампаниями находятся в трейте `\App\SendPulse\Campaigns`:

- `getCampaign(int $id) :array` [Возвращает информацию о кампании](https://sendpulse.com/ru/integrations/api/bulk-email#campaign-info).
    * `$id` - ID кампании;
- `getCampaigns(int $limit = null, int $offset = null) :array` [Возвращает список кампаний](https://sendpulse.com/ru/integrations/api/bulk-email#campaigns-list) (не более 100).
    * `$limit` - количество записей;
    * `$offset` - cмещение выдачи (начиная с какой записи показывать).
- `getAllCampaigns() :\Generator` Позволяет получить список всех кампаний, возвращая генератор.
- `addCampaign(array $params) :int` [Добавляет кампанию](https://sendpulse.com/ru/integrations/api/bulk-email#create-campaign) и возвращает ID кампании.
    * `$params` - параметры кампании.
- `updateCampaign(int $id, array $params) :array` [Обновляет запланированную кампанию](https://sendpulse.com/ru/integrations/api/bulk-email#edit-campaign).
    * `$id` - ID кампании;
    * `$params` - параметры кампании.
- `deleteCampaign(int $id) :array` [Отменяет отправку запланированной кампании](https://sendpulse.com/ru/integrations/api/bulk-email#cancel-send).
    * `$id` - ID кампании.
- `getAddressbookCampaigns(int $id, int $limit = null, int $offset = null) :array` [Возвращает список кампаний, которые создавались по данной адресной книге](https://sendpulse.com/ru/integrations/api/bulk-email#campaigns-list_book) (не более 100).
    * `$id` - ID адресной книги;
    * `$limit` - количество записей;
    * `$offset` - cмещение выдачи (начиная с какой записи показывать).
- `getAllAddressbookCampaigns(int $id) :\Generator` Позволяет получить список всех кампаний, которые создавались по данной адресной книге, возвращая генератор.
    * `$id` - ID адресной книги.

Примеры работы с кампаниями:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();
    $sendPulse->auth($clientId, $clientSecret);

    // Получаем список все кампаний
    $num = 0;
    $generator = $sendPulse->getAllCampaigns();
    foreach ($generator as $campaigns) {
        foreach ($campaigns as $campaign) {
            $num++;
            echo "[{$num}] {$campaign['id']}: {$campaign['name']}" . PHP_EOL;
        }
    }

    // Получаем информацию о кампании по ID кампании
    $campaignId = 21058230;
    $response = $this->getCampaign($campaignId);
    print_r($response);

    // Формируем параметры новой кампании
    $sendTime = new \DateTime();
    $sendTime->add(new \DateInterval("PT30M"));
    $params = [
        'sender_name'  => 'Тестовый отправитель',
        'sender_email' => 'test@example.com',
        'name'         => 'Тестовая рассылка',
        'subject'      => 'Тестовая рассылка',
        'template_id'  => 2308544,
        'list_id'      => 79093323,
        'send_date'    => $sendTime->format('Y-m-d H:i:s')
    ];

    // Добавляем новую кампанию
    $campaignId = $sendPulse->addCampaign($params);
    print_r($campaignId);

    // Отменяем отправку запланированной кампании
    $response = $sendPulse->deleteCampaign($campaignId);
    print_r($response);

    // Получаем список всех кампаний, которые создавались по данной адресной книге
    $num = 0;
    $addressbookId = 20143254;
    $generator = $sendPulse->getAllAddressbookCampaigns($addressbookId);
    foreach ($generator as $campaigns) {
        foreach ($campaigns as $campaign) {
            $num++;
            echo "[{$num}] {$campaign['id']}: {$campaign['name']}" . PHP_EOL;
        }
    }

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD%D0%B0%D0%BC%D0%B8"></a>
### Методы для работы с шаблонами

Методы для работы с шаблонами находятся в трейте `\App\SendPulse\Templates`:

- `getTemplate(int $id) :array` [Возвращает информацию о шаблоне](https://sendpulse.com/ru/integrations/api/bulk-email#template-id).
    * `$id` - ID шаблона.
- `getTemplates(string $owner = null, string $lang = null) :array` [Возвращает список шаблонов](https://sendpulse.com/ru/integrations/api/bulk-email#template-list) c возможностью фильтрации.
    * `$owner` - фильтр по владельцу шаблона (`me` - пользовательские шаблоны, `sendpulse` - системные шаблоны);
    * `$lang` - фильтр по языку шаблона (`ru`, `en`).
- `addTemplate(array $params) :int` [Добавлят шаблон](https://sendpulse.com/ru/integrations/api/bulk-email#create-template) и возвращает real ID шаблона.
    * `$params` - параметры шаблона.
- `updateTemplate(int $id, array $params) :array` [Обновляет шаблон](https://sendpulse.com/ru/integrations/api/bulk-email#edit-template).
    * `$id` - ID шаблона;
    * `$params` - параметры шаблона.

Примеры работы с шаблонами:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();
    $sendPulse->auth($clientId, $clientSecret);

    // Получаем список всех собственных шаблонов
    $response = $sendPulse->getTemplates($owner = 'me');
    $num = 0;
    foreach ($response as $tpl) {
        $num++;
        echo "[{$num}] {$tpl['real_id']}: {$tpl['name']}" . PHP_EOL;
    }

    // Получаем информацию о шаблоне
    $templateId = 1318345;
    $response = $sendPulse->getTemplate($templateId);
    print_r($response);

    // Формируем параметры нового шаблона
    $params = [
        'name' => 'Тестовый шаблон',
        'body' => 'PHA+RXhhbXBsZSB0ZXh0PC9wPg==',
        'lang' => 'ru'
    ];

    // Добавляем шаблон
    $templateId = $sendPulse->addTemplate($params);
    print_r($templateId);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%B4%D0%BB%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B-%D1%81-smtp-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81%D0%BE%D0%BC"></a>
### Методы для работы с SMTP сервисом

Методы для работы с SMTP сервисом находятся в трейте `\App\SendPulse\Smtp`:

- `sendEmails(array $params) :array` [Отправляет письмо](https://sendpulse.com/ru/integrations/api/smtp#send-email-smtp).
  * `$params` - параметры письма.

Примеры работы с SMTP сервисом:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();
    $sendPulse->auth($clientId, $clientSecret);

    // Формируем параметры письма
    $params = [
        'email' => [
            'html'=> 'PHA+RXhhbXBsZSB0ZXh0PC9wPg==',
            'text' => "Текст письма",
            'subject' => 'Тестовое письмо',
            'from' => [
                'name' => 'Тестовый отправитель',
                'email' => 'sender@example.com'
            ],
        ],
        'to' => [
            [
                'name' => 'Тестовый получатель',
                'email' => 'recipient1@example.com'
            ]
        ]
    ];

    // Отправляем письмо
    $response = $sendPulse->sendEmails($params);
    print_r($response);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0"></a>
###  Вспомогательные методы класса

- `getLastResponse()` Возвращает последний ответ API SendPulse.
- `request(string $method, string $path, array $params = []) ?array` Отправлет запрос в API SendPulse и возвращает ответ.
    * `$method` - метод запроса (GET, POST, PATCH, PUT, DELETE);
    * `$path` - путь в URL запроса;
    * `$params` - параметры запроса.
- `getAll(Closure $closure) :\Generator` Позволяет загрузить все сущности заданного типа, возвращая генератор.
    * `$closure` - анонимная функция-замыкание для загрузки сущностей: `$closure(int $offset)`, где `$offset` - смещение выдачи.

Примеры использования вспомогательных методов:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();
    $sendPulse->auth($clientId, $clientSecret);

    // Получаем черный список email адресов
    $response = $sendPulse->request('GET', '/blacklist');
    print_r($response);

    // Получаем список всех отправленных писем
    $generator = $sendPulse->getAll(function ($offset) use ($sendPulse) {
        return $sendPulse->request('GET', '/smtp/emails', [ 'offset' => $offset ]);
    });
    $num = 0;
    foreach ($generator as $emails) {
        foreach ($emails as $email) {
            $num++;
            echo "[{$num}] {$email['smtp_answer_code_explain']} {$email['recipient']}" . PHP_EOL;
        }
    }

    // Получаем последний ответ API SendPulse
    $response = getLastResponse();
    print_r($response);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%92%D1%81%D0%BF%D0%BE%D0%BC%D0%BE%D0%B3%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D1%8B"></a>
## Вспомогательные классы

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-apphttp"></a>
### Класс `\App\HTTP`

Класс [`\App\HTTP`](https://github.com/andrey-tech/http-client-php) обеспечивает:

- формирование POST запросов к API SendPulse по протоколу HTTPS;
- троттлинг запросов к API на требуемом уровне - [не более 10-и запросов в секунду](https://sendpulse.com/ru/integrations/api#description);
- вывод отладочной информации о запросах к API в STDOUT.

При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`.

Пример использования класса:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;
use \App\HTTP;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();

    // Устанавливаем максимальный уровень вывода отладочных сообщений в STDOUT
    $sendPulse->http->debugLevel = HTTP::DEBUG_URL |  HTTP::DEBUG_HEADERS | HTTP::DEBUG_CONTENT;

    // Устанавливаем троттлинг запросов на уровне не более 1 запроса в секунду
    $sendPulse->http->throttle = 1;

    // Устанавливаем таймаут обмена данными c API в 30 секунд
    $sendPulse->http->curlTimeout = 30;

    $sendPulse->auth($clientId, $clientSecret);

    // Получаем список отправителей email
    $response = $sendPulse->request('GET', '/senders');
    print_r($response);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-appdebuglogger"></a>
### Класс `\App\DebugLogger`

Класс [`\App\DebugLogger`](https://github.com/andrey-tech/debug-logger-php) обеспечивает логирование запросов и ответов к API SendPulse в файл.  
При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`. 

Пример использования класса:
```php
use \App\SendPulse\SendPulseAPI;
use \App\SendPulse\SendPulseAPIException;
use \App\AppException;
use \App\DebugLogger;

try {
    $clientId = 'acbdef0123456789abcdef0123456789';
    $clientSecret = 'acbdef0123456789abcdef0123456789';

    $sendPulse = new SendPulseAPI();

    // Создаем объект класса логирования
    $logFileName = 'debug_sendpulseapi.log';
    $logger = DebugLogger::instance($logFileName);

    // Устанавливаем каталог для сохранения лог файлов
    $logger->logFileDir = 'logs/';

    // Включаем логирование
    $logger->isActive = true;

    // Устанавливаем объект класса логирования
    $sendPulse->logger = $logger;

    $sendPulse->auth($clientId, $clientSecret);

    // Получаем список отправителей email
    $response = $sendPulse->request('GET', '/senders');
    print_r($response);

} catch (SendPulseAPIException|TokenStorageException|AppException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
```

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80"></a>
## Автор

© 2020 andrey-tech

<a id="%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F"></a>
## Лицензия

Данный код распространяется на условиях лицензии [MIT](./LICENSE).
