<?php

/**
 * Трейт Smtp. Содержит методы для работы с SMTP сервисом SendPulse
 *
 * @author    andrey-tech
 * @copyright 2020-2021 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (20.07.2020) Начальная версия
 * v1.0.1 (07.02.2021) Рефакторинг
 *
 */

declare(strict_types=1);

namespace App\SendPulse;

trait Smtp
{
    /**
     * Отправляет email одному или нескольким получателям
     * @param array $params Параметры письма
     * @return array
     */
    public function sendEmails(array $params): array
    {
        return $this->request('POST', '/smtp/emails', $params);
    }
}
