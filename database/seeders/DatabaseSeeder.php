<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'access admin',
            'manage categories',
            'manage products',
            'manage orders',
            'manage users',
            'manage roles',
            'view reports',
        ])->map(fn ($permission) => Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']));

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        $superAdminRole->syncPermissions($permissions);
        $adminRole->syncPermissions(['access admin', 'manage categories', 'manage products', 'manage orders', 'view reports']);
        $staffRole->syncPermissions(['access admin', 'manage products', 'manage orders']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@nextronix.test'],
            [
                'name' => 'Admin Nextronix',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
        $admin->syncRoles([$superAdminRole]);

        $staff = User::updateOrCreate(
            ['email' => 'staff@nextronix.test'],
            [
                'name' => 'Staff Nextronix',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
        $staff->syncRoles([$staffRole]);

        $categories = collect([
            ['name' => 'Laptop', 'description' => 'Laptop kerja, kuliah, dan gaming.'],
            ['name' => 'Smartphone', 'description' => 'Smartphone Android dan iOS untuk kebutuhan harian.'],
            ['name' => 'Audio', 'description' => 'Headset, speaker, dan perangkat audio.'],
            ['name' => 'Kamera', 'description' => 'Kamera digital, mirrorless, dan aksesorinya.'],
            ['name' => 'Aksesoris', 'description' => 'Aksesoris gadget dan elektronik.'],
            ['name' => 'Smart Home', 'description' => 'Perangkat elektronik rumah pintar.'],
        ])->mapWithKeys(function ($item) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'image' => 'img/product-banner.jpg',
                    'is_active' => true,
                ]
            );

            return [$category->name => $category];
        });

        $products = [
            ['Laptop', 'UltraBook Pro 14', 14200000, 13200000, 12, 1800, 'img/product-1.png', true],
            ['Laptop', 'Gaming Laptop RTX', 21800000, 19900000, 8, 2800, 'img/product-2.png', true],
            ['Laptop', 'Student Laptop 15', 7850000, null, 18, 2400, 'img/product-3.png', false],
            ['Smartphone', 'Smartphone X1 5G', 5899000, 5499000, 25, 600, 'img/product-4.png', true],
            ['Smartphone', 'Smartphone Lite A8', 2499000, null, 30, 500, 'img/product-5.png', false],
            ['Smartphone', 'Flagship Camera Phone', 11800000, 10900000, 10, 650, 'img/product-6.png', true],
            ['Audio', 'Wireless Headphone Max', 1250000, 999000, 40, 900, 'img/product-7.png', true],
            ['Audio', 'Bluetooth Speaker Boom', 750000, null, 35, 1200, 'img/product-8.png', false],
            ['Audio', 'Gaming Earbuds Low Latency', 525000, 449000, 50, 300, 'img/product-9.png', false],
            ['Kamera', 'Mirrorless Camera Z', 12499000, 11990000, 7, 1300, 'img/product-10.png', true],
            ['Kamera', 'Action Cam 4K Waterproof', 2750000, null, 15, 500, 'img/product-11.png', false],
            ['Kamera', 'Digital Vlog Camera', 4999000, 4599000, 11, 900, 'img/product-12.png', false],
            ['Aksesoris', 'Mechanical Keyboard RGB', 875000, 799000, 32, 1100, 'img/product-13.png', true],
            ['Aksesoris', 'Wireless Mouse Silent', 285000, null, 60, 300, 'img/product-14.png', false],
            ['Aksesoris', 'USB-C Hub 8 in 1', 399000, 349000, 45, 350, 'img/product-15.png', false],
            ['Smart Home', 'Smart LED Bulb Pack', 220000, null, 70, 500, 'img/product-16.png', false],
            ['Smart Home', 'Smart CCTV Indoor', 699000, 649000, 20, 800, 'img/product-17.png', true],
            ['Smart Home', 'WiFi Smart Plug', 179000, null, 80, 350, 'img/product-18.png', false],
        ];

        foreach ($products as $index => [$categoryName, $name, $price, $salePrice, $stock, $weight, $image, $featured]) {
            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $categories[$categoryName]->id,
                    'name' => $name,
                    'sku' => 'ELC-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'description' => 'Produk elektronik demo untuk project Nextronix Laravel 11. Deskripsi ini bisa diedit melalui admin panel.',
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => $stock,
                    'weight_gram' => $weight,
                    'length_cm' => 20,
                    'width_cm' => 15,
                    'height_cm' => 10,
                    'image' => $image,
                    'is_featured' => $featured,
                    'is_active' => true,
                ]
            );
        }
    }
}
