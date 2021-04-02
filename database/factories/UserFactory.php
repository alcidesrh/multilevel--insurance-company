<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(User::class, function (Faker $faker, $param) {
    return [
        'name' => $faker->name,
        'email' => $param['email'] ?? $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'remember_token' => Str::random(10),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'role_id' => $param['role_id'],
        'birthday' => $faker->dateTime,
        'last_name' => $faker->lastName,
        'company_id' => $param['company_id'] ?? null,
        'parent_id' => $param['parent_id'] ?? null,
    ];
});
