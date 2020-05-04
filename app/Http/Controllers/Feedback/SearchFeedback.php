<?php

namespace App\Http\Controllers\Feedback;

use App\Core\Repositories\Contracts\FeedbackContract;
use App\Core\Repositories\Criterias\Feedback\AcceptedFeedbackCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\SearchFeedbackRequest;
use App\Http\Resources\Collections\FeedbackCollection;
use App\Http\Resources\Feedback;
use Carbon\Carbon;
use Illuminate\Cache\Repository;
use MicroServiceWorld\Domain\Models\Department;

/**
 * Class SearchRequest.
 */
class SearchFeedback extends Controller {

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var FeedbackContract
     */
    protected $contract;

    /**
     * TTL in minutes
     * @var int
     */
    protected $ttl = 5;

    /**
     * SearchFeedback constructor.
     * @param FeedbackContract $contract
     * @param Repository $cache
     */
    public function __construct(FeedbackContract $contract, Repository $cache)
    {
        $this->contract = $contract;
        $this->cache = $cache;

        // scope the contract to the accepted feedback items
        $this->contract->pushCriteria(new AcceptedFeedbackCriteria());
    }

    /**
     * @param SearchFeedbackRequest $request
     * @return Feedback
     */
    public function __invoke(SearchFeedbackRequest $request)
    {
        // retrieve the minimum count
        $minimumResultCount = $request->input('minimum', 3);

        // caching
        $ttl = Carbon::now()->addMinutes($this->ttl);
        $key = $key = 'feedback_search::'.$minimumResultCount.'::'.Department::guessByRequestHeader()->name();

        if($request->has('tags')) {
            $tags = json_decode($request->input('tags'), true);
            $key = md5($key.'::'.http_build_query(collect($tags)->sortKeys()->toArray()));

            $feedbackItems = $this->cache->remember($key, $ttl, function() use ($minimumResultCount, $tags){
                return  $this->contract->searchFeedbackByTagsWithAMinimumResultSet($tags, $minimumResultCount);
            });
        } else {
            $feedbackItems = $this->cache->remember($key, $ttl, function () use ($minimumResultCount){
               return $this->contract->limit($minimumResultCount)->latest()->get();
            });
        }

        return (new FeedbackCollection($feedbackItems));
    }

}
