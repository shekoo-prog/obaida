<?php

namespace App\Models;

use App\Core\Database\BaseModel;

class Role extends BaseModel
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        $sql = "SELECT p.* FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                WHERE rp.role_id = ?";
        return $this->db->query($sql, [$this->{$this->primaryKey}])->fetchAll();
    }

    public function assignPermission($permissionId)
    {
        $sql = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
        return $this->db->query($sql, [$this->{$this->primaryKey}, $permissionId]);
    }

    public function removePermission($permissionId)
    {
        $sql = "DELETE FROM role_permissions WHERE role_id = ? AND permission_id = ?";
        return $this->db->query($sql, [$this->{$this->primaryKey}, $permissionId]);
    }
}