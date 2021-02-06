<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu\MenuItem
 *
 * @property int $id
 * @property string $name
 * @property string|null $href
 * @property string|null $icon
 * @property string $slug
 * @property int|null $parent_id
 * @property int $menu_id
 * @property int $sequence
 * @mixin \Eloquent
 */
class MenuItem extends Model
{
    public $timestamps = false;
}
