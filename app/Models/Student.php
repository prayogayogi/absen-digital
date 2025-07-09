<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'name',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'email',
        'photo',
        'class_id',
        'parent_name',
        'parent_phone',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}
