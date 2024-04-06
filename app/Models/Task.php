<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    public const STATUS_DONE = 1;
    public const STATUS_TODO = 2;
    public const STATUS_DOING = 3;
    public const STATUS_OVER_DUE = 4;

    protected $fillable = [
        'title',
        'description',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setStatus($status): static
    {
        $this->status = match ($status) {
            1 => 'done',
            2 => 'todo',
            3 => 'doing',
            4 => 'over_due',
            default => 'todo',
        };
        $this->save();
        return $this;
    }
}
