<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SmartDatabaseSeeder extends Seeder
{
   
    // أنماط البيانات الواقعية
    private $locations = ['الرياض', 'جدة', 'مكة', 'الدمام', 'الخبر', 'المدينة', 'تبوك', 'أبها'];
    private $categories = ['سيارات', 'عقارات', 'أجهزة إلكترونية', 'ملابس', 'أثاث', 'وظائف'];
    private $carBrands = ['تويوتا', 'نيسان', 'هيونداي', 'شفروليه', 'فورد', 'مرسيدس', 'بي ام دبليو'];
    private $carModels = ['كامري', 'سوناتا', 'اكورد', 'كورولا', 'يارس', 'افينتادور'];
    private $realNames = ['محمد أحمد', 'أحمد علي', 'فاطمة حسن', 'سارة خالد', 'عبدالله سعيد', 'نورة محمد'];

    public function run()
    {
        $faker = Faker::create('ar_SA');

        // 1. إنشاء المستخدمين بأنماط مختلفة
        $this->createUsersWithPatterns($faker);
        
        // 2. إنشاء الإعلانات بأنماط واقعية
        $this->createAdsWithPatterns($faker);

        
        // 4. إنشاء تقييمات وبلاغات واقعية
        $this->createReviewsAndReports();
    }

    private function createUsersWithPatterns($faker)
    {
        $users = [];
        
        // مستخدمون مهتمون بالسيارات
        for ($i = 0; $i < 15; $i++) {
            $users[] = [
                'name' => $this->realNames[array_rand($this->realNames)],
                'email' => 'car_lover' . $i . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '05' . rand(10000000, 99999999),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // مستخدمون مهتمون بالعقارات
        for ($i = 0; $i < 10; $i++) {
            $users[] = [
                'name' => $this->realNames[array_rand($this->realNames)],
                'email' => 'estate_lover' . $i . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '05' . rand(10000000, 99999999),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // مستخدمون عاديون
        for ($i = 0; $i < 25; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone' => '05' . rand(10000000, 99999999),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }

    private function createAdsWithPatterns($faker)
    {
         $requiredCategories = ['سيارات', 'عقارات'];
        foreach ($requiredCategories as $category) {
            $exists = DB::table('categories')->where('name', $category)->exists();
            if (!$exists) {
                throw new \Exception("Category '$category' does not exist in database");
            }
        }

        $users = DB::table('users')->pluck('id');
        $categories = DB::table('categories')->pluck('id', 'name');

        if (!isset($categories['سيارات']) || !isset($categories['عقارات'])) {
        throw new \Exception('Categories table does not contain expected data');
    }
        
        $ads = [];
        
        // إعلانات سيارات (من مستخدمين مهتمين بالسيارات)
        $carLovers = DB::table('users')->where('email', 'like', 'car_lover%')->pluck('id');
        foreach ($carLovers as $userId) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $brand = $this->carBrands[array_rand($this->carBrands)];
                $model = $this->carModels[array_rand($this->carModels)];
                
                $ads[] = [
                    'user_id' => $userId,
                    'category_id' => $categories['سيارات'],
                    'price' => rand(20000, 200000),
                    'location' => $this->locations[array_rand($this->locations)],
                    'phone' => '05' . rand(10000000, 99999999),
                    'description' => "للبيع $brand $model موديل " . rand(2010, 2023) . " بحالة جيدة",
                    'views_count' => rand(50, 500),
                    'interactions_count' => rand(5, 50),
                    'is_active' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ];
            }
        }
        
        // إعلانات عقارات (من مستخدمين مهتمين بالعقارات)
        $estateLovers = DB::table('users')->where('email', 'like', 'estate_lover%')->pluck('id');
        foreach ($estateLovers as $userId) {
            for ($i = 0; $i < rand(1, 2); $i++) {
                $type = ['شقة', 'فيلا', 'أرض', 'مكتب'][array_rand(['شقة', 'فيلا', 'أرض', 'مكتب'])];
                
                $ads[] = [
                    'user_id' => $userId,
                    'category_id' => $categories['عقارات'],
                    'price' => rand(100000, 2000000),
                    'location' => $this->locations[array_rand($this->locations)],
                    'phone' => '05' . rand(10000000, 99999999),
                    'description' => "$type للبيع في حي راقي مساحة " . rand(100, 500) . " متر",
                    'views_count' => rand(70, 600),
                    'interactions_count' => rand(8, 60),
                    'is_active' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ];
            }
        }

        $categoryIds = $categories->values()->toArray();
        
        // إعلانات متنوعة من مستخدمين عاديين
        foreach ($users as $userId) {
            if (rand(0, 1)) {
                // $categoryId = array_rand($categories);
                // $categoryName = array_search($categoryId, $categories->toArray());
                
                $ads[] = [
                    'user_id' => $userId,
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'price' => rand(100, 5000),
                    'location' => $this->locations[array_rand($this->locations)],
                    'phone' => '05' . rand(10000000, 99999999),
                    'description' => $faker->realText(100),
                    'views_count' => rand(10, 200),
                    'interactions_count' => rand(1, 20),
                    'is_active' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('advs')->insert($ads);
    }

    

    private function createReviewsAndReports()
    {
        $users = DB::table('users')->pluck('id');
        $ads = DB::table('advs')->pluck('id');
        
        $reports = [];
        
 
        // بلاغات (عدد قليل)
        for ($i = 0; $i < 10; $i++) {
            $reports[] = [
                'user_id' => $users->random(),
                'adv_id' => $ads->random(),
                'type' => ['spam', 'fraud', 'inappropriate_content', 'duplicate'][array_rand(['spam', 'fraud', 'inappropriate_content', 'duplicate'])],
                'content' => 'هذا الإعلان غير مناسب',
                'is_view' => rand(0, 1),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ];
        }
        
        DB::table('reports')->insert($reports);
    }
}
