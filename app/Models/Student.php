<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nisn',
        'name',
        'class_id',
        'parent_name',
        'parent_phone',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}
