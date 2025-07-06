<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriesIncome extends Model
{
    use HasFactory;
    protected $table = 'categories_income';

    protected $fillable = [
        'user_id',
        'name',
    ];
}
