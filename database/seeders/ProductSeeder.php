<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Membuat kategori
        $categories = [
            ['name' => 'Seblak Level 1-2', 'description' => 'Pedas Ringan'],
            ['name' => 'Seblak Level 3-4', 'description' => 'Pedas Sedang'],
            ['name' => 'Seblak Level 5', 'description' => 'Pedas Gila'],
            ['name' => 'Seblak Special', 'description' => 'Menu Spesial']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Membuat produk seblak
        $products = [
            [
                'name' => 'Seblak Seafood Special',
                'desc' => 'Seblak dengan campuran seafood premium: udang, cumi, dan kerang. Dilengkapi dengan kerupuk, bakso ikan, dan telur.',
                'price' => 35000,
                'stock' => 50,
                'category_id' => 4,
                'image' => 'products/seblak-seafood.jpg'
            ],
            [
                'name' => 'Seblak Kering Manis',
                'desc' => 'Seblak kering dengan bumbu manis gurih, cocok untuk yang tidak suka pedas. Dengan kerupuk dan kencur pilihan.',
                'price' => 25000,
                'stock' => 50,
                'category_id' => 1,
                'image' => 'products/seblak-kering-manis.jpg'
            ],
            [
                'name' => 'Seblak Kuah Komplit',
                'desc' => 'Seblak kuah dengan isi lengkap: mie, kerupuk, bakso, sosis, telur, dan sayuran. Kuah kental yang gurih.',
                'price' => 30000,
                'stock' => 50,
                'category_id' => 2,
                'image' => 'products/seblak-kuah-komplit.jpg'
            ],
            [
                'name' => 'Seblak Super Pedas',
                'desc' => 'Seblak dengan level pedas maksimal. Menggunakan 5 jenis cabai berbeda. Hanya untuk penyuka pedas sejati!',
                'price' => 32000,
                'stock' => 50,
                'category_id' => 3,
                'image' => 'products/seblak-super-pedas.jpg'
            ],
            [
                'name' => 'Seblak Mie Jumbo',
                'desc' => 'Seblak dengan porsi mie jumbo, telur, bakso, dan sosis. Cocok untuk yang lapar berat.',
                'price' => 28000,
                'stock' => 50,
                'category_id' => 2,
                'image' => 'products/seblak-mie-jumbo.jpg'
            ],
            [
                'name' => 'Seblak Tulang',
                'desc' => 'Seblak dengan tambahan tulang ayam yang sudah dipresto hingga empuk. Kuah kental yang gurih.',
                'price' => 35000,
                'stock' => 50,
                'category_id' => 4,
                'image' => 'products/seblak-tulang.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}