<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expenses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'expense_type_id',
        'user',
        'amount',
        'date',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(ExpenseType::class, 'id', 'expense_type_id');
    }
}
