<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Type Index')->only('index');
        $this->middleware('can:Type Create')->only(['create', 'store']);
        $this->middleware('can:Type Edit')->only(['edit', 'update']);
        $this->middleware('can:Type Delete')->only('destroy');
    }

    public function index()
    {
        $items = Type::orderBy('name', 'ASC')->get();
        return view('pages.type.index', [
            'title' => 'Type',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.type.create', [
            'title' => 'Create Type'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'unique:types,name'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name']);
            Type::create($data);

            DB::commit();
            return redirect()->route('types.index')->with('success', 'Type has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Type::findOrFail($id);
        return view('pages.type.edit', [
            'title' => 'Edit Type',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required', 'unique:types,name,' . $id . ''],
        ]);

        DB::beginTransaction();
        try {
            $item = Type::findOrFail($id);
            $data = request()->only(['name']);
            $item->update($data);
            DB::commit();
            return redirect()->route('types.index')->with('success', 'Type has been updated successfully.');
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
            $item = Type::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('types.index')->with('success', 'Type has been deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
