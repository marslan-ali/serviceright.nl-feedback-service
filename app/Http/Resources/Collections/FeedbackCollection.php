<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\Feedback;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class FeedbackCollection
 * @package App\Http\Resources\Collections
 */
class FeedbackCollection extends ResourceCollection
{
    /**
     * @var string
     */
    public $collects = Feedback::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
