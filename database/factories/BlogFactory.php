<?php


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog;
use Faker\Generator as Faker;

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

$factory->define(Blog::class, function (Faker $faker) {
    $now = \Carbon\Carbon::now();
    return [
        'title' => $faker->title,
        'content' => $faker->text,
        'user_id' => 0,
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
