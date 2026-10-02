<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            if ($category->activities()->exists()) {
                throw new DomainException("Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh kegiatan aktif.");
            }
        });
    }
}
