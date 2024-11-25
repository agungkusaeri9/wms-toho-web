<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Product Index')->only('index');
        $this->middleware('can:Product Create')->only(['create', 'store']);
        $this->middleware('can:Product Edit')->only(['edit', 'update']);
        $this->middleware('can:Product Delete')->only('destroy');
    }

    public function index()
    {
        $items = Product::with(['department', 'unit', 'category'])->orderBy('name', 'ASC')->get();
        return view('pages.product.index', [
            'title' => 'Product',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.product.create', [
            'title' => 'Create Product',
            'categories' => Category::orderBy('name', 'ASC')->get(),
            'units' => Unit::orderBy('name', 'ASC')->get(),
            'departments' => Department::orderBy('name', 'ASC')->get()
        ]);
    }

    public function store()
    {
        request()->validate([
            'code' => ['required', 'unique:products,code'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required'],
            'unit_id' => ['required', 'exists:units,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'initial_qty' => ['required', 'numeric'],
            'image' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['code', 'name', 'category_id', 'unit_id', 'department_id', 'initial_qty', 'description']);
            if (request()->file('image')) {
                $data['image'] = request()->file('image')->store('prduct', 'public');
            }
            if (request('initial_qty')) {
                $data['qty'] = request('initial_qty');
            }
            Product::create($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Product::findOrFail($id);
        return view('pages.product.edit', [
            'title' => 'Edit Product',
            'item' => $item,
            'categories' => Category::orderBy('name', 'ASC')->get(),
            'units' => Unit::orderBy('name', 'ASC')->get(),
            'departments' => Department::orderBy('name', 'ASC')->get()
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'code' => ['required', 'unique:products,code,' . $id . ''],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required'],
            'unit_id' => ['required', 'exists:units,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'image' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $item = Product::findOrFail($id);
            $data = request()->only(['code', 'name', 'category_id', 'unit_id', 'department_id', 'initial_qty', 'description']);
            if (request()->file('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $data['image'] = request()->file('image')->store('prduct', 'public');
            }
            $item->update($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
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
            $item = Product::findOrFail($id);
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
