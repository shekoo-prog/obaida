<?php

namespace App\Core;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $hidden = ['password'];
    protected $timestamps = true;

    public function __construct()
    {
        $this->db = Database::getInstance();
        if (!$this->table) {
            // Convert StudlyCase to snake_case for table name
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';
        }
    }

    protected function sanitizeData(array $data)
    {
        // Remove guarded fields
        $data = array_diff_key($data, array_flip($this->guarded));
        
        // Only allow fillable fields if specified
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        // Add timestamps if enabled
        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            if (!isset($data['created_at'])) {
                $data['created_at'] = $now;
            }
            $data['updated_at'] = $now;
        }

        return $data;
    }

    public function create(array $data)
    {
        $data = $this->sanitizeData($data);
        
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        $this->db->query($sql, array_values($data));
        
        return $this->find($this->db->getLastInsertId());
    }

    public function update($id, array $data)
    {
        $data = $this->sanitizeData($data);
        
        if (empty($data)) {
            return false;
        }

        $fields = implode('=?, ', array_keys($data)) . '=?';
        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = ?";
        
        $values = array_values($data);
        $values[] = $id;
        
        return $this->db->query($sql, $values);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->query($sql, [$id]);
    }

    public function query()
    {
        return new QueryBuilder($this->table, $this->db);
    }

    public function where($column, $operator = null, $value = null)
    {
        return $this->query()->where($column, $operator, $value);
    }
    public function whereIn($column, array $values)
    {
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $sql = "SELECT * FROM {$this->table} WHERE {$column} IN ({$placeholders})";
        return $this->db->query($sql, $values)->fetchAll();
    }

    public function first()
    {
        $sql = "SELECT * FROM {$this->table} LIMIT 1";
        return $this->db->query($sql)->fetch();
    }

    public function paginate($perPage = 15, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} LIMIT ? OFFSET ?";
        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        return $this->db->query($sql)->fetch()->count;
    }

    public function exists($id)
    {
        $sql = "SELECT EXISTS(SELECT 1 FROM {$this->table} WHERE {$this->primaryKey} = ?) as exist";
        return (bool) $this->db->query($sql, [$id])->fetch()->exist;
    }

    public function toArray($object)
    {
        $array = (array) $object;
        foreach ($this->hidden as $key) {
            unset($array[$key]);
        }
        return $array;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$column} " . strtoupper($direction);
        return $this->db->query($sql)->fetchAll();
    }

    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    public function commit()
    {
        return $this->db->commit();
    }

    public function rollBack()
    {
        return $this->db->rollBack();
    }

    protected function getTableColumns()
    {
        $sql = "SHOW COLUMNS FROM {$this->table}";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function find($id)
    {
        return $this->db->find($this->table, $id, $this->primaryKey);
    }
}