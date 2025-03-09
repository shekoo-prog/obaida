<?php

namespace App\Models;

use App\Core\Database\BaseModel;

class User extends BaseModel
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public function roles()
    {
        $sql = "SELECT r.* FROM roles r 
                JOIN user_roles ur ON r.id = ur.role_id 
                WHERE ur.user_id = ?";
        return $this->db->query($sql, [$this->{$this->primaryKey}])->fetchAll();
    }

    public function hasRole($roleName)
    {
        $sql = "SELECT COUNT(*) as count FROM roles r 
                JOIN user_roles ur ON r.id = ur.role_id 
                WHERE ur.user_id = ? AND r.name = ?";
        $result = $this->db->query($sql, [$this->{$this->primaryKey}, $roleName])->fetch();
        return $result->count > 0;
    }

    public function hasPermission($permissionName)
    {
        $sql = "SELECT COUNT(*) as count FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                JOIN user_roles ur ON rp.role_id = ur.role_id 
                WHERE ur.user_id = ? AND p.name = ?";
        $result = $this->db->query($sql, [$this->{$this->primaryKey}, $permissionName])->fetch();
        return $result->count > 0;
    }

    public function assignRole($roleId)
    {
        $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
        return $this->db->query($sql, [$this->{$this->primaryKey}, $roleId]);
    }

    public function removeRole($roleId)
    {
        $sql = "DELETE FROM user_roles WHERE user_id = ? AND role_id = ?";
        return $this->db->query($sql, [$this->{$this->primaryKey}, $roleId]);
    }
}