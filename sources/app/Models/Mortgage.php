<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mortgage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'product_name',
        'original_id',
        'from_year',
        'to_year',
        'from_amount',
        'to_amount',
        'min_rate',
        'max_rate',
        'min_initial_fee',
        'max_initial_fee',
    ];

    public function parentBank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function mortgagePrograms(): BelongsToMany
    {
        return $this->belongsToMany(MortgageProgram::class, 'mortgage_program_pivot', null, 'program_id');
    }

    public function getMonthlyPaymentAttribute()
    {
        return $this->getMonthlyPayment();
    }

    public function getMonthlyPayment(): int
    {
        //Рассчитываем минимальный ежемесячный платеж
        $takenAmount = $this->from_amount;
        $fee = $this->max_initial_fee;
        $year = $this->to_year;
        //Сначала берем первоначальный взнос для минимально возможной суммы по ипотеке с самым маленьким возможным процентом первоначального взноса
        $startPay = $takenAmount * ($fee / 100);
        //Вычитываем оставшуюся сумму которую нужно выплачивать ежемесячно 
        $remainingPay = ($takenAmount - $startPay);
        //Рассчитываем ежемесячный платеж для минимально возможного срока по ипотеке чтобы получилось примерно как здесь https://www.banki.ru/services/calculators/hypothec/
        $monthlyPay = $remainingPay / ($year * 12) + $remainingPay * ($this->min_rate / 12 / 100);
        //Почему-то значения на banki.ru отличаются от всех калькуляторов, даже их собственного

        return $monthlyPay;
    }

    public function availableInCities(): HasMany
    {
        return $this->hasMany(MortgageCity::class, 'mortgage_id');
    }
}
