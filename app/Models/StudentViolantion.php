<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolantion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'violation_type',
        'description',
        'date',
    ];
}
