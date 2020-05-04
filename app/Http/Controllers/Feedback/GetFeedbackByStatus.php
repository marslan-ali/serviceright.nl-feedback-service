<?php

namespace App\Http\Controllers\Feedback;

use App\Core\Repositories\Contracts\FeedbackContract;
use App\Http\Controllers\Controller;
use App\Http\Resources\Feedback;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class IndexFeedback.
 */
class GetFeedbackByStatus extends Controller
{
    /**
     * @var FeedbackContract
     */
    protected $contract;

    /**
     * IndexFeedback constructor.
     * @param FeedbackContract $contract
     */
    public function __construct(FeedbackContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'status' => [
                'required', Rule::in(['accepted', 'open']),
            ],
        ]);

        // the status
        switch ($request->input('status')) {
            case 'open':
                $feedback = $this->contract->getAllOpenFeedbackItems();
                break;
            default:
                $feedback = $this->contract->getAcceptedFeedbackItems();
                break;
        }

        return Feedback::collection($feedback);
    }
}
