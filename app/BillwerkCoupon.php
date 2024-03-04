<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillwerkCoupon extends Model
{
    protected $casts = [
        'sent_at' => 'datetime:Y-m-d',
    ];
}
