<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Generate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QrGeneratorController extends Controller
{
    public function checkQr()
    {
        $validator = Validator::make(request()->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }

        try {
            $item = Generate::with(['product.part_number', 'product.type', 'product.unit', 'product.department', 'product.rack', 'product.area'])->where('code', request('code'))->first();
            if (!$item) {
                return ResponseFormatter::error(null, 'Qr Code not found', 404);
            }
            return ResponseFormatter::success($item, 'Qr Code valid');
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
