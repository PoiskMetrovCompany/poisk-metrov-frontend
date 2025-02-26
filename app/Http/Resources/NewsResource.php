<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (isset($data['title_image_file_name'])) {
            $data['fullImagePath'] = "{$request->getSchemeAndHttpHost()}/news/{$data['title_image_file_name']}";
        }

        return $data;
    }
}
