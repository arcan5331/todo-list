<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    public const STATUS_DONE = 1;
    public const STATUS_TODO = 2;
    public const STATUS_DOING = 3;
    public const STATUS_OVER_DUE = 4;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected $with = ['tags'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function setStatus($status): static
    {
        $this->status = match ($status) {
            self::STATUS_DONE => 'done',
            self::STATUS_TODO => 'todo',
            self::STATUS_DOING => 'doing',
            self::STATUS_OVER_DUE => 'over_due',
            default => 'todo',
        };
        $this->save();
        return $this;
    }
}
