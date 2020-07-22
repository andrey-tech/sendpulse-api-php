<?php

/**
 * Интерфейс TokenStorageInterface. Определяет методы для сохранения и загрузки токенов
 *
 * @author    andrey-tech
 * @copyright 2020 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (21.07.2020) Начальный релиз
 *
 */

declare(strict_types = 1);

namespace App\SendPulse\TokenStorage;

interface TokenStorageInterface
{
    /**
     * Загружает токен
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return array|null
     * @throws TokenStorageException
     */
    public function load(string $clientId, string $clientSecret);

    /**
     * Сохраняет токен
     * @param string  $token Токен для сохранения
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return void
     * @throws TokenStorageException
     */
    public function save(string $token, string $clientId, string $clientSecret);
}
