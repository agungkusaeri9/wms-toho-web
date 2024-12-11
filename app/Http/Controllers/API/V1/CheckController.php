<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function check()
    {
        return ResponseFormatter::success(null, 'Host is running', 200);
    }
}
