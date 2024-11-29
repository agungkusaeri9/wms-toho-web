<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RackController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Rack Index')->only('index');
        $this->middleware('can:Rack Create')->only(['create', 'store']);
        $this->middleware('can:Rack Edit')->only(['edit', 'update']);
        $this->middleware('can:Rack Delete')->only('destroy');
    }

    public function index()
    {
        $items = Rack::orderBy('name', 'ASC')->where('area_id', request('area_id'))->get();
        return view('pages.rack.index', [
            'title' => 'Area',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.rack.create', [
            'title' => 'Create Area',
            'statuses' => Rack::getSatus()
        ]);
    }

    public function store()
    {
        request()->validate([
            'code' => ['required', Rule::unique('racks')->where(function ($query) {
                return $query->where('area_id', request('area_id'));
            }),],
            'name' => ['required'],
            'area_id' => 'required|exists:areas,id',
            'status' => ['required', 'in:Available,Full,Under Maintenance']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['code', 'name', 'status', 'description', 'area_id']);
            Rack::create($data);
            DB::commit();
            return redirect()->route('racks.index', ['area_id' => request('area_id')])->with('success', 'Rack berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Rack::findOrFail($id);
        return view('pages.rack.edit', [
            'title' => 'Edit Area',
            'item' => $item,
            'statuses' => Rack::getSatus()
        ]);
    }

    public function update($id)
    {
        $item = Rack::findOrFail($id);
        request()->validate([
            'code' => ['required', Rule::unique('racks')->where(function ($query) use ($item) {
                return $query->where('area_id', $item->area_id);
            })->ignore($id)],
            'name' => ['required'],
            'status' => ['required', 'in:Available,Full,Under Maintenance'],
        ]);

        DB::beginTransaction();
        try {
            $item = Rack::findOrFail($id);
            $data = request()->only(['code', 'name', 'type', 'description', 'status']);
            $item->update($data);
            DB::commit();
            return redirect()->route('racks.index', [
                'area_id' => $item->area_id
            ])->with('success', 'Rack berhasil diupdate.');
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
            $item = Rack::findOrFail($id);
            $area_id = $item->area_id;
            $item->delete();
            DB::commit();
            return redirect()->route('racks.index', [
                'area_id' => $area_id
            ])->with('success', 'Rack berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getByAreaId()
    {
        if (request()->ajax()) {
            $racks = Rack::where('area_id', request('area_id'))->orderBy('name', 'ASC')->get();
            return response()->json($racks);
        }
    }
}
