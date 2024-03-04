<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoggingController extends Controller
{
    public function error(Request $request)
    {
        $errorName = $request->name;
        $errorData = json_encode($request->data);
        Log::debug('Frontend Error: ' . $errorName . ' ' . $errorData);
        response(null, 200);
    }
}
