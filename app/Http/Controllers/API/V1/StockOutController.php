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
            'qty' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }

        $generate = Generate::where('code', request('code'))->first();

        if ($generate->qty < request('qty')) {
            return ResponseFormatter::error([], 'Qty melebihi stock', 500);
        }
        DB::beginTransaction();
        try {
            $data = request()->only(['qty']);
            $data['product_id'] = $generate->product_id;
            $data['date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth('api')->id();
            $data['code'] = StockOut::getNewCode();
            $data['department_id'] = $generate->product->department_id;
            StockOut::create($data);

            if ($generate->qty != request('qty')) {
                $sisa = $generate->qty - request('qty');
                $generate->update([
                    'qty' => $sisa
                ]);
            }
            DB::commit();

            return ResponseFormatter::success([], 'Stock Out Berhasil dibuat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
