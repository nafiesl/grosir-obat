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
        'name'    => $faker->name,
        'unit_id' => function () {
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

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Transaction::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'invoice_no' => str_random(5),
        'items'      => [],
        'customer'   => ['name' => $faker->name, 'phone' => $faker->phoneNumber],
        'payment'    => 1000,
        'total'      => 1000,
    ];
});
