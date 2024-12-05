<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Department Index')->only('index');
        $this->middleware('can:Department Create')->only(['create', 'store']);
        $this->middleware('can:Department Edit')->only(['edit', 'update']);
        $this->middleware('can:Department Delete')->only('destroy');
    }

    public function index()
    {
        $items = Department::orderBy('name', 'ASC')->get();
        return view('pages.department.index', [
            'title' => 'Department',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.department.create', [
            'title' => 'Create item'
        ]);
    }

    public function store()
    {
        request()->validate([
            'code' => ['required', 'unique:Departments,code'],
            'name' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name', 'code']);
            Department::create($data);

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Department::findOrFail($id);
        return view('pages.department.edit', [
            'title' => 'Edit Department',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'code' => ['required', 'unique:departments,code,' . $id . ''],
            'name' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $item = Department::findOrFail($id);
            $data = request()->only(['name', 'code']);
            $item->update($data);

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $item = Department::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
