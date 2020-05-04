<?php

namespace App\Http\Controllers;

use App\Core\Repositories\Contracts\FeedbackContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use MicroServiceWorld\Domain\Models\Department;

/**
 * Class FeedBackAggregate.
 */
class FeedBackAggregate extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FeedbackContract
     */
    protected $feedbackContract;

    /**
     * FeedBackAggregate constructor.
     * @param Request $request
     * @param FeedbackContract $feedbackContract
     */
    public function __construct(Request $request, FeedbackContract $feedbackContract)
    {
        $this->request = $request;
        $this->feedbackContract = $feedbackContract;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        $department = Department::guessByRequestHeader();

        // missing department header
        if (! $department) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => [
                'X-Department' => 'Department header is missing',
            ]], 422);
        }

        $result = Cache::remember('feedback_'.$department->name(), Carbon::now()->addMinutes(5), function () {
            return $this->feedbackContract->aggregateTotalsByDepartment();
        });

        // return the response to the application gateway
        return response()->json($result, 200);
    }
}
