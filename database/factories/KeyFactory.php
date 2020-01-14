<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Key;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Key::class, function (Faker $faker) {
    $id = array_rand([0, 1, 2], 1);
    $paid =  array_rand([0, 2], 1);
    return [
        "module_serial" => $faker->uuid,
        "hdd_serial" => $faker->uuid,
        "hardware_id" => $faker->uuid,
        "update_key" => $faker->uuid,
        "tablet_key" => $faker->uuid,
        "tabscreen_key" => $faker->uuid,
        "client_remark" => $faker->uuid,
        "admin_remark" => $faker->uuid,
        "user_id" => $id + 2,
        "paid" => $paid
    ];
});