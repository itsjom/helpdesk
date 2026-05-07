<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    public const KIND_GENERAL = 'general';

    protected $fillable = [
        'code',
        'name',
        'sort_order',
        'is_active',
        'kind',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'service_type', 'code');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function labelForCode(string $code): string
    {
        return static::where('code', $code)->value('name') ?? str_replace('_', ' ', $code);
    }
}
