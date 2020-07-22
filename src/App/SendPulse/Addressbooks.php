<?php

/**
 * Трейт Addressbooks. Содержит методы для работы с адресными книгами SendPulse
 *
 * @author    andrey-tech
 * @copyright 2020 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (19.07.2020) Начальная версия
 *
 */
declare(strict_types = 1);

namespace App\SendPulse;

use Generator;

trait Addressbooks
{
    /**
     * Возвращает информацию об адресной книге
     * @param  int $id ID адресной книги
     * @return array
     */
    public function getAddressbook(int $id) :array
    {
        return $this->request('GET', '/addressbooks/' . $id);
    }

    /**
     * Возвращает список адресных книг
     * @param  int|null $limit  Количество записей (необязательный параметр)
     * @param  int|null $offset Смещение выдачи (начиная с какой записи показывать)

     * @return array
     */
    public function getAddressbooks(int $limit = null, int $offset = null) :array
    {
        $params = [];

        if (! empty($limit)) {
            $params['limit'] = $limit;
        }

        if (! empty($offset)) {
            $params['offset'] = $offset;
        }

        return $this->request('GET', '/addressbooks', $params);
    }

    /**
     * Возвращает список всех адресных книг
     * @return \Generator
     */
    public function getAllAddressbooks() :Generator
    {
        return $this->getAll(function ($offset) {
            return $this->getAddressbooks($limit = null, $offset);
        });
    }

    /**
     * Создает адресную книгу
     * @param array $params Параметры адресной книги
     * @return int
     */
    public function addAddressbook(array $params) :int
    {
        $response =  $this->request('POST', '/addressbooks', $params);
        return $response['id'];
    }

    /**
     * Обновляет адресную книгу
     * @param array $id ID адресной книги
     * @param array $params Параметры адресной книги
     * @return array
     */
    public function updateAddressbook(int $id, array $params) :array
    {
        return $this->request('PUT', '/addressbooks/' . $id, $params);
    }

    /**
     * Удаляет адресную книгу
     * @param array $id ID адресной книги
     * @return array
     */
    public function deleteAddressbook(int $id) :array
    {
        return $this->request('DELETE', '/addressbooks/' . $id);
    }

    /**
     * Добавляет email адреса в адресную книгу
     * @param int $id ID адресной книги
     * @param array $emails Парамеры контактов
     * @return array
     */
    public function addAddressbookEmails(int $id, array $emails) :array
    {
        $params = [ 'emails' => $emails ];
        return $this->request('POST', "/addressbooks/{$id}/emails", $params);
    }

    /**
     * Удаляет email адреса из адресной книги
     * @param int $id ID адресной книги
     * @param array $emails Список email адресов
     * @return array
     */
    public function deleteAddressbookEmails(int $id, array $emails) :array
    {
        $params = [ 'emails' => $emails ];
        return $this->request('DELETE', "/addressbooks/{$id}/emails", $params);
    }

    /**
     * Возвращает список email адресов в адресной книге
     * @param int $id ID адресной книги
     * @param  int|null $limit  Количество записей (необязательный параметр)
     * @param  int|null $offset Смещение выдачи (начиная с какой записи показывать)

     * @return array
     */
    public function getAddressbookEmails(int $id, int $limit = null, int $offset = null) :array
    {
        $params = [];

        if (! empty($limit)) {
            $params['limit'] = $limit;
        }

        if (! empty($offset)) {
            $params['offset'] = $offset;
        }

        return $this->request('GET', "/addressbooks/{$id}/emails", $params);
    }

    /**
     * Возвращает список всех email адресов в адресной книге
     * @param  int $id ID адресной книги
     * @return \Generator
     */
    public function getAllAddressbookEmails(int $id) :Generator
    {
        return $this->getAll(function ($offset) use ($id) {
            return $this->getAddressbookEmails($id, $limit = null, $offset);
        });
    }

    /**
     * Возвращает общее количество email адресов в адресной книге
     * @param  int $id ID адресной книги
     * @return int
     */
    public function getAddressbookEmailsTotal(int $id) :int
    {
        $response = $this->request('GET', "/addressbooks/{$id}/emails/total");
        return $response['total'];
    }

    /**
     * Возвращает список переменных для адресной книги
     * @param int $id ID адресной книги
     * @return array
     */
    public function getAddresbookVariables(int $id) :array
    {
        return $this->request('GET', "/addressbooks/{$id}/variables");
    }
}
