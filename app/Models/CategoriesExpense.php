<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriesExpense extends Model
{
    use HasFactory;
    protected $table = 'categories_expense';

    protected $fillable = [
        'user_id',
        'name',
    ];
}
