<?php

/**
 * Класс TokenStorageException. Обрабатывает исключения в классах пространства имен App\SendPulse\TokenStorage
 *
 * @author    andrey-tech
 * @copyright 2020-2021 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (21.07.2020) Начальный релиз
 * v1.0.1 (07.02.2021) Рефакторинг
 *
 */

declare(strict_types=1);

namespace App\SendPulse\TokenStorage;

use Exception;

class TokenStorageException extends Exception
{
    /**
     * Конструктор
     * @param string $message Сообщение об исключении
     * @param int $code Код исключения
     * @param Exception|null $previous Предыдущее исключение
     */
    public function __construct(string $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct("SendPulseAPI TokenStorage: " . $message, $code, $previous);
    }
}
