<?php

namespace App\Models;

use App\Models\TeacherPrize;
use App\Models\TeacherActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'fullname',
        'gender',
        'phone',
        'security_code',
        'national_identity_number',
        'blood_type',
        'level',
        'start_date',
        'lessons_studied',
        'note',
    ];

    protected $casts = [
        'lessons_studied' => 'array',
    ];

    public function TeacherPrize()
    {
        return $this->hasMany(TeacherPrize::class, 'teacher_id', 'id');
    }

    public function TeacherActivity()
    {
        return $this->hasMany(TeacherActivity::class, 'teacher_id', 'id');
    }

    public function TeacherExam()
    {
        return $this->hasMany(TeacherExam::class, 'teacher_id', 'id');
    }

    public function TeacherLecture()
    {
        return $this->hasMany(TeacherLecture::class, 'teacher_id', 'id');
    }
}
