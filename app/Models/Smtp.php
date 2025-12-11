<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Smtp extends Model
{
    public static function generateReference(): string
    {
        do {
            $reference = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 7));
        } while (Smtp::where('reference', $reference)->exists());
        return $reference;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'smtp_id');
    }
}
