<?php

namespace App\Http\Controllers\Feedback;

use App\Core\Repositories\Contracts\FeedbackContract;
use App\Core\Repositories\Contracts\FeedbackRequestsContract;
use App\Domain\Models\Feedback;
use App\Http\Controllers\Controller;
use App\Http\Resources\Feedback as FeedbackResponse;
use Illuminate\Http\Request;

/**
 * Class CreateFeedback.
 */
class CreateFeedback extends Controller
{
    /**
     * @var FeedbackContract
     */
    protected $contract;

    /**
     * @var FeedbackRequestsContract
     */
    protected $requestContract;

    /**
     * CreateFeedback constructor.
     * @param FeedbackContract $contract
     * @param FeedbackRequestsContract $requestsContract
     */
    public function __construct(FeedbackContract $contract, FeedbackRequestsContract $requestsContract)
    {
        $this->contract = $contract;
        $this->requestContract = $requestsContract;
    }

    /**
     * @param Request $request
     * @return FeedbackResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'feedback_request_id' => ['required', 'exists:feedback_requests,id'],
            'rating' => ['required', function ($attribute, $value, $fail) {
                $value = (int) $value;
                if ($value > 50 || $value < 0) {
                    $fail($attribute.' INVALID_RATING_VALUE');
                }
            }],
            'content' => ['required'],
        ]);

        // query the feedback request
        $feedbackRequest = $this->requestContract->getOpenFeedbackRequestById(
            $request->input('feedback_request_id')
        );

        if (! $feedbackRequest) {
            aort(404);
        }

        // create the feedback
        /** @var Feedback $feedback */
        $feedback = $this->contract->createFeedback($feedbackRequest,
            $request->input('content'),
            $request->input('rating'),
            $request->userAgent(),
            $request->fingerprint()
        );

        // return the feedback response
        return new FeedbackResponse($feedback);
    }
}
