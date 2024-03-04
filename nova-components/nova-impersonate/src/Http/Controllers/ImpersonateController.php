<?php

namespace trosthelden\NovaImpersonate\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\ActionEvent;
use trosthelden\NovaImpersonate\Cookies;
use trosthelden\NovaImpersonate\Events\LeaveImpersonation;
use trosthelden\NovaImpersonate\Events\TakeImpersonation;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class ImpersonateController extends Controller
{

    public function take(Request $request, $id, $guardName = null)
    {

        event(new TakeImpersonation(Nova::user($request), User::find($id)));

        return redirect()->to($request->get('redirect_to', config('nova-impersonate.redirect_to')));
    }

    public function leave()
    {
        event(new LeaveImpersonation());

        return redirect()->to('/nova/resources/users');
    }

    protected function recordAction($userId, $user_to_impersonate, $actionName)
    {
        ActionEvent::create([
            'batch_id' => (string) Str::orderedUuid(),
            'user_id' => $userId,
            'name' => $actionName,
            'actionable_type' => $user_to_impersonate->getMorphClass(),
            'actionable_id' => $user_to_impersonate->getKey(),
            'target_type' => $user_to_impersonate->getMorphClass(),
            'target_id' => $user_to_impersonate->getKey(),
            'model_type' => $user_to_impersonate->getMorphClass(),
            'model_id' => $user_to_impersonate->getKey(),
            'fields' => '',
            'status' => 'finished',
            'exception' => '',
        ]);
    }

    public static function isImpersonating(): bool
    {
        return Cookie::has(Cookies::IMPERSONATING);
    }
}
