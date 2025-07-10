<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year',
        'homeroom_teacher_id',
    ];

    public function student(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
