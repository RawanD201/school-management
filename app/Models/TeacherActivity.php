<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherActivity extends Model
{
    use HasFactory;

    protected $table = 'teacher_activities';

    protected $primaryKey = 'id';

    protected $fillable = [
        'teacher_id',
        'name'
    ];
}
