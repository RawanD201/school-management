<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherExam extends Model
{
    use HasFactory;

    protected $table = 'teacher_exams';

    protected $primaryKey = 'id';

    protected $fillable = [
        'teacher_id',
        'mark',
        'lecture',
    ];
}
