<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Payment\BillwerkApi;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class B2BUser extends Model
{
    protected $table = 'b2b_users';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'vat_id', 'type'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function b2bCoupons() : HasMany
    {
        return $this->hasMany(B2BCoupon::class, 'b2b_user_id');
    }

    public function b2bPartner() : HasOne
    {
        return $this->hasOne(B2BPartner::class, 'b2b_user_id');
    }

    public function generateCoupon($contract) {
        $billwerkApi = new BillwerkApi(new Client());

        abort_if(!$contract, 404, 'Contract not found');

        $planVariantId = $contract->PlanVariantId ?? $contract->Phases[0]->PlanVariantId ?? null;

        abort_if(!$planVariantId, 404, 'Plan Variant ID not found');

        $isFuneralCompanyProductId = $this->isFuneralCompanyProductId($planVariantId);
        $isFlat = ($planVariantId  === config('billwerk.plans.b2b.flatrate_1_years'));

        $planGroupId = config('billwerk.plangroups.standard');
        $discountId = (!$isFuneralCompanyProductId && !$isFlat)? config('billwerk.discount.100_6_months') : config('billwerk.discount.100_1_months');
        $isFlatrate = $isFuneralCompanyProductId && $isFlat;
        $validUntil = ($isFlat)? Carbon::parse($contract->StartDate)->addYear() : null;


        $name = substr($contract->CustomerId,0,6)."_".substr($planVariantId,0,6);
        $name = uniqid("{$name}_");

        $coupon = $billwerkApi->generateCoupon(
            $planGroupId,
            $name,
            $discountId,
            $validUntil
        );

        abort_if(!$coupon, 404, 'Coupon not store in Billwerk');

        $b2bCoupon = $this->b2bCoupons()->create([
            "billwerk_id" => $coupon->Id,
            "contract_id" => $contract->Id,
            "is_flatrate" => $isFlatrate,
        ]);

        abort_if(!$b2bCoupon, 404, 'B2B Coupon not created');

        return  $b2bCoupon;
    }


    private function isFuneralCompanyProductId(String $productVariantId) {
        $funeralPlans = [
            config('billwerk.plans.b2b.50x_1_months'),
            config('billwerk.plans.b2b.100x_1_months'),
            config('billwerk.plans.b2b.200x_1_months'),
            config('billwerk.plans.b2b.flatrate_1_years'),
        ];

        return in_array($productVariantId, $funeralPlans);
    }
}
