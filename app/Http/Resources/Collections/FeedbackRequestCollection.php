<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\FeedbackRequest;

/**
 * Class FeedbackRequest.
 */
class FeedbackRequestCollection extends ResourceCollection
{
    /**
     * @var string
     */
    public $collects = FeedbackRequest::class;

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
