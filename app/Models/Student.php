<?php

namespace App\Models;

use App\Models\StudentExam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use  HasFactory, SoftDeletes;

    protected $fillable = [
        "fullname",
        "age",
        "gender",
        "phone",
        "father_occupation",
        "mother_occupation",
        "address",
        "sickness",
        "blood_type",
        "father_educational_level",
        "note",
        "level",
        "class",
    ];

    /**
     */
    protected $casts = [
        'birthday' => 'date',
    ];

    public function levels()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function StudentPrize()
    {
        return $this->hasMany(StudentPrize::class, 'student_id', 'id');
    }

    public function StudentExam()
    {
        return $this->hasMany(StudentExam::class, 'student_id', 'id');
    }
}
