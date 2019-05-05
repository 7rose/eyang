<?php

use Illuminate\Database\Seeder;

use App\Shop;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::create([
            'parent_id' => 0,
            'level' => 0,
            'domain' => 'root',
        ]);

        Shop::create([
            'parent_id' => 1,
            'level' => 1,
            'domain' => 'eyang.xyz',
            'info' => '{
                "limit":20,
                "name":"亿羊",
                "full_name":"亿羊金融信息",
                "color":"pink",
                "wechat":"https://u.wechat.com/MHm4WIDi5EMNXP277x6m9AA"
            }'
        ]);

        Shop::create([
            'parent_id' => 2,
            'level' => 2,
            'domain' => 'szdai.xyz',
            'info' => '{
                "name":"山竹",
                "full_name":"山竹贷",
                "color":"violet",
                "wechat":"https://u.wechat.com/MHm4WIDi5EMNXP277x6m9AA"
            }',
        ]);

        Shop::create([
            'parent_id' => 1,
            'level' => 1,
            'domain' => 'redniu.top',
            'info' => '{
                "name":"红牛",
                "full_name":"红牛贷",
                "color":"red",
                "wechat":"https://u.wechat.com/MNVZKdzHjyyiNuaerhCpBxc"
            }',
        ]);

        Shop::create([
            'parent_id' => 1,
            'level' => 1,
            'domain' => 'mmdai.xyz',
            'info' => '{
                "name":"猫咪",
                "full_name":"猫咪贷",
                "color":"orange",
                "wechat":"https://u.wechat.com/ENjAf5xmzHilN6IABEKJnd8"
            }',
        ]);

    }
}

