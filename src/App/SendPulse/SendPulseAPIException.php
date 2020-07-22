<?php

/**
 * Обработчик исключений в классе SendPulseAPI
 *
 * @author    andrey-tech
 * @copyright 2020 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (01.07.2020) Начальный релиз.
 *
 */

declare(strict_types = 1);

namespace App\SendPulse;

use Exception;

class SendPulseAPIException extends Exception
{
    /**
     * Конструктор
     * @param string $message Сообщение об исключении
     * @param int $code Код исключения
     * @param \Exception|null $previous Предыдущее исключение
     */
    public function __construct(string $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct("App SendPulseAPI: " . $message, $code, $previous);
    }
}
