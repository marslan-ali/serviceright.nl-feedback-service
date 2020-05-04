<?php

namespace App\Events;

use App\Domain\Models\Feedback;

/**
 * Class FeedbackHasBeenCreated.
 */
class FeedbackHasBeenCreated extends Event
{
    /**
     * @var Feedback
     */
    protected $feedback;

    /**
     * FeedbackHasBeenCreated constructor.
     * @param Feedback $feedback
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }
}
