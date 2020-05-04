<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FeedbackRequest.
 */
class FeedbackRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => (int) $this->order_id,
            'company_id' => (int) $this->company_id,
            'department' => $this->department,
            'tags'=>$this->tags
        ];
    }
}
