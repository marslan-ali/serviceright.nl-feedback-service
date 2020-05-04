<?php

namespace App\Core\Repositories\Criterias\Feedback;

use Illuminate\Database\Eloquent\Builder;
use MicroServiceWorld\Core\Infrastructure\Repositories\Contracts\CriteriaInterface;

/**
 * Retrieve all the feed backs that are still open to be handled.
 *
 * Class OpenFeedbackCriteria
 */
class OpenFeedbackCriteria implements CriteriaInterface
{
    /**
     * @param Builder $builder
     * @return void
     */
    public function apply($builder)
    {
        $builder->whereNull('accepted');
    }
}
