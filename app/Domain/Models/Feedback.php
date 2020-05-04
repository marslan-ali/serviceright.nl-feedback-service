<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Feedback.
 */
class Feedback extends Model implements Auditable
{
    // use the soft deletes
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     *
     */
    const MINIMUM_SEARCH_COUNT = 3;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $casts = ['order_information' => 'json','tags' => 'json'];

    /**
     * Date of the review.
     * @var array
     */
    protected $dates = ['date'];

    /**
     * @return HasMany
     */
    public function feedbackRequests() : HasMany
    {
        return $this->hasMany(FeedbackRequest::class);
    }
}
