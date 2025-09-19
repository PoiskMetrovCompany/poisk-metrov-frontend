<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatTokenCRMLeadPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_token',
        'crm_city',
        'crm_id'
    ];

    /**
     * Получить город CRM, к которому относится пара токен-лид.
     */
    public function crmCity(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'crm_city', 'slug');
    }
}
