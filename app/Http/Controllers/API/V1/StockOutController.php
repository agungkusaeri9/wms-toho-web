<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Generate;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockOutController extends Controller
{
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'code' => ['required'],
            'qty' => ['required', 'numeric', 'min:1']
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }
        $generate = Generate::where('code', request('code'))->first();
        if (!$generate) {
            return ResponseFormatter::error(null, 'Qr Code not found', 404);
        }
        if ($generate->qty < request('qty')) {
            return ResponseFormatter::error([], 'Quantity exceeds available stock', 500);
        }
        DB::beginTransaction();
        try {
            $data = request()->only(['qty']);
            $data['product_id'] = $generate->product_id;
            $data['date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth('api')->id();
            $data['code'] = StockOut::getNewCode();
            $data['department_id'] = $generate->product->department_id;
            $stockOut = StockOut::create($data);

            $sisa = $generate->qty - request('qty');
            $generate->update([
                'qty' => $sisa
            ]);
            DB::commit();
            return ResponseFormatter::success($stockOut, 'Stock Out has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
