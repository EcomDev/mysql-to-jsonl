<?php

namespace EcomDev\MySQL2JSONL;

use PDO;

readonly class MySQLConfiguration
{
    public function __construct(
        public string $database,
        public string $host = 'localhost',
        public string $unixSocket = '',
        public int    $port = 3306,
        public string $user = 'root',
        public string $password = '',
        public string $charset = 'utf8mb4',
        public ?string $key = null
    ) {
    }

    public static function fromUnixSocket(
        string $socket,
        string $database,
    ): self {
        return new self(database: $database, unixSocket: $socket);
    }

    public static function fromHost(string $host, string $database): self
    {
        return new self(database: $database, host: $host);
    }

    public function generateDSN(): string
    {
        if ($this->unixSocket) {
            return sprintf(
                'mysql:unix_socket=%s;dbname=%s;charset=%s',
                $this->unixSocket,
                $this->database,
                $this->charset
            );
        }

        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->charset
        );
    }

    public function withPort(int $port): self
    {
        return new self(
            $this->database,
            $this->host,
            $this->unixSocket,
            $port,
            $this->user,
            $this->password,
            $this->charset,
            $this->key
        );
    }

    public function withCredentials(string $user, string $password): self
    {
        return new self(
            $this->database,
            $this->host,
            $this->unixSocket,
            $this->port,
            $user,
            $password,
            $this->charset,
            $this->key
        );
    }

    public function generateDriverOptions()
    {
        return [
            PDO::MYSQL_ATTR_INIT_COMMAND =>
                "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO',FOREIGN_KEY_CHECKS=0,NAMES $this->charset",
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
        ] + ($this->key ? [PDO::MYSQL_ATTR_SERVER_PUBLIC_KEY => $this->key] : []);
    }

    public function withCharset(string $charset): self
    {
        return new self(
            $this->database,
            $this->host,
            $this->unixSocket,
            $this->port,
            $this->user,
            $this->password,
            $charset,
            $this->key
        );
    }

    public function withServerKey(string $key): self
    {
        return new self(
            $this->database,
            $this->host,
            $this->unixSocket,
            $this->port,
            $this->user,
            $this->password,
            $this->charset,
            $key
        );
    }
}
