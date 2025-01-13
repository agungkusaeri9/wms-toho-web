<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\API\V1\ResponseFormatter;
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
            'code' => ['required'],
            'user_id' => ['required', 'numeric']
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }
        $generate = Generate::where('code', request('code'))->first();
        if ($generate->status == 1) {
            return ResponseFormatter::error(null, 'The QR code has already been used properly.', 400);
        }

        DB::beginTransaction();
        try {
            $data = [];
            $generate->update(['status' => 1]);
            $data['product_id'] = $generate->product_id;
            $data['qty'] = $generate->qty;
            $data['received_date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = request('user_id');
            $data['code'] = StockIn::getNewCode();
            $data['generate_id'] = $generate->id;
            $stokIn = StockIn::create($data);
            $generate->product->increment('stock', $stokIn->qty);
            DB::commit();
            return ResponseFormatter::success($stokIn, 'Stock In has been created successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
