<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Generate;
use App\Models\StockIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockInController extends Controller
{
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'code' => ['required']
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $data = [];
            $generate = Generate::where('code', request('code'))->first();
            $data['product_id'] = $generate->product_id;
            $data['qty'] = $generate->qty;
            $data['received_date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth('api')->id();
            $data['code'] = StockIn::getNewCode();
            $stokIn = StockIn::create($data);
            $generate->increment('qty', $stokIn->qty);
            DB::commit();
            return ResponseFormatter::success($stokIn, 'Stock In has been created successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
