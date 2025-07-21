<?php

namespace App\Http\Resources\CandidateProfiles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CandidateProfileCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        return $this->resource;
    }
}
