<?php

namespace App\Core;

class QueryBuilder
{
    protected $table;
    protected $db;
    protected $query = [];
    protected $bindings = [];

    public function __construct($table, $db)
    {
        $this->table = $table;
        $this->db = $db;
    }

    public function select($columns = '*')
    {
        $this->query['select'] = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function where($column, $operator = null, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->query['where'][] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function get()
    {
        $sql = $this->buildQuery();
        return $this->db->query($sql, $this->bindings)->fetchAll();
    }

    public function first()
    {
        $sql = $this->buildQuery() . " LIMIT 1";
        return $this->db->query($sql, $this->bindings)->fetch();
    }

    protected function buildQuery()
    {
        $select = $this->query['select'] ?? '*';
        $sql = "SELECT {$select} FROM {$this->table}";
        
        if (!empty($this->query['where'])) {
            $sql .= " WHERE " . implode(' AND ', $this->query['where']);
        }
        
        return $sql;
    }
}