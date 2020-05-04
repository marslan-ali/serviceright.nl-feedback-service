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

use App\Domain\Models\FeedbackRequest;
use Carbon\Carbon;
use Faker\Generator;

$factory->define(FeedbackRequest::class, function (Generator $faker) {
    return [
        'order_id' => $faker->numberBetween(),
        'company_id' => $faker->numberBetween(),
        'department' => $faker->randomElement(['vehicles', 'couriers', 'multimedia']),
    ];
});

$factory->state(FeedbackRequest::class, 'expired', function (Generator $faker) {
    return [
        'completed_on' => Carbon::now(),
        'finger_print' => $faker->sha256,
        'user_agent' => $faker->userAgent,
        'ip' => $faker->ipv4,
    ];
});
