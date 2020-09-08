<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->where('email', 'admin@admin.com')->delete();

        Admin::create([
            'name' => 'administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('k13Co7'),
        ]);
    }
}
