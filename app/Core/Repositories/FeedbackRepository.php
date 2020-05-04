<?php

namespace App\Core\Repositories;

use App\Core\Repositories\Contracts\FeedbackContract;
use App\Core\Repositories\Criterias\Feedback\AcceptedFeedbackCriteria;
use App\Core\Repositories\Criterias\Feedback\OpenFeedbackCriteria;
use App\Core\Repositories\Criterias\Feedback\PositiveFeedbackCriteria;
use App\Domain\Models\Feedback;
use App\Domain\Models\FeedbackRequest as FBR;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Collection;
use MicroServiceWorld\Core\Infrastructure\Repositories\Criteria\DepartmentCriteria;
use Log;

/**
 * Class FeedbackRepository.
 */
class FeedbackRepository extends BaseRepository implements FeedbackContract
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->pushCriteria(DepartmentCriteria::class);
    }

    /**
     * @return string
     */
    public function model()
    {
        return Feedback::class;
    }

    /**
     * @param FBR $fbr
     * @param $content
     * @param $rating
     * @param null $userAgent
     * @param null $fingerPrint
     * @return Feedback|mixed
     */
    public function createFeedback(FBR $fbr, $content, $rating, $userAgent = null, $fingerPrint = null) : Feedback
    {
        /** @var Feedback $feedback */
        $feedback = $fbr->feedback()->create([
            'department' => $fbr->department,
            'order_id' => $fbr->order_id,
            'company_id' => $fbr->company_id,
            'content' => $content,
            'order_information' => null,
            'rating' => (int) $rating,
            'tags' => $fbr->tags
        ]);

        // update meta information
        $fbr->update([
            'completed_on' => Carbon::now(),
            'finger_print' => $fingerPrint,
            'user_agent' => $userAgent,
            'feedback_id' => $feedback->getKey(),
        ]);

        return $feedback;
    }

    /**
     * @return Collection
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getAllOpenFeedbackItems(): Collection
    {
        return $this
            ->pushCriteria(new OpenFeedbackCriteria())
            ->get();
    }

    /**
     * @return Collection
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getAcceptedFeedbackItems(): Collection
    {
        return $this
            ->pushCriteria(new AcceptedFeedbackCriteria())
            ->get();
    }

    /**
     * @return array
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function aggregateTotalsByDepartment(): array
    {
        $repository = $this->pushCriteria(DepartmentCriteria::class);

        /** @var Collection $result */
        $result = $repository->get();

        return [
            'total' => $result->count(),
            'average' => round($result->avg('rating') ?? 0),
        ];
    }

    /**
     * @param array $tags
     * @param int $minimumItems
     * @return Collection
     */
    public function searchFeedbackByTagsWithAMinimumResultSet(array $tags, int $minimumItems): Collection
    {
        $tags = collect($tags);

        // to store the results in
        $items = collect();

        // loop through the ranges
        // TODO change to OR statement in case the OR statement respects the order of the OR statement and thus gives back the expected result
        foreach (range(0, $minimumItems) as $range)
        {
            $whereBuilder = function($builder) use ($tags) {
                $tags->each(function($value, $key) use ($builder){
                    $builder->where("tags->{$key}", $value);
                });
            };

            // apply the where clause now
            $this->where(function($builder) use ($whereBuilder){
                $whereBuilder($builder);
            });

            // we always want the newest items
            $this->latest();
            $this->limit($minimumItems - $items->count());

            // don't find duplicate values
            $this->whereNotIn('id', $items->pluck('id'));

            // take the result
            $result = $this->get();

            // merge the results
            $items = $items->merge($result);

            // clean the builder
            $this->cleanBuilder();

            // remove the last item from the tags collection
            $tags->pop();

            if($items->count() >= $minimumItems) {
                break; // break out the loop
            }
        }

        // just to be sure we return the correct amount
        return $items->take($minimumItems);
    }


}
