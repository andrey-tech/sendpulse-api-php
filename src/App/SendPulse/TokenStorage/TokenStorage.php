<?php

/**
 * Класс TokenStorage. Реализует хранение токенов в файлах
 *
 * @author    andrey-tech
 * @copyright 2020-2021 andrey-tech
 * @see https://github.com/andrey-tech/sendpulse-api-php
 * @license   MIT
 *
 * @version 1.1.0
 *
 * v1.0.0 (21.07.2020) Начальный релиз
 * v1.1.0 (07.02.2021) Изменение имени класса на TokenStorage. Добавлено свойство $mkdirMode
 *
 */

declare(strict_types=1);

namespace App\SendPulse\TokenStorage;

class TokenStorage implements TokenStorageInterface
{
    /**
     * Устанавливает режим доступа для создаваемых каталогов для хранения токенов
     * @var int
     */
    public $mkdirMode = 0755;

    /**
     * Каталог для хранения файлов с токенами
     * @var string
     */
    private $storageFolder;

    /**
     * Конструктор
     * @param string $storageFolder Каталог для хранения файлов с токенами
     */
    public function __construct(string $storageFolder = 'tokens/')
    {
        $this->storageFolder = $storageFolder;
    }

    /**
     * Сохраняет токен в файл
     * @param  string  $token Токен для сохранения
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return void
     * @throws TokenStorageException
     */
    public function save(string $token, string $clientId, string $clientSecret)
    {
        $tokenFile = $this->getTokenFileName($clientId, $clientSecret);
        if (! @file_put_contents($tokenFile, $token, LOCK_EX)) {
            throw new TokenStorageException("Не удалось записать в файл токенов '{$tokenFile}'");
        }
    }

    /**
     * Загружает токен из файла
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return string|null
     * @throws TokenStorageException
     */
    public function load(string $clientId, string $clientSecret)
    {
        $tokenFile =  $this->getTokenFileName($clientId, $clientSecret);
        if (! is_file($tokenFile)) {
            return null;
        }

        $fh = @fopen($tokenFile, 'rb');
        if ($fh === false) {
            throw new TokenStorageException("Не удалось открыть файл токена '{$tokenFile}'");
        }

        if (! flock($fh, LOCK_SH)) {
            throw new TokenStorageException("Не удалось получить разделяемую блокировку файла токена '{$tokenFile}'");
        }

        // Загружаем содержимое файла с токеном
        $token = @file_get_contents($tokenFile);
        if ($token === false) {
            throw new TokenStorageException("Не удалось прочитать файл токена '{$tokenFile}'");
        }

        if (! flock($fh, LOCK_UN)) {
            throw new TokenStorageException("Не удалось разблокировать файл токена '{$tokenFile}'");
        }

        if (! fclose($fh)) {
            throw new TokenStorageException("Не удалось закрыть файл токена '{$tokenFile}'");
        }

        return $token;
    }

    /**
     * Проверяет существуют ли токен для заданного ID клиента и секрета клиента
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return bool
     * @throws TokenStorageException
     */
    public function hasToken(string $clientId, string $clientSecret): bool
    {
        $tokenFile =  $this->getTokenFileName($clientId, $clientSecret);
        return is_file($tokenFile);
    }

    /**
     * Возвращает абсолютное имя файла с токеном
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return string
     * @throws TokenStorageException
     */
    protected function getTokenFileName(string $clientId, string $clientSecret): string
    {
        $storageFolder = __DIR__ . DIRECTORY_SEPARATOR . $this->storageFolder;
        if (! is_dir($storageFolder)) {
            if (!mkdir($storageFolder, $this->mkdirMode, $recursive = true) && !is_dir($storageFolder)) {
                throw new TokenStorageException(
                    "Не удалось рекурсивно создать каталог файлов токенов '{$storageFolder}'"
                );
            }
        }

        $storageFolder = realpath($storageFolder);
        $clientHash = $this->getClientHash($clientId, $clientSecret);
        $tokenFile =  $storageFolder . DIRECTORY_SEPARATOR .  $clientHash . '.txt';

        return $tokenFile;
    }

    /**
     * Возвращает хэш клиента по ID клиента и секрету клиента
     * @param string $clientId ID клиента
     * @param string $clientSecret Секрет клиента
     * @return string
     */
    protected function getClientHash(string $clientId, string $clientSecret): string
    {
        return md5($clientId . ':' . $clientSecret);
    }
}
