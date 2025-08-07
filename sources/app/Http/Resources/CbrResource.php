<?php

namespace App\Http\Resources;

use App\Core\Abstracts\AbstractResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbrResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            parent::toArray($request)
        ];
    }
}
