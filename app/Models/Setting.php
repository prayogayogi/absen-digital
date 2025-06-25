<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * @var string
     */

    protected $casts = [
        'value_file' => 'array',
    ];

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'value_file',
        'name',
        'type',
        'ext',
        'category',
    ];

    public static function getCategories()
    {
        return self::select('category')
            ->distinct()
            ->get();
    }
}
