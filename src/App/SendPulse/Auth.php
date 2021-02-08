<?php

/**
 * Трейт Auth. Содержит методы для авторизации в SendPulse
 *
 * @author    andrey-tech
 * @copyright 2020-2021 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.2
 *
 * v1.0.0 (01.07.2020) Начальная версия
 * v1.0.1 (19.07.2020) Рефакторинг
 * v1.0.2 (07.02.2021) Изменения для класса TokenStorage
 *
 */

declare(strict_types=1);

namespace App\SendPulse;

use App\SendPulse\TokenStorage\TokenStorage;
use App\SendPulse\TokenStorage\TokenStorageException;

trait Auth
{
    /**
     * ID клиента
     * @var string
     */
    private $clientId;

    /**
     * Секрет клиента
     * @var string
     */
    private $clientSecret;

    /**
     * Access token клиента
     * @var string
     */
    private $clientToken;

    /**
     * Объект класса, выполняющего хранение токенов
     * @var object
     */
    public $tokenStorage;


    /**
     * Счетчик числа попыток обновления access token при ответе API '401 Unauthorized'
     * @var int
     */
    private $reOAuth2Counter = 0;

    /**
     * Выполняет авторизацию пользователя в SendPulse
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return string
     * @throws SendPulseAPIException|TokenStorageException
     */
    public function auth(string $clientId, string $clientSecret): string
    {
        if (empty($clientId) || empty($clientSecret)) {
            throw new SendPulseAPIException("Пустой ID или секрет клиента");
        }

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        if (empty($this->tokenStorage)) {
            $this->tokenStorage = new TokenStorage();
        }

        $clientToken = $this->tokenStorage->load($clientId, $clientSecret);
        if (empty($clientToken)) {
            $clientToken = $this->getClientToken();
            $this->tokenStorage->save($clientToken, $clientId, $clientSecret);
        }

        $this->clientToken = $clientToken;

        return $clientToken;
    }

    /**
     * Выполняет повторную авторизацию в SendPulse при ответе '401 Unauthorized'
     * @return void
     * @throws SendPulseAPIException
     */
    private function reOAuth2()
    {
        $this->reOAuth2Counter++;

        // Проверяем счетчик числа попыток обновления access token
        if ($this->reOAuth2Counter > 1) {
            throw new SendPulseAPIException(
                "Не удалось обновить access token клиента ID {$this->clientId}"
            );
        }

        $clientToken = $this->getClientToken();
        $this->tokenStorage->save($clientToken, $this->clientId, $this->clientSecret);
        $this->clientToken = $clientToken;
    }

    /**
     * Получает access token клиента, посылая запрос к API SendPulse
     * @return string
     */
    private function getClientToken(): string
    {
        $params = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        $response = $this->request('POST', '/oauth/access_token', $params);

        return $response['access_token'];
    }
}
