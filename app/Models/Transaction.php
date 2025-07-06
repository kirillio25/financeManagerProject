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
}
