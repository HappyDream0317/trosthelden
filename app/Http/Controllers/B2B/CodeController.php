<?php

namespace App\Http\Controllers\B2B;

use App\B2BCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['markAsUsed']);
    }

    public function getAssigned(Request $request, $user_id)
    {
        $perPage = $request->get('perPage') ?? 50;

        $results = B2BCode::with([
            'user',
            'b2bCoupon',
            'b2bCoupon.b2bUser',
            'b2bCoupon.b2bUser.user'
        ])
            ->whereHas('b2bCoupon.b2bUser', function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })
            ->where(function ($query) {
                $query->where('is_assigned', true)
                    ->orWhere('user_id', '<>', null);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        return response()->json($results);
    }

    public function getNotAssigned(Request $request, $user_id)
    {
        $perPage = $request->get('perPage') ?? 50;

        $results = B2BCode::with([
                'user',
                'b2bCoupon',
                'b2bCoupon.b2bUser',
                'b2bCoupon.b2bUser.user'
            ])
            ->whereHas('b2bCoupon.b2bUser', function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })
            ->where('is_assigned', false)
            ->whereNull('user_id')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        return response()->json($results);
    }


    public function markAsUsed(Request $request)
    {
        try {
            B2BCode::where('code', $request->code)
                ->update([
                    'user_id' => $request->user,
                    'used_at' => Carbon::now()
                ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

    }

    public function getPDF(Request $request, $user_id, $id) {

        abort_if(!$id, 404, 'Code not found');

        $code= B2BCode::find($id);

        abort_if(!$code, 404, 'Code not found');

        $planVariant = $code->b2bCoupon->getProduct();

        abort_if(!$planVariant, 404, 'PlanVariant not found in Billwerk');

        $product = $planVariant->Id === config('billwerk.plans.standard.6_months')? 6 : 1;

        $pdf = Pdf::loadView('documents.code', [
            'code' => $code->code,
            'product' => $product
        ]);

        $time = time();
        $name = "{$user_id}_{$code->code}_{$time}";
        $file = Storage::disk('local')->path(''). "/$name.pdf";
        $pdf->save($file);

        return response()->json(['url' => route("b2b.show.code.pdf", [
            "user_id" => $user_id,
            "id"=> $id,
            "name"=> $name
        ]) ]);
    }

    public function showPDF(Request $request, $user_id, $id, $name) {
        abort_if(!$id, 404, 'Code not found');

        $code= B2BCode::findOrFail($id);

        abort_if(!$code, 404, 'Code not found');

        $file = Storage::disk('local')->path(''). "/$name.pdf";

        return response()->download($file, "$name.pdf")->deleteFileAfterSend(true);
    }

    public function updateDescription(Request $request, $user_id, $id)
    {
        abort_if(!$id, 404, 'Code not found');

        $code = B2BCode::findOrFail($id);

        abort_if(!$code, 404, 'Code not found');

        $code->description = $request->description;

        $save = $code->save();

        abort_if(!$save, 404, 'Code not saved');

        return response()->json(['success' => true]);
    }

    public function updateAssigned(Request $request, $user_id, $id)
    {
        abort_if(!$id, 404, 'Code not found');

        $code = B2BCode::findOrFail($id);

        abort_if(!$code, 404, 'Code not found');

        $isAssigned = $request->get('isAssigned');

        abort_if(!$isAssigned, 404, 'Code assigned value not found');

        $code->is_assigned = $isAssigned;
        $code->assigned_at = ($isAssigned)? Carbon::now() : null;

        $save = $code->save();

        abort_if(!$save, 404, 'Code not saved');

        return response()->json(['success' => true]);
    }

}
