<?php

namespace Tests;

use App\Domain\Models\FeedbackRequest;
use Tests\TestCase;
/**
 * Class AggregateCountFeedbackTest.
 */
class AggregateCountFeedbackTest extends TestCase
{
//    use DatabaseTransactions;

    public function test_count_all_feedback_in_system()
    {
        $departmentName = $this->faker->name;

        $feedbacks = factory(\App\Domain\Models\Feedback::class, 20)->create([
            'department' => $departmentName,
        ]);

        $response = $this->json('GET', '/feedback/statistics/aggregate', [], [
            'X-Department' => $departmentName,
        ]);
        $response->assertJsonStructure(['total', 'average']);
        $response->assertStatus(200);
    }

    public function test_count_all_feedback_return_valid()
    {
        $departmentName = $this->faker->name;

        factory(\App\Domain\Models\Feedback::class, 20)->create();

        $response = $this->json('GET', '/feedback/statistics/aggregate', [], [
            'X-Department' => $departmentName,
        ]);

        $response->assertJsonStructure(['total', 'average']);
        $response->assertStatus(200);
    }
}
