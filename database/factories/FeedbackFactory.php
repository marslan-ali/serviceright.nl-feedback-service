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

use App\Domain\Models\Feedback;
use Carbon\Carbon;
use Faker\Generator;

$factory->define(Feedback::class, function (Generator $faker) {

    $tag1 = json_encode(['brand' => 'volkswagen', 'model' => 'polo', 'construction_year' => '2012']);
    $tag2 = json_encode(['brand' => 'mercedes', 'model' => 'C-class']);
    $tag3 = json_encode(['brand' => 'audio']);

    return [
        'order_id' => $faker->numberBetween(),
        'company_id' => $faker->numberBetween(),
        'department' => $faker->randomElement(['vehicles', 'couriers', 'multimedia']),
        'content' => $faker->paragraph(10),
        'rating' => $faker->numberBetween(0, 50),
        'tags' => $faker->randomElement([$tag1, $tag2, $tag3])
    ];
});

$factory->state(Feedback::class, 'open', function (Generator $faker) {
    return [
        'accepted' => null,
    ];
});

$factory->state(Feedback::class, 'accepted', function (Generator $faker) {
    return [
        'accepted' => Carbon::now(),
    ];
});
