<?php

$factory->define('App\User', function ($faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->email,
        'password' => $faker->password
    ];
});

