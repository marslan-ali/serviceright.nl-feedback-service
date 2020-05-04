<?php

namespace  App\Http\Controllers\FeedbackRequest;

use App\Core\Repositories\Contracts\FeedbackRequestsContract;
use App\Domain\Models\FeedbackRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackRequest as FeedbackRequestResponse;

/**
 * Class ShowFeedbackRequest.
 */
class ShowFeedbackRequest extends Controller
{
    /**
     * @var FeedbackRequestsContract
     */
    protected $contract;

    /**
     * ShowFeedbackRequest constructor.
     * @param FeedbackRequestsContract $contract
     */
    public function __construct(FeedbackRequestsContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * @param $requestId
     * @return FeedbackRequestResponse
     */
    public function __invoke($requestId)
    {
        $feedbackRequest = $this->contract->getOpenFeedbackRequestById($requestId);
        if (is_null($feedbackRequest)) { // feedback request
            abort(404);
        }

        return new FeedbackRequestResponse($feedbackRequest);
    }
}
