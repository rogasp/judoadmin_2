<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }


}
