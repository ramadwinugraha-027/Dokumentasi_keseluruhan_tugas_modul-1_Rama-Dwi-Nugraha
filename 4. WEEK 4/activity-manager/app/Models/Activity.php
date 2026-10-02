<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'activity_date',
        'category_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
