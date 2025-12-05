<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Product;

return new class extends Migration
{
    private $deletedProduct = null;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create "Seblak Ghost" category
        Category::firstOrCreate(
            ['name' => 'Seblak Ghost'],
            [
                'name' => 'Seblak Ghost',
                'desc' => 'Seblak dengan level pedas maksimal. Menggunakan cabai ghost pepper yang sangat pedas.',
            ]
        );

        // Delete product named "Seblak Ghost" (it will become a category instead)
        Product::where('name', 'LIKE', '%Seblak Ghost%')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Find and delete the category
        $category = Category::where('name', 'Seblak Ghost')->first();
        
        if ($category) {
            // Move any products in this category to default category first
            $defaultCategory = Category::where('name', 'Spicy Level 1')->first() 
                ?? Category::first();
            
            if ($defaultCategory) {
                Product::where('category_id', $category->id)
                    ->update(['category_id' => $defaultCategory->id]);
            }
            
            // Delete the category
            $category->delete();
        }
    }
};
