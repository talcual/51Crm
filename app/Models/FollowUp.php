<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'followable_type',
        'followable_id',
        'type',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'assigned_to',
        'ai_suggested',
        'ai_reasoning',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'ai_suggested' => 'boolean',
    ];

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
