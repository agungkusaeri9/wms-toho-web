<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Area Index')->only('index');
        $this->middleware('can:Area Create')->only(['create', 'store']);
        $this->middleware('can:Area Edit')->only(['edit', 'update']);
        $this->middleware('can:Area Delete')->only('destroy');
    }

    public function index()
    {
        $items = Area::orderBy('name', 'ASC')->get();
        return view('pages.area.index', [
            'title' => 'Area',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.area.create', [
            'title' => 'Create Area',
            'types' => Area::getTypes()
        ]);
    }

    public function store()
    {
        request()->validate([
            'code' => ['required', 'unique:areas,code'],
            'name' => ['required'],
            'type' => ['required', 'in:Storage,Receiving,Shipping,Packing']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['code', 'name', 'type', 'description']);
            Area::create($data);

            DB::commit();
            return redirect()->route('areas.index')->with('success', 'Area berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Area::findOrFail($id);
        return view('pages.area.edit', [
            'title' => 'Edit Area',
            'item' => $item,
            'types' => Area::getTypes()
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'code' => ['required', 'unique:areas,code,' . $id . ''],
            'name' => ['required'],
            'type' => ['required', 'in:Storage,Receiving,Shipping,Packing']
        ]);

        DB::beginTransaction();
        try {
            $item = Area::findOrFail($id);
            $data = request()->only(['code', 'name', 'type', 'description']);
            $item->update($data);
            DB::commit();
            return redirect()->route('areas.index')->with('success', 'Area berhasil diupdate.');
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
            $item = Area::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('areas.index')->with('success', 'Area berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
