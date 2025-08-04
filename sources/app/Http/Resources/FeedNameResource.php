<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedNameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'feed_name' => $this->feed_name,
            'site_name' => $this->site_name,
            'create_new' => $this->create_new,
            'pair_found' => $this->pair_found
        ];
    }
}
