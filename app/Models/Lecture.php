<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecture extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lectures';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'level',
        'file',
    ];

    protected $casts = [
        'file' => 'array',
    ];
}
