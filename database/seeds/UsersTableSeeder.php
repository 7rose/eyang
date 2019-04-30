<?php

use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'shop_id' => 1,
            'name' => 'kris',
            'mobile' => '7569300',
            'password' => bcrypt('000000'),
            'auth' => '{
                "root":true
            }',
        ]);

        User::create([
            'shop_id' => 2,
            'name' => 'Flora',
            'mobile' => '244467389',
            'password' => bcrypt('000000'),
            'auth' => '{
                "admin":true,
                "boss":true
            }',
        ]);

        User::create([
            'shop_id' => 4,
            'name' => 'Dianna',
            'mobile' => '1156363889',
            'password' => bcrypt('000000'),
            'auth' => '{
                "issuer":true,
                "boss":true
            }',
        ]);
    }
}


