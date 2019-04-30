<?php

use Illuminate\Database\Seeder;

use App\Conf;

class ConfsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conf::create([
            'key' => 'product_type',
            'val' => 'cps',
            'text' => '机审秒下',
        ]);

        Conf::create([
            'key' => 'product_type',
            'val' => 'cpa',
            'text' => '新品推荐',
        ]);
    }
}
