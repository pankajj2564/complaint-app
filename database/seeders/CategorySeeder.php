<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesData = [
            'Hostel' => ['Electrical', 'Air-condition', 'Civil', 'Food', 'Wi-Fi', 'Plumbing', 'Housekeeping'],
            'Academic' => ['Teaching', 'Attendance', 'Results', 'Placement', 'Training & Internship', 'Labs'],
            'Sports' => ['Grounds', 'Gym'],
            'Campus Services' => ['Food outlets', 'Parking', 'Housekeeping', 'Security'],
            'Transport' => ['Change of Route', 'Bus Pass', 'Others'],
            'Medical' => [],
            'Fee & Accounts' => ['Scholarship', 'Late charges'],
            'Hostel Food' => [],
            'Others' => [],
        ];

        foreach ($categoriesData as $catName => $subs) {
            $category = Category::create(['name' => $catName]);

            foreach ($subs as $subName) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $subName
                ]);
            }
        }
    }
}