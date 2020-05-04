<?php

namespace Tests;

use App\Domain\Models\FeedbackRequest;
use Tests\TestCase;
use Illuminate\Support\Str;

/**
 * Create the feedback request endpoint of the system.
 *
 * Class CreateFeedbackRequestEndpoint
 */
class CreateFeedbackRequestEndpointTest extends TestCase
{
//    use DatabaseTransactions;

    /**
     * On return 200 we return an exisiting item.
     */
    public function test_fetch_existing_feedback_from_system()
    {
        $feedbackRequest = factory(FeedbackRequest::class)->create();

        $response = $this->json('POST', '/feedback-request', [
            'order_id' => $feedbackRequest->order_id,
            'company_id' => $feedbackRequest->company_id,
            'department' => $feedbackRequest->department,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data'=>['id']]);
    }

    public function test_create_feedback_missing_department()
    {
        $response = $this->json('POST', '/feedback-request', [
            'order_id' => $this->faker->randomDigit,
            'company_id' => $this->faker->randomDigit,
        ]);
        $response->assertStatus(422);
    }

    public function test_create_feedback_failed_with_validation_errors()
    {
        $response = $this->json('POST', '/feedback-request');
        $response->assertStatus(422);
    }

    /**
     * Test the creation of a feedback request.
     */
    public function test_create_feedback_request()
    {
        $response = $this->json('POST', '/feedback-request', [
            'order_id' => $this->faker->randomDigit,
            'company_id' => $this->faker->randomDigit,
            'department' => $this->faker->randomElement(['vehicles', 'couriers', 'multimedia']),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => ['id']]);
    }

    /**
     * Test the valid request information request.
     */
    public function test_show_feedback_request_information()
    {
        $feedbackRequest = factory(FeedbackRequest::class)->create();

        $response = $this->json('GET', '/feedback-request/'.$feedbackRequest->getKey());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'department', 'order_id', 'company_id',
            ],
        ]);
    }

    /**
     * Test the invalid response code.
     */
    public function test_show_invalid_request_information()
    {
        $randomUuid = (string) Str::uuid();
        $response = $this->json('GET', '/feedback-request/'.$randomUuid);
        $response->assertStatus(404);
    }

    /**
     * Not found within the system because the user already provided this review.
     */
    public function test_not_found_already_done_feedback_request()
    {
        /** @var FeedbackRequest $feedbackRequest */
        $feedbackRequest = factory(FeedbackRequest::class)->state('expired')->create();
        $response = $this->json('GET', '/feedback-request/'.$feedbackRequest->getKey());
        $response->assertStatus(404);
    }
}
