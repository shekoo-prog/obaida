<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    private $encryptionKey;

    private function __construct()
    {
        $this->encryptionKey = $_ENV['APP_KEY'] ?? 'your-secure-key';
        try {
            $this->connection = new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        } catch (PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception("Query failed: " . $e->getMessage());
        }
    }

    public function encryptData($data)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decryptData($data)
    {
        $data = base64_decode($data);
        $ivlen = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $ivlen);
        $encrypted = substr($data, $ivlen);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}