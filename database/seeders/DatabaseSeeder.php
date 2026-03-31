<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ims.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Employee User',
            'email' => 'employee@ims.com',
            'password' => Hash::make('Employee@123'),
            'role' => 'employee',
            'status' => 'active'
        ]);

        // Create categories
        Category::create(['name' => 'Clothing']);
        Category::create(['name' => 'Shoes']);

        // Create supplier
        $supplier = Supplier::create(['name' => 'Supplier A', 'city' => 'Jakarta']);

        // Create products
        Product::create([
            'sku' => 'SKU001',
            'name' => 'T-Shirt',
            'category_id' => 1,
            'supplier_id' => 1,
            'product_condition' => 'new',
            'quantity' => 50
        ]);

        Product::create([
            'sku' => 'SKU002',
            'name' => 'Shoes',
            'category_id' => 2,
            'supplier_id' => 1,
            'product_condition' => 'new',
            'quantity' => 30
        ]);
    }
}
