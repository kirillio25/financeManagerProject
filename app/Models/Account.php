<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'name',
        'note',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}