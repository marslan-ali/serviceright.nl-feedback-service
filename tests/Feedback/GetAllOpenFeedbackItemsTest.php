<?php

namespace Tests\Feedback;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Core\Repositories\Contracts\FeedbackContract;
use App\Domain\Models\Feedback;

/**
 * Class GetAllOpenFeedbackItemsTest.
 */
class GetAllOpenFeedbackItemsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var FeedbackContract|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $contract;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->contract = app(FeedbackContract::class);
    }

    public function test_if_all_feedback_are_accepted_from_controller()
    {
        $feedback = factory(Feedback::class)->state('accepted')->create();
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->getKey(),
        ]);

        /** @var FeedbackContract $feedbackContract */
        $feedbackContract = $this->contract;
        $items = $feedbackContract->getAcceptedFeedbackItems();
        $this->assertNotNull($items->random(1)->first()->accepted);
    }

    public function test_if_all_feedback_are_open_from_controller()
    {
        $feedback = factory(Feedback::class)->state('open')->create();
        $this->assertDatabaseHas('feedback',
                [
            'id' => $feedback->getKey(),
        ]);

        /** @var FeedbackContract $feedbackContract */
        $feedbackContract = app(FeedbackContract::class);
        $items            = $feedbackContract->getAllOpenFeedbackItems();
        $this->assertNull($items->random(1)->first()->accepted);
    }

    public function test_get_open_feedback_items()
    {
        $response = $this->json('GET', '/feedback?status=open', []);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'order_id', 'company_id', 'content', 'rating', 'order_information',
                    'accepted', 'created_at'],
            ],
        ]);
    }

    /**
     * Get all the accepted feedback items.
     */
    public function test_get_accepted_feedback_items()
    {
        $response = $this->json('GET', '/feedback?status=accepted', []);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'order_id', 'company_id', 'content', 'rating', 'order_information',
                    'accepted', 'created_at'],
            ],
        ]);
    }

}
