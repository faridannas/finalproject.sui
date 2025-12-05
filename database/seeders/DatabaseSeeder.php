<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Content;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun admin dengan password yang sudah di-hash
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@seblakumi.ai',
            'role' => 'admin',
            'password' => Hash::make('kakasayangumi'), // Password dienkripsi
        ]);

        // Membuat kategori
        $categories = [
            ['name' => 'Seblak Kuah', 'description' => 'Seblak with rich and flavorful soup', 'image' => 'categories/seblak-kuah.jpg'],
            ['name' => 'Seblak Kering', 'description' => 'Dry seblak with intense flavors', 'image' => 'categories/seblak-kering.jpg'],
            ['name' => 'Spicy Level 1', 'description' => 'Mild spicy - Perfect for beginners', 'image' => 'categories/level1.jpg'],
            ['name' => 'Spicy Level 2', 'description' => 'Medium spicy - A bit of heat', 'image' => 'categories/level2.jpg'],
            ['name' => 'Spicy Level 3', 'description' => 'Hot spicy - Getting serious', 'image' => 'categories/level3.jpg'],
            ['name' => 'Spicy Level 4', 'description' => 'Very hot - For brave hearts', 'image' => 'categories/level4.jpg'],
            ['name' => 'Spicy Level 5', 'description' => 'Extremely hot - Challenge accepted?', 'image' => 'categories/level5.jpg'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Membuat produk dengan gambar yang lebih spesifik dan real
        $products = [
            [
                'category_id' => 2,
                'name' => 'Seblak Kuah Original',
                'price' => 25000,
                'stock' => 50,
                'desc' => 'Seblak kuah original dengan kerupuk, bakso, dan telur dalam kuah pedas yang gurih',
                'image' => 'products/seblak-kuah-original.svg'
            ],
            [
                'category_id' => 2,
                'name' => 'Seblak Kuah Seafood',
                'price' => 30000,
                'stock' => 40,
                'desc' => 'Seblak kuah special dengan campuran seafood segar seperti cumi, udang, dan bakso ikan',
                'image' => 'products/seblak-kuah-seafood.svg'
            ],
            [
                'category_id' => 2,
                'name' => 'Seblak Kuah Komplit',
                'price' => 35000,
                'stock' => 45,
                'desc' => 'Seblak kuah dengan berbagai topping komplit termasuk mie, kerupuk, bakso, sosis, dan telur',
                'image' => 'products/seblak-kuah-komplit.svg'
            ],
            [
                'category_id' => 3,
                'name' => 'Seblak Kering Pedas',
                'price' => 20000,
                'stock' => 60,
                'desc' => 'Seblak kering dengan bumbu pedas yang meresap sempurna',
                'image' => 'products/seblak-kering-pedas.svg'
            ],
            [
                'category_id' => 3,
                'name' => 'Seblak Kering Manis',
                'price' => 22000,
                'stock' => 55,
                'desc' => 'Seblak kering dengan sentuhan manis pedas yang unik',
                'image' => 'products/seblak-kering-manis.svg'
            ],
            [
                'category_id' => 4,
                'name' => 'Seblak Level 1 - Pemula',
                'price' => 22000,
                'stock' => 45,
                'desc' => 'Seblak dengan level pedas ringan, cocok untuk pemula',
                'image' => 'products/seblak-level1.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Seblak Level 2 - Menengah',
                'price' => 23000,
                'stock' => 50,
                'desc' => 'Seblak dengan level pedas sedang yang bikin nagih',
                'image' => 'products/seblak-level2.jpg'
            ],
            [
                'category_id' => 6,
                'name' => 'Seblak Level 3 - Pedas',
                'price' => 24000,
                'stock' => 40,
                'desc' => 'Seblak dengan level pedas yang mulai menantang',
                'image' => 'products/seblak-level3.jpg'
            ],
            [
                'category_id' => 7,
                'name' => 'Seblak Level 4 - Super Pedas',
                'price' => 25000,
                'stock' => 35,
                'desc' => 'Seblak dengan level pedas yang sangat menantang',
                'image' => 'products/seblak-level4.jpg'
            ],
            [
                'category_id' => 7,
                'name' => 'Seblak Level 5 - Maut',
                'price' => 26000,
                'stock' => 30,
                'desc' => 'Seblak dengan level pedas ekstrim, tantangan sejati pecinta pedas',
                'image' => 'products/seblak-level5.jpg'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Membuat banner dengan gambar yang menarik
        $banners = [
            [
                'title' => 'Seblak Kuah Special',
                'image' => 'banners/banner-seblak-kuah.jpg',
                'link' => '/products?category=1',
                'description' => 'Nikmati kelezatan seblak kuah yang menghangatkan'
            ],
            [
                'title' => 'Tantangan Level 5',
                'image' => 'banners/banner-level5.jpg',
                'link' => '/products?category=7',
                'description' => 'Berani coba seblak level 5? Khusus pemberani!'
            ],
            [
                'title' => 'Seblak Seafood Premium',
                'image' => 'banners/banner-seafood.jpg',
                'link' => '/products?seafood=true',
                'description' => 'Seblak seafood dengan ingredients premium'
            ]
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        // Membuat user regular untuk testimonial
        $users = [
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Dewi Anjani',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Ahmad Fadillah',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Membuat testimonial yang meyakinkan
        $testimonials = [
            [
                'user_id' => 2, // Rizky
                'product_id' => 1,
                'rating' => 5,
                'comment' => 'Seblak kuahnya mantap banget! Rasanya pas, pedasnya nendang tapi tetep bisa dinikmati. Porsinya juga worth it banget buat harganya. Recommended! 👍'
            ],
            [
                'user_id' => 3, // Siti
                'product_id' => 3,
                'rating' => 5,
                'comment' => 'Seblak kuah komplit ini the best! Isinya lengkap, ada kerupuk, bakso, ceker, telur, semua fresh. Yang paling penting kuahnya bener-bener berasa homemade. Suka banget! ❤️'
            ],
            [
                'user_id' => 4, // Budi
                'product_id' => 7,
                'rating' => 4,
                'comment' => 'Level 2 ini pas buat yang suka pedas sedang. Rasanya enak, bumbunya meresap. Pelayanannya juga cepet, packaging rapi. Bakal order lagi sih!'
            ],
            [
                'user_id' => 5, // Dewi
                'product_id' => 2,
                'rating' => 5,
                'comment' => 'Seafoodnya fresh! Udang dan cuminya gede-gede, kuahnya medok. Worth every penny! Seller ramah, pengiriman cepat. Mantul! 🦐'
            ],
            [
                'user_id' => 6, // Ahmad
                'product_id' => 10,
                'rating' => 5,
                'comment' => 'Berani coba level 5, dan bener-bener PEDAS POLL! Tapi enak banget, ketagihan malah. Buat pecinta pedas wajib coba! 🔥🔥🔥'
            ],
        ];

        foreach ($testimonials as $testimonial) {
            \App\Models\Testimonial::create($testimonial);
        }

        // Membuat konten "About"
        Content::create([
            'title' => 'About Seblak Umi AI',
            'body' => 'Seblak Umi AI is your go-to place for authentic Indonesian seblak. Made with love and the finest ingredients.',
            'image' => 'about.jpg',
        ]);
    }
}
