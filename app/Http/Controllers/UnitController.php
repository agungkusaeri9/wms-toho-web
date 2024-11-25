<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Unit Index')->only('index');
        $this->middleware('can:Unit Create')->only(['create', 'store']);
        $this->middleware('can:Unit Edit')->only(['edit', 'update']);
        $this->middleware('can:Unit Delete')->only('destroy');
    }

    public function index()
    {
        $items = Unit::orderBy('name', 'ASC')->get();
        return view('pages.unit.index', [
            'title' => 'Unit',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.unit.create', [
            'title' => 'Create Unit'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'unique:units,name'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name']);
            Unit::create($data);

            DB::commit();
            return redirect()->route('units.index')->with('success', 'Unit berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Unit::findOrFail($id);
        return view('pages.unit.edit', [
            'title' => 'Edit Unit',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required', 'unique:units,name,' . $id . ''],
        ]);

        DB::beginTransaction();
        try {
            $item = Unit::findOrFail($id);
            $data = request()->only(['name']);
            $item->update($data);

            DB::commit();
            return redirect()->route('units.index')->with('success', 'Unit berhasil diupdate.');
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
            $item = Unit::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
