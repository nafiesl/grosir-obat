<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'username'       => $faker->unique()->username,
        'password'       => 'secret',
        'remember_token' => str_random(10),
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name'         => $faker->name,
        'unit_id'      => function() {
            return factory(App\Unit::class)->create()->id;
        },
        'cash_price'   => 2000,
        'credit_price' => 1000,
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Unit::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
