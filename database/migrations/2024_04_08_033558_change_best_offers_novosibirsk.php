<?php

use App\Models\BestOffer;
use App\Models\ResidentialComplex;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private $oldConditions = ['complex_code' => 'tajmskver', 'location_code' => 'novosibirsk'];
    private $newConditions = ['complex_code' => 'tajmparkapartamenty', 'location_code' => 'novosibirsk'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (BestOffer::where($this->oldConditions)->exists()) {
            BestOffer::where($this->oldConditions)->delete();
        }

        if (! BestOffer::where($this->newConditions)->exists()) {
            BestOffer::create($this->newConditions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (BestOffer::where($this->newConditions)->exists()) {
            BestOffer::where($this->newConditions)->delete();
        }

        if (! BestOffer::where($this->oldConditions)->exists()) {
            BestOffer::create($this->oldConditions);
        }
    }
};
