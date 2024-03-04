<?php

namespace App\Http\Controllers\B2B;

use App\B2BPartner;
use App\B2BPartnerRedirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['getCodeById', 'getRedirectBySlug']);
    }


    public function getById(Request $request, $user_id, $id)
    {
        $partner = B2BPartner::where('id', $id)
            ->select(['id', 'code'])
            ->with(['b2bRedirects' => function ($query) {
                $query->select('id', 'b2b_partner_id', 'slug');
            }])
            ->first();

        abort_if(!$partner, 404, 'Partner not found');

        return response()->json($partner);
    }

    public function getCodeById(Request $request, $id)
    {
        $partner = B2BPartner::find($id);

        abort_if(!$partner, 404, 'Partner Code not found');

        return response()->json(['code' => $partner->code]);
    }

    public function getRedirectBySlug(Request $request, $slug) {

        abort_if(!$slug, 404, 'Slug not found');

        $redirect = B2BPartnerRedirect::has('b2bPartner')
            ->with(['b2bPartner' => function ($query) {
                $query->select('id', 'code');
            }])
            ->select('target', 'b2b_partner_id')
            ->where('slug', $slug)
            ->first();

        abort_if(!$redirect, 404, 'Partner Redirect not found');

        return response()->json([
            'target' => $redirect->target,
            'code' => $redirect->b2bPartner->code,
        ]);
    }

}
