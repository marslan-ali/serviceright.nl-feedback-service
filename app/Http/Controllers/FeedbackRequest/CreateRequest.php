<?php

namespace App\Http\Controllers\FeedbackRequest;

use App\Core\Repositories\Contracts\FeedbackRequestsContract;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackRequest;
use App\Http\Requests\CreateFeedbackRequest;
use Illuminate\Http\Request;

/**
 * Class CreateRequest.
 */
class CreateRequest extends Controller {

    /**
     * @var FeedbackRequestsContract
     */
    protected $contract;

    /**
     * CreateRequest constructor.
     * @param FeedbackRequestsContract $contract
     */
    public function __construct(FeedbackRequestsContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * @param CreateFeedbackRequest $request
     * @return FeedbackRequest
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(CreateFeedbackRequest $request)
    {
        /** @var \App\Domain\Models\FeedbackRequest $feedbackRequest */
        $feedbackRequest = $this->contract->createFeedbackRequest(
                $request->input('order_id'), $request->input('company_id'),
                $request->input('department'), $request->input('tags')
        );

        return new FeedbackRequest($feedbackRequest);
    }

}
