<?php

namespace App\Observers;

use App\Models\ResidentialComplex;
use Illuminate\Support\Facades\Cache;

class ResidentialComplexObserver
{
    /**
     * Handle the ResidentialComplex "created" event.
     */
    public function created(ResidentialComplex $residentialComplex): void
    {
        Cache::forget('residentialComplex');
        Cache::put('residentialComplex', $residentialComplex);
    }

    /**
     * Handle the ResidentialComplex "updated" event.
     */
    public function updated(ResidentialComplex $residentialComplex): void
    {
        //
    }

    /**
     * Handle the ResidentialComplex "deleted" event.
     */
    public function deleted(ResidentialComplex $residentialComplex): void
    {
        //
    }

    /**
     * Handle the ResidentialComplex "restored" event.
     */
    public function restored(ResidentialComplex $residentialComplex): void
    {
        //
    }

    /**
     * Handle the ResidentialComplex "force deleted" event.
     */
    public function forceDeleted(ResidentialComplex $residentialComplex): void
    {
        //
    }
}
