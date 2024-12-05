<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::whereNotNull('id')->delete();
        Permission::create(['name' => 'Dashboard']);

        // roles
        Permission::create(['name' => 'Role Index']);
        Permission::create(['name' => 'Role Create']);
        Permission::create(['name' => 'Role Edit']);
        Permission::create(['name' => 'Role Delete']);

        // permissions
        Permission::create(['name' => 'Permission Index']);
        Permission::create(['name' => 'Permission Create']);
        Permission::create(['name' => 'Permission Edit']);
        Permission::create(['name' => 'Permission Delete']);

        // user
        Permission::create(['name' => 'User Index']);
        Permission::create(['name' => 'User Create']);
        Permission::create(['name' => 'User Edit']);
        Permission::create(['name' => 'User Delete']);

        // Qr Generator
        Permission::create(['name' => 'Qr Generator Index']);
        Permission::create(['name' => 'Qr Generator Create']);
        Permission::create(['name' => 'Qr Generator Edit']);
        Permission::create(['name' => 'Qr Generator Print']);

        // Stock In
        Permission::create(['name' => 'Stock In Index']);
        Permission::create(['name' => 'Stock In Create']);

        // Stock Out
        Permission::create(['name' => 'Stock Out Index']);
        Permission::create(['name' => 'Stock Out Create']);

        // Type
        Permission::create(['name' => 'Type Index']);
        Permission::create(['name' => 'Type Create']);
        Permission::create(['name' => 'Type Edit']);
        Permission::create(['name' => 'Type Delete']);

        // Part Number
        Permission::create(['name' => 'Part Number Index']);
        Permission::create(['name' => 'Part Number Create']);
        Permission::create(['name' => 'Part Number Edit']);
        Permission::create(['name' => 'Part Number Delete']);

        // Product
        Permission::create(['name' => 'Product Index']);
        Permission::create(['name' => 'Product Create']);
        Permission::create(['name' => 'Product Edit']);
        Permission::create(['name' => 'Product Show']);
        Permission::create(['name' => 'Product Delete']);

        // Report
        Permission::create(['name' => 'Report Balance']);
        Permission::create(['name' => 'Report Stock In']);
        Permission::create(['name' => 'Report Stock Out']);

        // Department
        Permission::create(['name' => 'Department Index']);
        Permission::create(['name' => 'Department Create']);
        Permission::create(['name' => 'Department Edit']);
        Permission::create(['name' => 'Department Delete']);

        // Unit
        Permission::create(['name' => 'Unit Index']);
        Permission::create(['name' => 'Unit Create']);
        Permission::create(['name' => 'Unit Edit']);
        Permission::create(['name' => 'Unit Delete']);

        // Area
        Permission::create(['name' => 'Area Index']);
        Permission::create(['name' => 'Area Create']);
        Permission::create(['name' => 'Area Edit']);
        Permission::create(['name' => 'Area Delete']);

        // Supplier
        Permission::create(['name' => 'Supplier Index']);
        Permission::create(['name' => 'Supplier Create']);
        Permission::create(['name' => 'Supplier Edit']);
        Permission::create(['name' => 'Supplier Delete']);
    }
}
