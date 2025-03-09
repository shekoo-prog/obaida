<?php

namespace App\Models;

use App\Core\Database\BaseModel;

class Permission extends BaseModel
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'description'];
}