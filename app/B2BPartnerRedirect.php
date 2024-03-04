<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class B2BPartnerRedirect extends Model
{
    protected $table = 'b2b_partner_redirects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'b2b_partner_id', 'slug', 'target'
    ];

    public function b2bPartner()
    {
        return $this->belongsTo(B2BPartner::class, 'b2b_partner_id');
    }
}
