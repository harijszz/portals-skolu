<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = ['subject_id', 'value', 'weight', 'description', 'date'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
