<?php
/**
 * Класс SendPulseAPI. Выполняет запросы к REST API сервиса SendPulse
 *
 * @author    andrey-tech
 * @copyright 2020 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (01.07.2020) Начальный релиз
 * v1.0.1 (19.07.2020) Рефракторинг
 *
 */

declare(strict_types = 1);

namespace App\SendPulse;

use Closure;
use Generator;
use App\HTTP;

class SendPulseAPI
{
    use Auth;
    use Campaigns;
    use Addressbooks;
    use Templates;
    use Smtp;

    /**
     * URL для REST API
     * @var string
     */
    const URL = 'https://api.sendpulse.com';

    /**
     * Объект класса, выполняющего логирование
     * @var object
     */
    public $logger;

    /**
     * Объект класса \App\HTTP
     * @var object
     */
    public $http;

    /**
     * Последний ответ от API SendPulse
     * @var array
     */
    protected $lastResponse;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->http = new HTTP();
        // Не более 10 запросов к API в секунду (https://sendpulse.com/ru/integrations/api)
        $this->http->throttle = 10;
        $this->http->useCookies = false;
    }

    /**
     * Возвращает последний ответ от API SendPulse
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Отправляет запрос в API SendPulse
     * @param  string $method Метод запроса
     * @param  string $path Путь в URL запроса
     * @param  array  $params Параметры запроса
     * @return array|null
     */
    public function request(string $method, string $path, array $params = [])
    {
        $url = self::URL . $path;
        $headers = [];

        // Это запрос авторизации?
        $isAuth = $path === '/oauth/access_token';

        // Если это НЕ запрос авторизации
        if (! $isAuth) {
            // Проверка выполненной авторизации по наличию access token клиента
            if (empty($this->clientToken)) {
                throw new SendPulseAPIException("Требуется авторизация oAuth2()");
            }
            $headers[] = 'Authorization: Bearer ' . $this->clientToken;
        }

        // Логирование запроса
        if (isset($this->logger)) {
            $jsonParams = urldecode($this->toJSON($params, true));
            $this->logger->save("ЗАПРОС: {$method} {$path}" . PHP_EOL . $jsonParams, $this);
        }

        $this->lastResponse = $this->http->request($url, $method, $params, $headers);
     
        // Логирование ответа
        if (isset($this->logger)) {
            $jsonResponse = $this->toJSON($this->lastResponse, true);
            $this->logger->save("ОТВЕТ: {$method} {$path}" . PHP_EOL . $jsonResponse, $this);
        }

        // Проверка кода состояния HTTP
        if (! $this->http->isSuccess()) {
            $httpCode = $this->http->getHTTPCode();

            /**
             * Если ответ '401 Unauthorized' и это не запрос авторизации,
             * то обновляем access token клиента и повторно отправляем запрос
             */
            if ($httpCode === 401 && ! $isAuth) {
                $this->reOAuth2();

                $this->request($method, $path, $params);
                $this->reOAuth2Counter = 0;

                return $this->lastResponse;
            }

            $jsonParams = $this->toJSON($params);
            $jsonResponse = $this->toJSON($this->lastResponse);
            throw new SendPulseAPIException(
                "Ошибка: HTTP {$httpCode} при запросе {$method} {$path} ({$jsonParams}): {$jsonResponse}"
            );
        }

        return $this->lastResponse;
    }

    /**
     * Преобразует данные в строку JSON для сообщений об ошибке или лога
     * @param mixed $data Данные для преобразования
     * @param bool $prettyPrint Включает pretty print для JSON
     * @return string
     */
    protected function toJSON($data, bool $prettyPrint = false) :string
    {
        $encodeOptions = JSON_UNESCAPED_UNICODE|JSON_PARTIAL_OUTPUT_ON_ERROR;

        if ($prettyPrint) {
            $encodeOptions |= JSON_PRETTY_PRINT;
        }

        $jsonParams = json_encode($data, $encodeOptions);
        if ($jsonParams === false) {
            $jsonParams = print_r($data, true);
        }

        return $jsonParams;
    }

    /**
     * Загружает все сущности заданного типа
     * @param \Closure $closure Анонимная функция-замыкание для загрузки сущностей $closure(int $offset)
     *                          $offset - смещение выдачи
     * @return \Generator
     */
    public function getAll(Closure $closure) :Generator
    {
        $offset = 0;
        do {
            $entities = $closure($offset);
          
            yield $entities;

            $count = count($entities);
            if ($count == 0) {
                break;
            }

            $offset += $count;
        } while (true);
    }
}
