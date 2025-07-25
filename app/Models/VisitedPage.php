<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitedPage extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'User' => ['main_table_value' => 'user_id', 'linked_table_value' => 'id'],
    ];

    protected $fillable = ['user_id', 'page', 'code'];

    public static function createForCurrentUser(string $page, string $code)
    {
        $userId = Auth::id();

        if ($userId == null) {
            return;
        }

        if (! User::where(['id' => $userId])->exists()) {
            return;
        }

        $conditions = [
            'user_id' => $userId,
            'page' => $page,
            'code' => $code
        ];

        if (! VisitedPage::where($conditions)->exists()) {
            VisitedPage::create($conditions);
        }
    }

    public function visitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
