<?php

namespace App\Core\Repositories\Criterias\Feedback;

use Illuminate\Database\Eloquent\Builder;
use MicroServiceWorld\Core\Infrastructure\Repositories\Contracts\CriteriaInterface;

/**
 * Retrieve all the feed backs that are still open to be handled.
 *
 * Class AcceptedFeedbackCriteria
 */
class AcceptedFeedbackCriteria implements CriteriaInterface
{
    /**
     * @param Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->whereNotNull('accepted');
    }
}
