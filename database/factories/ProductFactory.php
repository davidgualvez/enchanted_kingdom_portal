<?php

use Faker\Generator as Faker;

$factory->define(App\Part::class, function (Faker $faker) {
    return [
        //
        'branch_id'     => 1,
        'name'          => $faker->name,
        'description'   => $faker->text($maxNbChars = 200),
        'srp'           => rand(1,1000),
        'image'         => 'https://source.unsplash.com/random/150x150',
        'category_id'   => rand(1,5),
    ];
});
