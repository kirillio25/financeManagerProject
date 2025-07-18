<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'type_id',
        'category_id',
        'account_id',
        'created_at',
        'updated_at',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function categoryIncome()
    {
        return $this->belongsTo(CategoriesIncome::class, 'category_id');
    }

    public function categoryExpense()
    {
        return $this->belongsTo(CategoriesExpense::class, 'category_id');
    }

    public function category()
    {
        return $this->type_id == 1
            ? $this->categoryIncome()
            : $this->categoryExpense();
    }
}
