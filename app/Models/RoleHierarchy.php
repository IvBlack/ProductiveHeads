<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RoleHierarchy
 *
 * @property int $id
 * @property int $role_id
 * @property int $hierarchy
 * @mixin \Eloquent
 */
class RoleHierarchy extends Model
{
    protected $table = 'role_hierarchy';
    public $timestamps = false;

}
