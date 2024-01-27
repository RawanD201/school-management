<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLecture extends Model
{
    use HasFactory;
    protected $table = 'teacher_lectures';

    protected $primaryKey = 'id';

    protected $fillable = [
        'teacher_id',
        'name'
    ];
}
