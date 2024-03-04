<?php

namespace App\Http\Controllers\B2B;


use App\B2BDiscount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{

    public function getByCode(Request $request, $code)
    {
        $discount = B2BDiscount::where('code', $code)
            ->has('b2bPartner')
            ->with('b2bPartner')
            ->first();

        abort_if(!$discount, 404, 'Discount not found');

        return response()->json($discount);
    }


}
