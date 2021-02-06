<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Menu\MenuRole;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(MenuRole::class, function (Faker $faker) {
    return [
        'role_name' => 'guest',
        'menus_id'  => factory(App\Models\MenuItem::class)->create()->id,
    ];
});
