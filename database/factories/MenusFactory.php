<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Menu\MenuItem;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(MenuItem::class, function (Faker $faker) {
    return [
        'name'          => $faker->sentence(4,true),
        'href'          => '/href',
        'icon'          => NULL,
        'slug'          => 'link',
        'parent_id'     => NULL,
        'menu_id'       => 1,
        'sequence'      => 1
    ];
});
