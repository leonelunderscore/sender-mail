<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    public function smtp(): BelongsTo
    {
        return $this->belongsTo(Smtp::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class, 'campaign_id');
    }

    public function formatAttachement(string $filename)
    {
        return config('filesystems.disks.s3.url') . '/' . $filename;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_sent' => 'boolean',
            'attachments' => 'array'
        ];
    }
}
