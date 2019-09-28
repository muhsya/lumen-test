<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Models\Template::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->sentence,
        'name' => $faker->sentence,
    ];
});

$factory->define(App\Models\Checklist::class, function (Faker\Generator $faker) {
    return [
        'object_domain' => $faker->sentence,
		'object_id' => "1",
		'description' => $faker->paragraph,
		'is_completed' => false,
		'due' => $faker->dateTime,
		'task_id' => 123,
		'urgency' => 2,
		'completed_at' => $faker->dateTime,
		'updated_by' => $faker->name
    ];
});

$factory->define(App\Models\Item::class, function (Faker\Generator $faker) {
    return [
		'description' => $faker->paragraph,
		'is_completed' => false,
		'due' => $faker->dateTime,
		'task_id' => 123,
		'urgency' => 2,
		'completed_at' => $faker->dateTime,
		'created_by' => $faker->name,
		'updated_by' => $faker->name,
    ];
});
