<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class B2BPartner extends Model
{
    protected $table = 'b2b_partners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'b2b_user_id'
    ];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['code'];
    }

    public function b2bUser()
    {
        return $this->belongsTo(B2BUser::class, 'b2b_user_id');
    }

    public function b2bDiscount(): HasMany
    {
        return $this->hasMany(B2BDiscount::class, 'b2b_partner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'b2b_partner_id');
    }

    public function b2bRedirects(): HasMany
    {
        return $this->hasMany(B2BPartnerRedirect::class, 'b2b_partner_id');
    }
}
