<?php

namespace App;

use App\Payment\BillwerkApi;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class B2BCoupon extends Model
{
    protected $table = 'b2b_coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'b2b_user_id', 'billwerk_id', 'contract_id', 'expired_at', 'is_flatrate'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expired_at' => 'datetime'
    ];
    
    public function b2bUser()
    {
        return $this->belongsTo(B2BUser::class, 'b2b_user_id');
    }
    
    public function b2bCodes() : HasMany
    {
        return $this->hasMany(B2BCode::class, 'b2b_coupon_id');
    }

    public function generateCodes($count = 1) {

        $billwerkApi = new BillwerkApi(new Client());
        $codes = [];

        for ($i=0; $i < $count; $i++) {
            $code = $this->generateCodeName();
            array_push($codes, ["code" => $code]);
        }

        $coupons = $billwerkApi->generateCodes($this->billwerk_id, $codes);

        abort_if(!$coupons, 404, 'Coupon not updated in Billwerk');

        $b2bCode = $this->b2bCodes()->createMany($codes);

        abort_if(!$b2bCode, 404, 'B2B Code not created');

        return  $b2bCode;
    }

    public function isValid() {
        $billwerkApi = new BillwerkApi(new Client());

        $coupon = $billwerkApi->getCoupon($this->billwerk_id);

        abort_if(!$coupon, 404, 'Coupon not found in Billwerk');

        if(!empty($coupon->ValidUntil) && $coupon->ValidUntil !== $this->expired_at) {
            $this->expired_at = $coupon->ValidUntil;
            $this->save();
        }

        return $coupon->Active;
    }

    public function getProduct() {
        $billwerkApi = new BillwerkApi(new Client());

        $coupon = $billwerkApi->getCoupon($this->billwerk_id);

        abort_if(!$coupon, 404, 'Coupon not found in Billwerk');

        $discount = $billwerkApi->getDiscount($coupon->DiscountId);

        abort_if(!$discount, 404, 'Discount not found in Billwerk');

        $planVariantId = array_search('true', (array)$discount->Targets);

        abort_if(!$planVariantId, 404, 'PlanVariant ID not found in Billwerk');

        $planVariant = $billwerkApi->getPlanVariant($planVariantId);

        abort_if(!$planVariant, 404, 'PlanVariant not found in Billwerk');

        return $planVariant;

    }

    public function generateCodeName() {
       return strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 16));
    }
    
}
