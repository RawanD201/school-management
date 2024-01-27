<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPrize extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_prizes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'name'
    ];
}
