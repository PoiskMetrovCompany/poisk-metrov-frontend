<?php

namespace App\Yandex;

use Illuminate\Support\Collection;

class Availability
{
    public $weekdays;
    public $weekend;
    public bool $everyday;
    public bool $sunday;
    public bool $monday;
    public bool $tuesday;
    public bool $wednesday;
    public bool $thursday;
    public bool $friday;
    public bool $saturday;
    public bool $twentyFourHours;
    public Collection $intervals;

    public function __construct($availability) {
        if (property_exists($availability, 'Weekdays')) {
            $this->weekdays = $availability->Weekdays;
        }

        if (property_exists($availability, 'Weekend')) {
            $this->weekend = $availability->Weekend;
        }

        if (property_exists($availability, 'Everyday')) {
            $this->everyday = $availability->Everyday;
        }

        if (property_exists($availability, 'Sunday')) {
            $this->sunday = $availability->Sunday;
        }

        if (property_exists($availability, 'Monday')) {
            $this->monday = $availability->Monday;
        }

        if (property_exists($availability, 'Tuesday')) {
            $this->tuesday = $availability->Tuesday;
        }

        if (property_exists($availability, 'Wednesday')) {
            $this->wednesday = $availability->Wednesday;
        }

        if (property_exists($availability, 'Thursday')) {
            $this->thursday = $availability->Thursday;
        }

        if (property_exists($availability, 'Friday')) {
            $this->friday = $availability->Friday;
        }

        if (property_exists($availability, 'Saturday')) {
            $this->saturday = $availability->Saturday;
        }

        if (property_exists($availability, 'TwentyFourHours')) {
            $this->twentyFourHours = $availability->TwentyFourHours;
        }

        if (property_exists($availability, 'Intervals')) {
            $this->intervals = collect([]);
            
            foreach ($availability->Intervals as $interval) {
                $this->intervals->push(new Interval($interval));
            }
        }
    }
}