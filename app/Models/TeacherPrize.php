<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherPrize extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_prizes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'teacher_id',
        'name'
    ];
}
