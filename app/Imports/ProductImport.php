<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Department;
use App\Models\PartNumber;
use App\Models\Product;
use App\Models\Rack;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // dd($rows);
        foreach ($rows as $key => $row) {
            // dd($row);
            if ($key > 0) {
                $part_number_arr = $row[1];
                $part_name_arr = $row[2];
                $part_code_arr = $row[3];
                $type_arr = $row[4];
                $part_unit_arr = $row[4];
                $department_arr = $row[6];
                $area_arr = $row[7];
                $rack_arr = $row[8];
                $supplier_arr = $row[9];

                if ($part_number_arr && $part_name_arr && $part_code_arr && $type_arr && $part_unit_arr && $department_arr && $area_arr && $rack_arr && $supplier_arr) {
                    // cek part number
                    $part_number = PartNumber::where('name', $part_number_arr)->first();
                    if (!$part_number) {
                        $part_number = PartNumber::create([
                            'name' => $part_number_arr
                        ]);
                    }
                    $part_number_id = $part_number->id;

                    // cek unit
                    $unit = Unit::where('name', $part_unit_arr)->first();
                    if (!$unit) {
                        $unit = Unit::create([
                            'name' => $part_unit_arr
                        ]);
                    }
                    $unit_id = $unit->id;

                    // cek department
                    $department = Department::where('name', $department_arr)->first();
                    if (!$department) {
                        $department = Department::create([
                            'code' => \Str::random(5),
                            'name' => $department_arr
                        ]);
                    }
                    $department_id = $department->id;

                    // cek suppier
                    $supplier = Supplier::where('name', $supplier_arr)->first();
                    if (!$supplier) {
                        $supplier = Supplier::create([
                            'code' => \Str::random(5),
                            'name' => $supplier_arr
                        ]);
                    }
                    $supplier_id = $supplier->id;

                    // cek type
                    $type = Type::where('name', $type_arr)->first();
                    if (!$type) {
                        $type = Type::create([
                            'name' => $type_arr
                        ]);
                    }
                    $type_id = $type->id;

                    // cek area
                    $area = Area::where('name', $area_arr)->first();
                    if (!$area) {
                        $area = Area::create([
                            'code' => \Str::random(5),
                            'name' => $area_arr
                        ]);
                    }
                    $area_id = $area->id;

                    // cek rack
                    $rack = Rack::where('name', $rack_arr)->first();
                    if (!$rack) {
                        $rack = Rack::create([
                            'code' => \Str::random(5),
                            'name' => $rack_arr,
                            'area_id' => $area_id
                        ]);
                    }
                    $rack_id = $rack->id;
                    // cek product
                    $product = Product::where('code', $part_code_arr)->first();
                    if (!$product) {
                        Product::create([
                            'code' => $part_code_arr,
                            'name' => $part_name_arr,
                            'part_number_id' => $part_number_id,
                            'unit_id' => $unit_id,
                            'department_id' => $department_id,
                            'supplier_id' => $supplier_id,
                            'type_id' => $type_id,
                            'area_id' => $area_id,
                            'rack_id' => $rack_id
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Product has been imported successfully.');
    }
}
