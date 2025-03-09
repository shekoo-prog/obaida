<?php

namespace App\Core\Database;

use App\Core\Database\SensitiveData;

abstract class BaseModel
{
    use SensitiveData;

    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $this->hideProtectedFields($result);
    }

    public function create(array $data)
    {
        $data = $this->filterFillableFields($data);
        if ($this instanceof SensitiveData) {
            $data = $this->encryptSensitiveData($data);
        }

        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        $this->db->query($sql, array_values($data));
        
        return $this->find($this->db->lastInsertId());
    }

    public function update($id, array $data)
    {
        $data = $this->filterFillableFields($data);
        if ($this instanceof SensitiveData) {
            $data = $this->encryptSensitiveData($data);
        }

        $fields = array_map(function($field) {
            return "{$field} = ?";
        }, array_keys($data));
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = ?";
        $values = array_merge(array_values($data), [$id]);
        
        return $this->db->query($sql, $values);
    }

    protected function filterFillableFields($data)
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function hideProtectedFields($data)
    {
        if (!$data) return $data;
        
        foreach ($this->hidden as $field) {
            unset($data->$field);
        }
        return $data;
    }

    public function where($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        $stmt = $this->db->query($sql, [$value]);
        return $this;
    }

    public function first()
    {
        return $this->hideProtectedFields($this->db->query("SELECT * FROM {$this->table} LIMIT 1")->fetch());
    }
}