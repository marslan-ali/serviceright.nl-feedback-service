<?php

namespace Tests;

use App\Domain\Models\FeedbackRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class CreateFeedbackTest.
 */
class CreateFeedbackTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_valid_feedback()
    {
        /** @var FeedbackRequest $feedbackRequest */
        $feedbackRequest = factory(FeedbackRequest::class)->create();

        $response = $this->json('POST', '/feedback', [
            'feedback_request_id' => $feedbackRequest->getKey(),
            'rating' => 35,
            'content' => $this->faker->text(200),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => ['id']]);
    }
}
