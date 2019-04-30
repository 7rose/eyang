<?php

use Illuminate\Database\Seeder;

use App\Org;

class OrgsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Org::create([
            'name' => '融知道',
            'code' => 'rzd',
            'config' => '{
                "templet":"http://rzd.wetwvn.cn/index.php/Home/shop_info/business_info/{shop}=/shop_id/{product}",
                "shop":"w{7}",
                "product":"d{4,}"
            }',
        ]);
        
        Org::create([
            'name' => '金银河',
            'code' => 'jyh',
            'config' => '{
                "templet":"http://shmuchun.cn//loan/url/{product}/{shop}",
                "shop":"d{4,}",
                "product":"d{2,}"
            }',
        ]);
    }
}
