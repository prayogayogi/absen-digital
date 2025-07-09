<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status',
    ];
}
