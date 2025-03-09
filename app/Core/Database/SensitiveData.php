<?php

namespace App\Core\Database;

trait SensitiveData
{
    protected $sensitiveFields = [];

    protected function encryptSensitiveData($data)
    {
        $db = Database::getInstance();
        foreach ($this->sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $db->encryptData($data[$field]);
            }
        }
        return $data;
    }

    protected function decryptSensitiveData($data)
    {
        $db = Database::getInstance();
        foreach ($this->sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $db->decryptData($data[$field]);
            }
        }
        return $data;
    }
}