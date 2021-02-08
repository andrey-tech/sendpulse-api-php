<?php

/**
 * Трейт Campaigns. Содержит методы для работы с кампаниями SendPulse
 *
 * @author    andrey-tech
 * @copyright 2020-2021 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.1
 *
 * v1.0.0 (02.07.2020) Начальная версия
 * v1.0.1 (07.02.2021) Рефакторинг
 *
 */

declare(strict_types=1);

namespace App\SendPulse;

use Generator;

trait Campaigns
{
    /**
     * Возвращает информацию о кампании
     * @param  int $id ID кампании
     * @return array
     */
    public function getCampaign(int $id): array
    {
        return $this->request('GET', '/campaigns/' . $id);
    }

    /**
     * Возвращает список кампаний
     * @param  int|null $limit  Количество записей (необязательный параметр)
     * @param  int|null $offset Смещение выдачи (начиная с какой записи показывать)
     * @return array
     */
    public function getCampaigns(int $limit = null, int $offset = null): array
    {
        $params = [];

        if (! empty($limit)) {
            $params['limit'] = $limit;
        }

        if (! empty($offset)) {
            $params['offset'] = $offset;
        }

        return $this->request('GET', '/campaigns', $params);
    }

    /**
     * Возвращает список всех кампаний
     * @return Generator
     */
    public function getAllCampaigns(): Generator
    {
        return $this->getAll(function ($offset) {
            return $this->getCampaigns($limit = null, $offset);
        });
    }

    /**
     * Создает кампанию
     * @param array $params Параметры кампании
     * @return int
     */
    public function addCampaign(array $params): int
    {
        $response =  $this->request('POST', '/campaigns', $params);
        return $response['id'];
    }

    /**
     * Обновляет запланированную кампанию
     * @param int $id ID кампании
     * @param array $params Параметры кампании
     * @return array
     */
    public function updateCampaign(int $id, array $params): array
    {
        return $this->request('PATCH', '/campaigns/' . $id, $params);
    }

    /**
     * Отменяет отправку запланированной кампании
     * @param int $id ID кампании
     * @return array
     */
    public function deleteCampaign(int $id): array
    {
        return $this->request('DELETE', '/campaigns/' . $id);
    }

    /**
     * Возвращает список кампаний, которые создавались по данной адресной книге
     * @param  int      $id ID адресной книги
     * @param  int|null $limit  Количество записей (необязательный параметр)
     * @param  int|null $offset Смещение выдачи (начиная с какой записи показывать)
     * @return array
     */
    public function getAddressbookCampaigns(int $id, int $limit = null, int $offset = null): array
    {
        $params = [];

        if (! empty($limit)) {
            $params['limit'] = $limit;
        }

        if (! empty($offset)) {
            $params['offset'] = $offset;
        }

        return $this->request('GET', "/addressbooks/{$id}/campaigns", $params);
    }

    /**
     * Возвращает список всех кампаний, которые создавались по данной адресной книге
     * @param  int      $id ID адресной книги
     * @return Generator
     */
    public function getAllAddressbookCampaigns(int $id): Generator
    {
        return $this->getAll(function ($offset) use ($id) {
            return $this->getAddressbookCampaigns($id, $limit = null, $offset);
        });
    }
}
