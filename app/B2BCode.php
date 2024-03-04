<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class B2BCode extends Model
{
    protected $table = 'b2b_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'b2b_coupon_id', 'code', 'used_at', 'user_id', 'description', 'is_assigned', 'assigned_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'used_at' => 'datetime',
        'assigned_at' => 'datetime'
    ];

    public function b2bCoupon()
    {
        return $this->belongsTo(B2BCoupon::class, 'b2b_coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
