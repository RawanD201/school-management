<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_payments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'student',
        'amount',
        'date',
        'note',
    ];
}
