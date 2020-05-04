<?php

namespace App\Core\Repositories\Contracts;

use App\Domain\Models\Feedback;
use App\Domain\Models\FeedbackRequest as FBR;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FeedbackContract.
 */
interface FeedbackContract extends RepositoryInterface
{
    /**
     * @param FBR $feedbackRequest
     * @param $content
     * @param $rating
     * @param null $userAgent
     * @param null $fingerPrint
     * @return Feedback
     */
    public function createFeedback(FBR $feedbackRequest, $content, $rating, $userAgent = null, $fingerPrint = null) : Feedback;

    /**
     * Get all open feedback items.
     *
     * @return Collection
     */
    public function getAllOpenFeedbackItems() : Collection;

    /**
     * Get all accepted feedback items.
     *
     * @return Collection
     */
    public function getAcceptedFeedbackItems() : Collection;

    /**
     * @return array
     */
    public function aggregateTotalsByDepartment() : array;

    /**
     * @param array $tags
     * @param int $minimumItems
     * @return Collection
     */
    public function searchFeedbackByTagsWithAMinimumResultSet(array $tags, int $minimumItems) : Collection;
}
