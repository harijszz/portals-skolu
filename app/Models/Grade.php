<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'value', 'weight', 'description', 'date'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'weight' => 'decimal:2',
            'date' => 'date:Y-m-d',
        ];
    }
}
