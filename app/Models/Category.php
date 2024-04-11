<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'user_id',
    ];

    protected $hidden = [
        'category_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static null|Category $firstDeletedNodeParent = null;

    protected static function booted(): void
    {
        parent::booted();

        static::deleting(function (Category $category) {

            if (self::isTheFirstNodeInThisDeletion())
                self::setFirstDeletedNodeParent($category->parent);

            $category->tasks()->update(['category_id' => self::$firstDeletedNodeParent->id]);

            $childCategories = $category->children;
            foreach ($childCategories as $childCategory) {
                $childCategory->delete();
            }
        });

        static::deleted(function () {
            self::resetFirstDeletedNodeParent();
        });
    }

    protected static function isTheFirstNodeInThisDeletion(): bool
    {
        return self::$firstDeletedNodeParent === null;
    }

    protected static function resetFirstDeletedNodeParent(): void
    {
        self::$firstDeletedNodeParent = null;
    }

    protected static function setFirstDeletedNodeParent(Category $category): void
    {
        self::$firstDeletedNodeParent = $category;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
