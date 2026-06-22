<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['user_id', 'name', 'teacher', 'passing_grade'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function average(): ?float
    {
        if ($this->grades->isEmpty()) {
            return null;
        }

        $totalWeight = $this->grades->sum('weight');
        if ($totalWeight == 0) {
            return null;
        }

        $weightedSum = $this->grades->sum(fn($g) => $g->value * $g->weight);

        return round($weightedSum / $totalWeight, 2);
    }

    public function willPass(): ?bool
    {
        $avg = $this->average();
        if ($avg === null) return null;

        return $avg >= $this->passing_grade;
    }
}
