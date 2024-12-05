<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Supplier Index')->only('index');
        $this->middleware('can:Supplier Create')->only(['create', 'store']);
        $this->middleware('can:Supplier Edit')->only(['edit', 'update']);
        $this->middleware('can:Supplier Delete')->only('destroy');
    }

    public function index()
    {
        $items = Supplier::orderBy('code', 'ASC')->get();
        return view('pages.supplier.index', [
            'title' => 'Supplier',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.supplier.create', [
            'title' => 'Create Supplier'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['email', 'nullable'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'contact_person' => ['nullable']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name', 'email', 'phone', 'address', 'contact_person']);
            $data['code'] = Supplier::getNewCode();
            Supplier::create($data);

            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Supplier::findOrFail($id);
        return view('pages.supplier.edit', [
            'title' => 'Edit Supplier',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['email', 'nullable'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'contact_person' => ['nullable']
        ]);

        DB::beginTransaction();
        try {
            $item = Supplier::findOrFail($id);
            $data = request()->only(['name', 'email', 'phone', 'address', 'contact_person']);
            $item->update($data);

            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier has been updated successfully.');
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
            $item = Supplier::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier has been deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
