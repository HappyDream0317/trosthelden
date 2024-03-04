<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class B2BDiscount extends Model
{
    protected $table = 'b2b_discounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',  'b2b_partner_id'
    ];
        
    public function b2bPartner()
    {
        return $this->belongsTo(B2BPartner::class, 'b2b_partner_id');
    }
}

