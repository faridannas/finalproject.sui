<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'user_id' => 1, // Assuming user with ID 1 exists
                'product_id' => 1,
                'rating' => 5,
                'comment' => 'Seblaknya enak banget! Pedasnya pas dan bumbunya meresap sempurna. Toppingnya juga melimpah. Pasti order lagi!',
            ],
            [
                'user_id' => 1,
                'product_id' => 2,
                'rating' => 5,
                'comment' => 'Pertama kali coba seblak se-enak ini. Level pedasnya bisa disesuaikan dan rasanya nagih banget. Recommended!',
            ],
            [
                'user_id' => 1,
                'product_id' => 3,
                'rating' => 4,
                'comment' => 'Porsinya besar dan harganya worth it. Seblaknya fresh dan bumbunya mantap. Cuma pengiriman agak lama.',
            ],
            [
                'user_id' => 1,
                'product_id' => 1,
                'rating' => 5,
                'comment' => 'Seblak terenak di Jakarta! Seafoodnya segar dan banyak. Pedasnya bikin ketagihan. Must try!',
            ],
            [
                'user_id' => 1,
                'product_id' => 2,
                'rating' => 5,
                'comment' => 'Udah langganan dari awal buka. Konsisten enak dan pelayanannya ramah. Seblak favorit keluarga!',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            \App\Models\Testimonial::create($testimonial);
        }
    }
}
