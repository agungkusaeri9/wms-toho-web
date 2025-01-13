<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\API\V1\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function all(Request $request)
    {
        $role = $request->get('role', '');
        $limit = $request->get('limit', 10);
        $keyword = $request->get('keyword', '');
        $items = Employee::with('user')->whereHas('user', function ($q) use ($role) {
            $q->whereHas('roles', function ($query) use ($role) {
                if ($role)
                    $query->where('name', $role);
            });
        })->orderBy('name', 'asc');
        if ($keyword) {
            $items->where('name', 'like', '%' . $keyword . '%');
        }
        $employees = $items->limit($limit)->get();
        // $employees = $items->paginate($limit);
        // $pagination = [
        //     'current_page' => $employees->currentPage(),
        //     'last_page' => $employees->lastPage(),
        //     'per_page' => $employees->perPage(),
        //     'total' => $employees->total(),
        // ];
        // return ResponseFormatter::success($employees, 'Employee fetched', 200, $pagination);
        return ResponseFormatter::success($employees, 'Employee fetched', 200);
    }
}
