<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'total_customer',
        'total_transaction',
        'total_product',
    ];

    public $timestamps = false;
}
