<?php

namespace App\Http\Controllers\B2B;

use App\B2BCode;
use App\B2BCoupon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\User;
class CouponController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['hasValidFlatrate']);
    }
    public function hasValidFlatrate(Request $request)
    {

        $user_id = $request->get('user_id');

        $user = User::has('b2bUser')->with('b2bUser')->find($user_id);

        abort_if(!$user, 404, 'User not found');

        $b2bCoupons = B2BCoupon::where('b2b_user_id', $user->b2bUser->id)
            ->where('is_flatrate', true)
            ->get()
            ->filter
            ->isValid()
            ->first();

        return response()->json($b2bCoupons);
    }

    public function generateCode(Request $request,  $user_id, $id){

        abort_if(!$id, 404, 'Coupon ID not found');

        $b2bCoupon = B2BCoupon::find($id);

        abort_if(!$b2bCoupon, 404, 'B2B Coupon not found');

        $b2bCode = $b2bCoupon->generateCodes();

        abort_if(!$b2bCode, 404, 'B2B Code not created');

        return response()->json($b2bCode->first());

    }
}
