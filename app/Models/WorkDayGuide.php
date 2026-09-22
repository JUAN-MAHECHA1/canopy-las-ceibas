<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkDayGuide extends Model
{
    protected $fillable = ['work_day_id', 'user_id'];

    public function workDay(): BelongsTo
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function guia(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
