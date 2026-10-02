<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'code',
        'title',
        'description',
        'activity_date',
        'category_id',
        'status',
        'capacity',
        'registered_count',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'capacity' => 'integer',
            'registered_count' => 'integer',
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

    public function isComplete(): bool
    {
        return ! empty($this->title)
            && ! empty($this->category_id)
            && ! empty($this->activity_date)
            && ! empty($this->description)
            && trim($this->description) !== '';
    }

    public function hasCapacity(): bool
    {
        return $this->registered_count < $this->capacity;
    }

    public function isPast(): bool
    {
        return $this->activity_date < now()->startOfDay();
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $q, string $term) {
            $q->where(function (Builder $sub) use ($term) {
                $sub->where('code', 'like', "%{$term}%")
                    ->orWhere('title', 'like', "%{$term}%");
            });
        });
    }

    public function scopeFilterCategory(Builder $query, mixed $categoryId): Builder
    {
        return $query->when($categoryId, function (Builder $q, $catId) {
            $q->where('category_id', $catId);
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return $query->when($status, function (Builder $q, string $st) {
            $q->where('status', strtolower($st));
        });
    }

    public function scopeSortDate(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'oldest', 'asc', 'start_at_asc' => $query->orderBy('activity_date', 'asc'),
            default => $query->orderBy('activity_date', 'desc'),
        };
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->search($filters['search'] ?? null)
            ->filterCategory($filters['category_id'] ?? null)
            ->filterStatus($filters['status'] ?? null)
            ->sortDate($filters['sort'] ?? 'latest');
    }
}
