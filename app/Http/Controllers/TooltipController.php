<?php

namespace App\Http\Controllers;

use App\http\Resources\Tooltips as TooltipResource;
use App\Tooltip;
use Illuminate\Http\Request;

class TooltipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getTooltipsForProfile(Request $request)
    {
        $tooltips = new TooltipResource(Tooltip::where('page', 'profile')->get());

        return response()->json($tooltips);
    }
}
