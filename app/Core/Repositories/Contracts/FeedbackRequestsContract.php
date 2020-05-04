<?php

namespace App\Core\Repositories\Contracts;

use App\Domain\Models\FeedbackRequest;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FeedbackRequestsContract.
 */
interface FeedbackRequestsContract extends RepositoryInterface {

    /**
     * @param $id
     * @return FeedbackRequest|null
     */
    public function getOpenFeedbackRequestById($id): ? FeedbackRequest;

    /**
     * Create the feedback request.
     *
     * @param $orderId
     * @param $companyId
     * @param $branch
     * @return FeedbackRequest
     */
    public function createFeedbackRequest($orderId, $companyId, $branch, $tags): FeedbackRequest;
    

}
