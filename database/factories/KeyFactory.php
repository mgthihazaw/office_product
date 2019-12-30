<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Key;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Key::class, function (Faker $faker) {
    return [
        "module_serial" => $faker->uuid,
        "hdd_serial" => $faker->uuid,
        "hardware_id" => $faker->uuid,
        "update_key" => $faker->uuid,
        "tablet_key" => $faker->uuid,
        "tabscreen_key" => $faker->uuid,
        "client_remark" => $faker->uuid,
        "admin_remark" => $faker->uuid,
    ];
});