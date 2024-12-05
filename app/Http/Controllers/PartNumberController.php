<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Part Number Index')->only('index');
        $this->middleware('can:Part Number Create')->only(['create', 'store']);
        $this->middleware('can:Part Number Edit')->only(['edit', 'update']);
        $this->middleware('can:Part Number Delete')->only('destroy');
    }

    public function index()
    {
        $items = PartNumber::orderBy('name', 'ASC')->get();
        return view('pages.part-number.index', [
            'title' => 'Part Number',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.part-number.create', [
            'title' => 'Create Part Number'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'unique:part_numbers,name'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name']);
            PartNumber::create($data);

            DB::commit();
            return redirect()->route('part-numbers.index')->with('success', 'Part Number has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = PartNumber::findOrFail($id);
        return view('pages.part-number.edit', [
            'title' => 'Edit Part Number',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required', 'unique:part_numbers,name,' . $id . ''],
        ]);

        DB::beginTransaction();
        try {
            $item = PartNumber::findOrFail($id);
            $data = request()->only(['name']);
            $item->update($data);
            DB::commit();
            return redirect()->route('part-numbers.index')->with('success', 'Part Number has been updated successfully.');
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
            $item = PartNumber::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('part-numbers.index')->with('success', 'Part Number has been deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
