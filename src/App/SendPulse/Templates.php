<?php

/**
 * Трейт Templates. Содержит методы для работы с шаблонами SendPulse
 *
 * @author    andrey-tech
 * @copyright 2020-2021 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (19.07.2020) Начальная версия
 * v1.0.1 (07.02.2021) Рефакторинг
 *
 */

declare(strict_types=1);

namespace App\SendPulse;

trait Templates
{
    /**
     * Возвращает информацию о шаблоне
     * @param  int $id ID шаблона
     * @return array
     */
    public function getTemplate(int $id): array
    {
        return $this->request('GET', '/template/' . $id);
    }

    /**
     * Возвращает список шаблонов
     * @param string $owner Фильтр по владельцу шаблона (me - пользовательские шаблоны, sendpulse - системные шаблоны)
     * @param string $lang Фильтр по языку шаблона (ru, en)
     * @return array
     */
    public function getTemplates(string $owner = null, string $lang = null): array
    {
        $path = '/templates';
        $params = [];

        if (! empty($lang)) {
            $path .= '/' . $lang;
        }

        if (! empty($owner)) {
            $params = [ 'owner' => $owner ];
        }

        return $this->request('GET', $path, $params);
    }

    /**
     * Добавляет шаблон
     * @param array $params Параметры шаблона
     * @return int
     */
    public function addTemplate(array $params): int
    {
        $response =  $this->request('POST', '/template', $params);
        return $response['real_id'];
    }

    /**
     * Обновляет шаблон
     * @param int $id ID шаблона
     * @param array $params Параметры шаблона
     * @return array
     */
    public function updateTemplate(int $id, array $params): array
    {
        return $this->request('POST', '/template/edit/' . $id, $params);
    }
}
