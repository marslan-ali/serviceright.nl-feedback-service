<?php

namespace App\Core\Repositories\Criterias\Feedback;

use Illuminate\Database\Eloquent\Builder;
use MicroServiceWorld\Core\Infrastructure\Repositories\Contracts\CriteriaInterface;

/**
 * Retrieve all the feed backs that are positive in the system.
 *
 * Class PositiveFeedbackCriteria
 */
class PositiveFeedbackCriteria implements CriteriaInterface
{
    /**
     * @param Builder $builder
     * @return void
     */
    public function apply($builder)
    {
        $builder->where('rating', '>=', 30);
    }
}
