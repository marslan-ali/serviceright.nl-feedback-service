<?php

namespace App\Core\Repositories;

use App\Core\Repositories\Contracts\FeedbackRequestsContract;
use App\Domain\Models\FeedbackRequest;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FeedbackRequestsRepository.
 */
class FeedbackRequestsRepository extends BaseRepository implements FeedbackRequestsContract {

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tags' => 'like',
    ];

    /**
     * @return string
     */
    public function model()
    {
        return FeedbackRequest::class;
    }

    /**
     * @param $id
     * @return FeedbackRequest|null
     * @throws RepositoryException
     */
    public function getOpenFeedbackRequestById($id): ?FeedbackRequest
    {
        /** @var FeedbackRequest|null $feedbackRequest */
        $feedbackRequest = $this->makeModel()->newQuery()->where(function (Builder $builder) use ($id) {
                    $builder->whereNull('completed_on');
                    $builder->whereKey($id);
                })->first();

        return $feedbackRequest;
    }

    /**
     * @param $orderId
     * @param $companyId
     * @param $branch
     * @return FeedbackRequest
     */
    public function createFeedbackRequest($orderId, $companyId, $branch, $tags): FeedbackRequest
    {
        $feedbackRequest = $this->firstOrCreate([
            'order_id'   => $orderId,
            'company_id' => $companyId,
            'department' => $branch,
            'tags'       => $tags
        ]);

        return $feedbackRequest;
    }



}
