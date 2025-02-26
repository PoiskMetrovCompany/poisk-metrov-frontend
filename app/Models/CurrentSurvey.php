<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class CurrentSurvey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_id',
        'current_step',
        'date',
        'approximate_name',
        'approximate_builder',
        'approximate_construction',
        'agent_fio',
        'client',
        'is_first',
        'construction',
        'builder',
        'address',
        'is_lead',
        'price',
        'builder_percent',
        'commission',
        'place',
        'awaiting_confirmation',
        'confirmed',
        'document'
    ];

    public array $fieldsForDisplays = [
        'Дата' => 'date',
        'ФИО агента' => 'approximate_name',
        'ФИО клиента' => 'client',
        'Первичка/вторичка' => 'is_first',
        'Стройка' => 'approximate_construction',
        'Застройщик' => 'approximate_builder',
        'Адрес' => 'address',
        'Лид/рекомендация' => 'is_lead',
        'Стоимость' => 'price',
        'Процент от сделки' => 'builder_percent',
        'Фикс. комиссия' => 'commission',
        'Куда отправлять' => 'place',
        'Документ' => 'document',
    ];

    public function getSummary(): Collection
    {
        $summary = new Collection();

        $this->addFieldToSummary('Дата', 'date', $summary);
        $this->addFieldToSummary('ФИО агента', 'agent_fio', $summary);
        $this->addFieldToSummary('ФИО клиента', 'client', $summary);
        $this->addFieldToSummary('Первичка/вторичка', 'is_first', $summary);
        $this->addFieldToSummary('Стройка', 'construction', $summary);
        $this->addFieldToSummary('Застройщик', 'builder', $summary);
        $this->addFieldToSummary('Адрес', 'address', $summary);
        $this->addFieldToSummary('Лид/рекомендация', 'is_lead', $summary);
        $this->addFieldToSummary('Стоимость', 'price', $summary);
        $this->addFieldToSummary('Процент от сделки', 'builder_percent', $summary);
        $this->addFieldToSummary('Фикс. комиссия', 'commission', $summary);
        $this->addFieldToSummary('Куда отправлять', 'place', $summary);

        return $summary;
    }

    private function addFieldToSummary(string $fieldDisplayName, string $fieldName, Collection &$summary): void
    {
        if (isset($this->{$fieldName}) && $this->{$fieldName} != null) {
            $summary[$fieldDisplayName] = $this->{$fieldName};
        }
    }
}