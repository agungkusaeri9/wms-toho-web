<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Category Index')->only('index');
        $this->middleware('can:Category Create')->only(['create', 'store']);
        $this->middleware('can:Category Edit')->only(['edit', 'update']);
        $this->middleware('can:Category Delete')->only('destroy');
    }

    public function index()
    {
        $items = Category::orderBy('name', 'ASC')->get();
        return view('pages.category.index', [
            'title' => 'Category',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.category.create', [
            'title' => 'Create Category'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'unique:categories,name'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name']);
            Category::create($data);

            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Category::findOrFail($id);
        return view('pages.category.edit', [
            'title' => 'Edit Category',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required', 'unique:categories,name,' . $id . ''],
        ]);

        DB::beginTransaction();
        try {
            $item = Category::findOrFail($id);
            $data = request()->only(['name']);
            $item->update($data);
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category has been updated successfully.');
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
            $item = Category::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category has been deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
